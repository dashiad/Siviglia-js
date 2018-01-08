<?php
namespace lib\output\html\renderers;

class JsonRenderer
{
    public function render($page, $requestedPath, $outputParams)
    {
        header('Content-Type: application/json');
        global $oCurrentUser;

        if(!$page->definition["SOURCES"])
        {
            die(json_encode(array("success"=>0,"errCode"=>1,"errText"=>'No sources for this path')));
        }

        $sources=$page->definition["SOURCES"];

        foreach($sources as $key=>$definition)
        {
            switch($definition["ROLE"])
            {
                case 'action':
                {
                    $actionName=$definition["NAME"];
                    $object=$definition["MODEL"];
                    if($actionName=="" || $object=="")
                        return false; // TODO : Redirigir a pagina de error.

                    $actionResult=new \lib\action\ActionResult();

                    $formInfo=\lib\output\html\Form::getFormPath($object,$actionName);
                    $className=$formInfo["CLASS"];
                    $classPath=$formInfo["PATH"];

                    // Se incluye la definicion del formulario.
                    include_once($classPath);
                    $curForm=new $className($actionResult);
                    $result=$curForm->process(false);
                    if($result->isOk())
                    {
                        // Sale aqui directamente.
                        echo json_encode(array("success"=>true));
                        return;
                    }
                    $res=array("success"=>false,
                        "errors"=>array()
                    );

                    if(is_array($result->fieldErrors))
                    {
                        foreach($result->fieldErrors as $key=>$value)
                        {
                            $keys=array_keys($value);
                            $res["errors"][$key]=$keys[0];
                        }
                    }
                    if(is_array($result->globalErrors))
                        $res["globalErrors"]=$result->globalErrors;

                    if(is_array($result->permissionError))
                        $res["permissionError"]=$result->permissionError;
                    echo json_encode($res);
                    return;
                }break;
                case 'multiple':
                {
                    $obj=\lib\datasource\DataSourceFactory::getDataSource($definition["MODEL"], $definition["NAME"]);
                    $obj->setParameters($page);
                    $it=$obj->fetchAll();
                    $result["data"]=$it->getFullData();
                    $result["count"]=$obj->count();
                    $result["result"]=1;
                }break;
                case 'dynlist':
                case 'list':
                case 'view':{
                    $obj=  \lib\datasource\DataSourceFactory::getDataSource($definition["MODEL"], $definition["NAME"]);
                    $obj->setParameters($page);
                    $obj->fetchAll();
                    $iterator=$obj->getIterator();
                    if($definition["ROLE"]=='view')
                    {

                        $data=$iterator->getFullRow();
                        if(!$data)
                            $result=array("data"=>null,"result"=>0,"error"=>1,"message"=>"Object not found","count"=>0);
                        else
                            $result=array("result"=>1,"error"=>0,"data"=>array($data),"count"=>1);

                    }
                    else
                    {
                        $dsDefinition=\lib\model\types\TypeFactory::getObjectDefinition($definition["MODEL"]);
                        if($definition["NAME"]=="FullList")
                        {
                            $result["indexField"]=$dsDefinition["INDEXFIELDS"][0];
                        }
                        $result["definition"]=$obj->getOriginalDefinition();
                        unset($result["definition"]["STORAGE"]);
                        $iterator=$obj->getIterator();
                        $result["data"]=$iterator->getFullData();
                        $result["start"]=$obj->getStartingRow();
                        $result["end"]=$result["start"]+$iterator->count();
                        $result["count"]=$obj->count();
                        $result["result"]=1;
                    }
                }break;

                default:{

                }break;
            }
        }

        echo json_encode($result);
        \Registry::$registry["PAGE"]=$page;
    }
}