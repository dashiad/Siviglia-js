<?php
namespace lib\output\json;
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 23/07/13
 * Time: 2:20
 * To change this template use File | Settings | File Templates.
 */
class JsonAction extends \lib\output\Action {
    var $keys;
    function __construct($params,$request,$mode)
    {
        if($mode==\lib\routing\Action::MODE_PROCESS_FORM) {
            $data = $request->getActionData();
            if (isset($data['json'])) {
                $json = $data['json'];
                $data = json_decode(trim($data['json']), true);
            }

            if (!$data) {
                $cad = str_replace('\"', '"', $json);
                $data = json_decode($cad, true);
                if (!$data) // TODO : ENVIAR MENSAJE DE ERROR CORRECTO
                    error_log("NULO AL DECODIFICARLO:" . json_last_error());
            }
            \Registry::$registry["action"] = $data;
            $this->setActionName($data["name"]);
            $this->setObjectName($data["object"]);
            if(isset($data["keys"]))
            {
                $this->keys=$data["keys"];
                foreach($data["keys"] as $curKey=>$curValue) {

                    \Registry::$registry["action"]["FIELDS"][$curKey] = $curValue;
                }
            }
        }
        else
        {
            $this->setActionName($params["NAME"]);
            $this->setObjectName($params["MODEL"]);
            $this->keys=null;

        }
        parent::__construct($request,$mode);
    }
    function getKeys()
    {
        if($this->mode==\lib\routing\Action::MODE_LOAD_FORM)
        {
            return $this->request->getParameters();
        }
        else
            return $this->keys;
    }
    function resolve()
    {
        if($this->mode==\lib\routing\Action::MODE_LOAD_FORM)
        {
            return json_encode([
                "result"=>1,"error"=>0,"form"=>[
                "definition"=>$this->form->getDefinition(),
                "value"=>$this->form->getAsDictionary()
                ]
            ]);
        }
        $this->execute();
        $result=$this->getActionResult();
        if($result->isOk())
        {
            return json_encode($this->composeResultOk($result,$this->form));
        }
        return json_encode(array(
            "result"=>0,"error"=>1,"action"=>$result
        ));
    }
    function composeResultOk($actionResult,$curForm)
    {
        $model=$actionResult->getModel();
        if(!$model)
        {
            // No hay modelo.Posiblemente fue una accion "Delete"
            $result=array("result"=>1,"error"=>0,"action"=>$actionResult,"data"=>null,"start"=>0,"end"=>0,"count"=>0);
        }
        else
        {
        /*    $objName=$model->__getFullObjectName();
            $outputDatasource = 'View';
            $def = $curForm->getDefinition();
            if($def['OUTPUT_DATASOURCE']) {
                $outputDatasource = $def['OUTPUT_DATASOURCE'];
            }
            $ds=\lib\datasource\DataSourceFactory::getDataSource($model->__getFullObjectName(), $outputDatasource);
            //$ds=\lib\datasource\DataSourceFactory::getDataSource($model->__getFullObjectName(), "View");
            $ds->setParameters($model);
            $ds->fetchAll();
            $iterator=$ds->getIterator();
*/
            $result=array(
                "result"=>1,
                "error"=>0,
                "action"=>$actionResult,
                "data"=>[$this->form->getAsDictionary()]//$iterator->getFullData()
            );
        }
        return $result;
    }
}
