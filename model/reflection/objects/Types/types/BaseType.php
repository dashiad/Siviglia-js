<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class BaseType extends \lib\model\types\TypeSwitcher
{
    static $typeCache;
    function __construct($name,$parentType=null, $value=null,$validationMode=null)
    {
        parent::__construct($name,[
            "LABEL"=>"Type",
            "REQUIRED"=>true,
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "IMPLICIT_TYPE"=>"ModelField",
            "ALLOWED_TYPES"=>BaseType::getAllTypeClasses()
        ],$parentType,$value,$validationMode);
    }
    function isAllowedType($type)
    {
        if($type=="Array" || $type=="String")
            $type="_".$type;
        if(parent::isAllowedType($type))
            return true;
        return isset($this->allowed_types["/model/reflection/Types/types/".$type]);
    }
    function getTypeInstance($type)
    {
        if($type=="Array" || $type=="String")
            $type="_".$type;
        if(!isset($this->allowed_types[$type])) {

            $type = "/model/reflection/Types/types/" . $type;
        }

        $instance=\lib\model\types\TypeFactory::getType($this,$this->allowed_types[$type]);
        $instance->__setParent($this,$type);
        return $instance;
    }

    static function getAllTypeClasses()
    {
        if(BaseType::$typeCache!==null)
            return BaseType::$typeCache;

        $src=glob(PROJECTPATH."/model/reflection/objects/Types/types/*.php");
        $result=[];
        for($k=0;$k<count($src);$k++)
        {
            $cur=basename($src[$k]);
            //if($cur!=="BaseType.php")
           // {
                $p=explode(".",$cur);
                $curClass="/model/reflection/Types/types/".$p[0];
                $short=$p[0];
                if($short=="_Array")
                    $short="Array";
                if($short=="_String")
                    $short="String";
                $result[$short]=$curClass;
                //$result[$curClass]=$curClass;
            //}
        }
        // Se escanean los paquetes existentes, obteniendo los tipos que haya.
        \model\reflection\ReflectorFactory::iterateOnPackages(function($pkg) use (& $result){
                if($pkg->getName()=="reflection")
                    return;
                $pkg->iterateOnModelTree(function($model) use ($pkg,& $result){
                    $d=$model->getModelDescriptor();
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

                            $short=str_replace("/model/reflection/Types/types/","",$cName);
                            $result[$short]=$cName;
                        }
                    }
                });
        });
        return $result;
    }
}
