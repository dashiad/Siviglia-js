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
    function initializePage($params)
    {
        // TODO: Implement initializePage() method.
        $mDProv=new MetaDataProvider();
        $model="/model/".$params->modelName;
        $out=null;
        switch($params->type)
        {
            case "formDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_FORM,MetaDataProvider::GET_DEFINITION,$model,$params->formName);
            }break;
            case "formDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_FORM,MetaDataProvider::GET_DEFINITION,$model,$params->formName,$params->fieldName);
            }break;
            case "modelDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_DEFINITION,$model);
            }break;
            case "modelDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_FIELD,$model,null,$params->fieldName);
            }break;
            case "datasourceDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_DEFINITION,$model,$params->datasourceName);
            }break;
            case "datasourceDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_FIELD,$model,$params->datasourceName,$params->fieldName);
            }break;
            case "datasourceParams":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_PARAM_DEFINITION,$model,$params->datasourceName);
            }break;
            case "datasourceParamsField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_DATASOURCE,MetaDataProvider::GET_PARAM,$model,$params->datasourceName,$params->fieldName);
            }break;
            case "actionDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_ACTION,MetaDataProvider::GET_DEFINITION,$model,$params->actionName);
            }break;
            case "actionDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_ACTION,MetaDataProvider::GET_FIELD,$model,$params->actionName,$params->fieldName);
            }break;
            case "validateFormField":{
                $out=$mDProv->validate()
            }

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
}
