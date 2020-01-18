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
            case "formDefinition":{}break;
            case "formDefinitionField":{}break;
            case "modelDefinition":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_DEFINITION,$model);
            }break;
            case "modelDefinitionField":{
                $out=$mDProv->getMetaData(MetaDataProvider::META_MODEL,MetaDataProvider::GET_FIELD,$model,$params->fieldName);
            }break;
            case "datasourceDefinition":{}break;
            case "datasourceDefinitionField":{}break;
            case "datasourceParams":{}break;
            case "datasourceParamsField":{}break;
            case "actionDefinition":{}break;
            case "actionDefinitionField":{}break;
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
