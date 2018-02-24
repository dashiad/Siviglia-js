<?php
namespace model\reflection\ReflectorFactory\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class NamespaceObjects
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Tree',
        'IS_ADMIN'=>1,
        "PARAMS"=>array(
            "namespace"=>array('TYPE'=>'String',
                'LABEL'=>'Model Name',
                "REQUIRED"=>true
                )
        ),
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
                    "MODEL"=>'self',
                    "METHOD"=>'getLayerObjects'
                )
            )
        )
    );

    function getLayerObjects($ds)
    {
        $layers=\model\reflection\ReflectorFactory::getLayers();
        for($k=0;$k<count($layers);$k++) {
            if($layers[$k]["name"]==$ds->namespace) {
                return \model\reflection\ReflectorFactory::getLayerObjects($layers[$k]["name"], $layers[$k]["path"], null, "objects");
            }
        }
        return array();
    }
}
?>