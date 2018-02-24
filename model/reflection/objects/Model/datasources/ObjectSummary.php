<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/
include_once(PROJECTPATH."/model/reflection/objects/Html/forms/FormDefinition.php");
class ObjectSummary
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Tree',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'class'=>array("TYPE"=>"String","REQUIRED"=>true)
        ),
        'FIELDS'=>array(
            'name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Model Name'
            ),
            'definition'=>array(
                'TYPE'=>"PHPVariable",
                'LABEL'=>"Object definition"
            ),
            'datasources'=>array(
                'TYPE'=>"PHPVariable",
                'LABEL'=>"DataSources"
            ),
            'actions'=>array(
                'TYPE'=>"PHPVariable",
                'LABEL'=>"Actions"
            ),
            'html'=>array(
                'TYPE'=>'PHPVariable'
            ),
            'js'=>array(
                'TYPE'=>'PHPVariable'
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
                    "METHOD"=>'getObjectSummary'
                )
            )
        )
    );

    function getObjectSummary($ds)
    {
        $class=str_replace('\\','/',$ds->class);
        $modelDef=\model\reflection\ReflectorFactory::getModel($class);

        $result=array(
            "definition"=>$modelDef->getDefinition(),
        );
        $datasources=$modelDef->getDataSources();
        foreach($datasources as $key=>$value)
            $result["datasources"][$key]=$value->getDefinition();

        $actions=$modelDef->getActions();
        foreach($actions as $key=>$value)
            $result["actions"][$key]=$value->getDefinition();
        $forms=\model\reflection\Html\forms\FormDefinition::getModelForms($class);
        foreach($forms as $key=>$value)
            $result["forms"][$key]=$value->getDefinition();
    }
}
?>