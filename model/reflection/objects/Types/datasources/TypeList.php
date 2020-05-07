<?php
namespace model\reflection\Types\datasources;
use model\reflection\Model\types\BaseType;

/**
 * FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
* CLASS:AdminFullList
 *
 *
 **/

class TypeList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        "PARAMS"=>array(
            "allowBaseType"=>[
                "TYPE"=>"Boolean",
                "DEFAULT"=>false
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
            )
        ),
        'PERMISSIONS'=>array(
            array(
                'MODEL'=>'Types',
                'PERMISSION'=>'REFLECTION'
            )
        ),
        'SOURCE'=>[

            'METHOD'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getTypeList'
                )
            )

            ]
    );


    function getTypeList()
    {
        $src=glob(PROJECTPATH."/model/reflection/objects/Types/types/*.php");
        $result=[];
        for($k=0;$k<count($src);$k++)
        {
            $curType=[
                "package"=>"reflection"
            ];
            $cur=basename($src[$k]);
            //if($cur!=="BaseType.php")
            // {

            $p=explode(".",$cur);
            $curClass="/model/reflection/Types/types/".$p[0];
            $curType["fullName"]=$curClass;
            $short=$p[0];
            if($short=="_Array")
                $short="Array";
            if($short=="_String")
                $short="String";
            $curType["smallName"]=$short;
            $result[]=$curType;
        }
        // Se escanean los paquetes existentes, obteniendo los tipos que haya.
        \model\reflection\ReflectorFactory::iterateOnPackages(function($pkg) use (& $result){
            if($pkg->getName()=="reflection")
                return;
            $pkg->iterateOnModels(function($model) use ($pkg,& $result){
                $d=$model->getModelDescriptor();
                $curType["package"]=$pkg->getName();
                if($d->isPrivate())
                {
                    $modelname=$d->getNamespaceModel();
                    $submodel=$d->getClassName();
                }
                else
                {
                    $modelname=$d->getClassName();
                    $submodel=null;
                }
                $typeList=\lib\model\Package::getInfo(
                    $pkg->getName(),
                    $modelname,
                    $submodel,
                    \lib\model\Package::TYPE,
                    "*");
                if($typeList!==null){
                    for($k=0;$k<count($typeList);$k++)
                    {
                        $cName=str_replace('\\',"/",$typeList[$k]["class"]);
                        $curType["smallName"]=$cName;
                        $curType["fullName"]=$cName;
                        $result[]=$curType;
                    }
                }
            });
        });
        return $result;
    }
}
?>
