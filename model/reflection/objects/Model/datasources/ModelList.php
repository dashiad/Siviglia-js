<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/

class ModelList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'smallName'=>[
                "TYPE"=>"String",
                "PARAMTYPE"=>"DYNAMIC"
            ],
            'fullName'=>[
                "TYPE"=>"String"
            ]
        ),
        'FIELDS'=>array(
            'package'=>array(
                'TYPE'=>'String',
            ),
            'smallName'=>array(
                'TYPE'=>"String"
            ),
            'fullName'=>array(
                'TYPE'=>"String",
            ),
            'modelPath'=>array(
                'TYPE'=>"String",
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
                    "METHOD"=>'getModelList'
                )
            )

            ]
    );

    function getModelList($ds)
    {
        $list=[];
        $filter=null;
        $filterField=null;
        if($ds->smallName!==null)
        {
            $filter=$ds->smallName;
            $filterField="smallName";
        }

        else
        {
            if($ds->fullName!==null)
            {
                $filter=$ds->fullName;
                $filterField="fullName";
            }
        }
        $filter=str_replace('\\', '/', $filter);
        \model\reflection\ReflectorFactory::iterateOnPackages(function($pkg) use (& $list,$filter){
            $pkg->iterateOnModelTree(function($model) use ($pkg,& $list,$filter){
                //if($pkg->getName()!=="reflection") {
                    $normalized=str_replace('\\', '/', $model->getClassName());
                    if($filter==null || $normalized==$filter) {
                        $list[] = ["package" => $pkg->getName(),
                            "smallName" => $normalized,
                            "fullName" => str_replace('\\','/',$model->getClassName()),
                            "modelPath" => $model->getReflectedModel()->__getModelDescriptor()->getBaseDir()
                        ];
                    }
                //}
            });
        });
        return $list;
    }
}
?>
