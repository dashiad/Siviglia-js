<?php
namespace model\reflection\Model;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/Definition.php
CLASS:Definition
 *
 *
 **/

class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'ReflectionSerializer',
        'DEFAULT_WRITE_SERIALIZER'=>'ReflectionSerializer',
        'INDEXFIELDS'=>array('MODEL'),
        'TABLE'=>"Model",
        'LABEL'=>'Model',
        'SHORTLABEL'=>'Model',
        'CARDINALITY'=>'300',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'MODEL'=>array(
                'TYPE'=>'String',
                /*'SOURCE'=>[
                    "TYPE"=>"DataSource",
                    "MODEL"=>"/model/reflection/Model",
                    "DATASOURCE"=>"ModelList",
                    "LABEL"=>"smallName",
                    "VALUE"=>"fullName"
                ]*/
            ),
            'definition'=>array(
                "LABEL"=>"Definition",
                "TYPE"=>"/model/reflection/Model/types/ModelType"
            )
        ),
        'PERMISSIONS'=>array(),
    );
}
