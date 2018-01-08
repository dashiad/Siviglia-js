<?php
/**
    Punto de entrada a las peticiones de metadata (convertidas a json).
    La metadata se puede obtener de un formulario, datasource u objeto.
*/
include_once("CONFIG_default.php");
include_once(LIBPATH."startup.php");
class BaseMetadata
{
    var $definition;
    function __construct($basePath, $namespaced, $variableName, $isStatic,$fieldResolution, $fieldHiding,$removeKeys,$definition=null)
    {
        
        $fileName=realpath($basePath);
        if(strstr($basePath,PROJECTPATH)===false || !is_file($basePath))
        {
                echo "{}";exit();
        }
        if($definition==null)
        {
            include_once($basePath);
        
            if($isStatic)
            {
                $inst = new $namespaced();
                $definition=$inst::$$variableName;
            }
            else
            {
                $inst=new $namespaced();
                $definition=$inst->getDefinition();
            }
        }
        if($fieldResolution)
        {
            foreach($fieldResolution as $value2)
            {
                $fDef=$definition[$value2];
                foreach($fDef as $key=>$value)
                {
                    $type=\lib\model\types\TypeFactory::getType(null,$value);
                    $definition[$value2][$key]=$type->definition;
                    switch($definition[$value2][$key]["TYPE"])
                    {
                    case "File":
                    case "Image":
                        {
                            unset($definition[$value2][$key]["TARGET_FILEPATH"]);
                            unset($definition[$value2][$key]["TARGET_FILENAME"]);
                        }break;
                    }
                }
            }
        }
        if($fieldHiding)
        {
            foreach($fieldHiding as $value)
            {
                foreach($definition[$value] as $kk=>$vv)
                {
                    if($vv["PUBLIC_FIELD"]==false)
                        unset($definition[$value][$kk]);
                }
            }
        }

        if($removeKeys)
        {
            foreach($removeKeys as $value)
            {
                $start = & $definition;
                $parts=explode("/",$value);
                for($k=0;$k<count($parts)-1;$k++)
                    $start=& $start[$parts[$k]];
                unset($start[$parts[$k]]);
            }
        }
        $this->definition=$definition;

    }
}

class ModelMetaData extends BaseMetadata {
    function __construct($objName)
    {
        $Obj=new \lib\reflection\model\ObjectDefinition($objName);
        $path=$Obj->getDestinationFile("Definition.php");
        parent::__construct($path, $Obj->getNamespaced().'\Definition', "definition", true ,array(), false,array('STORAGE'));
        $this->definition["layer"]=$Obj->layer;
        if($Obj->isPrivate())
        {
            $this->definition["private"]=1;
            $this->definition["parentObject"]=$Obj->getNamespaceModel();
        }
        if(isset($this->definition["EXTENDS"]))
        {
            $parentMeta=new ModelMetaData($this->definition["EXTENDS"]);
            $parentFields=$parentMeta->definition["FIELDS"];
            unset($this->definition["FIELDS"][$this->definition["INDEXFIELDS"][0]]);
            $this->definition["FIELDS"]=array_merge($this->definition["FIELDS"],$parentFields);
            $this->definition["ALIASES"]=array_merge($this->definition["ALIASES"]?$this->definition["ALIASES"]:array(),
                                                     $parentMeta->definition["ALIASES"]?$parentMeta->definition["ALIASES"]:array());
        }
    }
}
class DataSourceMetaData extends BaseMetadata {
    function __construct($objName,$dsName)
    {
        include_once(LIBPATH."/datasource/DataSourceFactory.php");
        $ds=\lib\datasource\DataSourceFactory::getDataSource($objName,$dsName);
        $this->definition=$ds->getOriginalDefinition();
        unset($this->definition["STORAGE"]);
        unset($this->definition["PERMISSIONS"]);
    }
}
class FormMetaData extends BaseMetadata {
    function __construct($objName,$formName)
    {

        $Obj=new \lib\reflection\model\ObjectDefinition($objName);
        $path=$Obj->getDestinationFile("/html/forms/$formName.php");
        parent::__construct($path, $Obj->getNamespaced().'\html\forms\\'.$formName, "definition", false ,array(), false,array());
    }
}

include_once(LIBPATH."/Request.php");
$request=Request::getInstance();
// Es necesario inicializar el registro
Registry::initialize($request);
$params=$request->getParameters();
switch($params["type"])
{
    case "model":
    {
        $obj=new ModelMetaData(trim($params["model"],". "));
        echo json_encode(array("error"=>0,"model"=>$obj->definition));
    }break;
    case "datasource":
    {
       $obj=new DataSourceMetaData(trim($params["model"],". "),trim($params["name"],". "));
       echo json_encode(array("error"=>0,"datasource"=>$obj->definition));       
    }break;
    case "form":
    {
        $obj=new FormMetaData(trim($params["model"],". "),trim($params["name"],". "));
        echo json_encode(array("error"=>0,"form"=>$obj->definition));
    }break;
    case "allModels":
    {

    }break;
}
