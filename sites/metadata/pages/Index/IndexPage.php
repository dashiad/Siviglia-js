<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 18/11/2017
 * Time: 23:44
 */

namespace sites\metadata\pages\Index;

use \lib\metadata\MetaDataProvider;
class IndexPage extends \model\web\Page
{
    var $requestParams;
    function initializePage($params)
    {

    }
    function getMetaDefinition()
    {
        $params=$this;
        // TODO: Implement initializePage() method.
        $mDProv=new MetaDataProvider();
        $model="/model/".$this->modelName;
        $out=null;
        switch($this->type)
        {
            case "formDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_FORM,MetaDataProvider::GET_DEFINITION,$model,$this->formName);
            }break;
            case "formDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_FORM,MetaDataProvider::GET_DEFINITION,$model,$this->formName,$this->fieldName);
            }break;
            case "modelDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_DEFINITION,$model);
            }break;
            case "modelDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_FIELD,$model,null,$this->fieldName);
            }break;
            case "datasourceDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_DEFINITION,$model,$this->datasourceName);
            }break;
            case "datasourceDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_FIELD,$model,$this->datasourceName,$this->fieldName);
            }break;
            case "datasourceParams":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_PARAM_DEFINITION,$model,$this->datasourceName);
            }break;
            case "datasourceParamsField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_PARAM,$model,$this->datasourceName,$this->fieldName);
            }break;
            case "actionDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_ACTION,MetaDataProvider::GET_DEFINITION,$model,$this->actionName);
            }break;
            case "actionDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_ACTION,MetaDataProvider::GET_FIELD,$model,$this->actionName,$this->fieldName);
            }break;

            case "pageDefinition":{}break;
            case "pageDefinitionField":{}break;
            case "typeDefinition":{}break;
            case "other":{}break;
            case "forms":{}break;
            case "datasources":{}break;
            case "actions":{}break;
            case "pages":{}break;
        }
        if($out!==null)
            echo json_encode($out);

    }
    function validate()
    {

        $mDProv=new MetaDataProvider();
        $model="/model/".$this->modelName;
        $result=[
            "error"=>0,
                "meta"=>[
                    "model"=>$model,
                    "type"=>"form",
                    "name"=>$this->formName,
                    "field"=>$this->fieldName
                ]
            ];
        switch($this->type)
        {
            case "validateFormField":{
                try {
                    $mDProv->validate(MetaDataProvider::META_FORM, $model, $this->formName, $this->fieldName, $this->fieldPath, $this->fieldValue);
                    return $result;

                }catch(\lib\model\BaseException $e)
                {
                    $result["error"]=1;
                    $result["errorCode"]=$e->getCode();
                    $result["errorParams"]=$e->getParams();
                    $result["errorClass"]=get_class($e);
                }
                return $result;

            }break;

        }

    }
}
