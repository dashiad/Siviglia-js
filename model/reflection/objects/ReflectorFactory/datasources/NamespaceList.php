<?php
namespace model\reflection\ReflectorFactory\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class NamespaceList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'FIELDS'=>array(
            'name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Model Name'
            ),
            'path'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Path'
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
                    "MODEL"=>'\model\reflection\ReflectorFactory',
                    "METHOD"=>'getLayers'
                )
            )
        )
    );
}
?>