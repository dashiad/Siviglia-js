<?php


namespace model\reflection\types;


class BaseReflectedType extends \lib\model\types\Container
{
    function __construct($typeLabel,$name,$def,$parentType=null,$value=null,$validationMode=null)
    {
        $baseDef=[
            "LABEL"=>"$typeLabel",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "LABEL"=>["LABEL"=>"Label","TYPE"=>"String"],
                "SHORTLABEL"=>["LABEL"=>"Label corta","TYPE"=>"String"],
                "DESCRIPTIVE"=>["LABEL"=>"Campo descriptivo","TYPE"=>"Boolean","HELP"=>"Utilizar este campo para describir (en links, selectores,etc) a las instancias de este modelo."],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta(),
                "REQUIRED"=>["LABEL"=>"Requerido","TYPE"=>"Boolean","DEFAULT"=>false]
            ]
        ];
        foreach($def as $key=>$value)
            $baseDef["FIELDS"][$key]=$value;
        parent::__construct($name, $baseDef,$parentType,$value,$validationMode);
    }
}