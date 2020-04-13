<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/
class FieldList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        "PARAMS"=>[
            "model"=>[
                "TYPE"=>"String",
                "REQUIRED"=>true
            ]
        ],
        'FIELDS'=>array(
            'NAME'=>array(
                'TYPE'=>'String',
            ),
            'TYPE'=>array(
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
                "METHOD"=>'getFieldList'
            )
        )
            ]
    );

    function getFieldList($ds)
    {
        $list=[];
        $srv=\Registry::getService("model");
        $ins=$srv->getModel($ds->model);
        $def=$ins->getDefinition();
        $res=[];
        foreach($def["FIELDS"] as $k=>$v)
        {
            $res[]=[
                "NAME"=>$k,
                "TYPE"=>$v["TYPE"]
            ];
        }
        return $res;
    }
}
?>
