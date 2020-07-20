<?php
namespace model\reflection\ArrayDefinition\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class Get
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Model Name'
            )
        ),
        'FIELDS'=>array(
            'name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Model Name'
            ),
            'path'=>array(
                'TYPE'=>'PHPVariable',
                'LABEL'=>'Definition'
            )
        ),
        'PERMISSIONS'=>array(
            array(
                'MODEL'=>'Site',
                'PERMISSION'=>'REFLECTION'
            )
        ),
        'SOURCE'=>[
        'STORAGE'=>array(
            'DICTIONARY'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'\model\reflection\ArrayDefinition',
                    "METHOD"=>'getDefinition'
                )
            )
        )
            ]
    );
}
?>
