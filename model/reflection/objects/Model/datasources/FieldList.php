<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/
include_once(PROJECTPATH."/model/reflection/objects/Html/forms/FormDefinition.php");
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
        'STORAGE'=>array(
            'DICTIONARY'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getFieldList'
                )
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
