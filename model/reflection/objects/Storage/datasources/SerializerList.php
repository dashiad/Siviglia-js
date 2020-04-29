<?php
namespace model\reflection\Storage\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class SerializerList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
        ),
        'FIELDS'=>array(
            'name'=>array(
                'TYPE'=>'String',
            ),
            'type'=>array(
                'TYPE'=>"String"
            )
        ),
        'PERMISSIONS'=>array(
            array(
                'MODEL'=>'Site',
                'PERMISSION'=>'REFLECTION'
            )
        ),
        'SOURCE'=>[
            'METHOD'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getStorageList'
                )
            )
            ]
    );

    function getStorageList($ds)
    {
        $configService=\Registry::getService("config");
        $conf=$configService->getConfig();
        $storage=$conf["SERIALIZERS"];
        $list=[];

        foreach($storage as $k=>$v)
        {
            $list[]=["name"=>$v["NAME"],"type"=>$v["TYPE"]];
        }
        return $list;
    }
}
?>
