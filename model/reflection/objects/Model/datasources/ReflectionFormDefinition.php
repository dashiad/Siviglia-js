<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/
include_once(PROJECTPATH."/model/reflection/objects/base/AutoUIDefinition.php");
class ReflectionFormDefinition
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Tree',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'type'=>array("TYPE"=>"String","REQUIRED"=>true)
        ),
        'FIELDS'=>array(
            'name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Model Name'
            ),
            'definition'=>array(
                'TYPE'=>"PHPVariable",
                'LABEL'=>"Form definition"
            )
        ),
        'PERMISSIONS'=>array(
            array(
                'MODEL'=>'Site',
                'PERMISSION'=>'REFLECTION'
            )
        ),
        'STORAGE'=>array(
            'DICTIONARY'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getFormDefinition'
                )
            )
        )
    );

    function getFormDefinition($ds)
    {
        $type=$ds->type;
        switch($type)
        {
            case "ModelDefinition":{
                $def=$this->loadDefinitionFrom("Model","Model");
            }break;
            case "ModelAction":{
                $def=$this->loadDefinitionFrom("Action","Action");
            }break;
            case "ModelDatasource":{
                $def=$this->loadDefinitionFrom("DataSource","DataSource");
            }break;
            case "ModelHtmlForm":{
                $def=$this->loadDefinitionFrom("Html","forms/Form");
            }break;
            case "ModelHtmlView":{
                $def=$this->loadDefinitionFrom("Html","views/View");
            }break;
            case "ModelHtmlWidget":{
                $def=$this->loadDefinitionFrom("Html","views/Widget");
            }break;
            case "ModelJsForm":{
                $def=$this->loadDefinitionFrom("Js","jquery/Form");
            }break;
            case "ModelJsView":{
                $def=$this->loadDefinitionFrom("Js","jquery/View");
            }break;
            case "ModelJsWidget":{
                $def=$this->loadDefinitionFrom("Js","jquery/Widget");
            }break;
        }
        $result=array(
            "name"=>$type,
            "definition"=>$def["DEFINITION"]
        );

        return $result;
    }
    function loadDefinitionFrom($object,$fileName)
    {
        $instance=\model\reflection\base\AutoUIDefinition::getInstance($object,$fileName);
        return $instance->getDefinition();
    }
}
?>