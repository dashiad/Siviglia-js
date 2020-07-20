<?php
namespace model\reflection\ReflectorFactory\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class FullTree
{
    static  $definition=array(
        'ROLE'=>'tree',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'TYPES'=>[
           "Reflection.modelTree"=>[
               "TYPE"=>"Container",
               "LABEL"=>"Node",
               "FIELDS"=>[
                   "resource"=>["LABEL"=>"Resource","TYPE"=>"String"],
                   "package"=>["LABEL"=>"package","TYPE"=>"String"],
                   "model"=>["LABEL"=>"model","TYPE"=>"String"],
                   "submodel"=>["LABEL"=>"submodel","TYPE"=>"String"],
                   "class"=>["LABEL"=>"class","TYPE"=>"String"],
                   "file"=>["LABEL"=>"file","TYPE"=>"String"],
                   "name"=>["LABEL"=>"name","TYPE"=>"String"],
                   "item"=>["LABEL"=>"item","TYPE"=>"String"],
                   "children"=>["LABEL"=>"Children",
                       "TYPE"=>"Array",
                       "ELEMENTS"=>"Reflection.modelTree",
                       "DEFAULT"=>null
                       ]
               ]
           ]
        ],
        'FIELDS'=>array(
            'root'=>array(
                'TYPE'=>'Reflection.modelTree',
                'LABEL'=>'Root'
            )
        ),
        'PERMISSIONS'=>array("Public"),
        'SOURCE'=>[

            'METHOD'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getTree'
                )
            )
            ]
    );
    function getTree()
    {
        $out=["resource"=>"root","children"=>[]];
        \model\reflection\ReflectorFactory::iterateOnPackages(function($pkg) use (&$out) {
            $res=$pkg->getPackage()->getResourceTree();
            if($res)
                $out["children"][]=$res;
        });
        return [["root"=>$out]];
    }
}
?>
