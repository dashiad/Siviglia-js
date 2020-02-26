<?php
namespace model\reflection\Model\datasources;
/**
FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
CLASS:AdminFullList
 *
 *
 **/
include_once(PROJECTPATH."/model/reflection/objects/Html/forms/FormDefinition.php");
class ModelList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
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
        \model\reflection\ReflectorFactory::iterateOnPackages(function($pkg) use (& $list){
            $pkg->iterateOnModels(function($model) use ($pkg,& $list){
                if($pkg->getName()!=="reflection") {
                    $list[] = ["package" => $pkg->getName(),
                        "smallName" => str_replace('\\', '/', $model->getClassName()),
                        "fullName" => $model->getClassName(),
                        "modelPath" => $model->modelDescriptor->getBaseDir()
                    ];
                }
            });
        });
        return $list;
    }
}
?>
