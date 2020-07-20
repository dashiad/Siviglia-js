<?php
namespace model\reflection\Storage\Mysql;
class MysqlOptionsDefinition
{
    function __construct($parentModel,$def)
    {
        // En esta clase, los aliases, indexes,etc, se regeneran cada vez que se
        // llama a getDefinition, para reflejar cambios realizados en el modelo.
        $this->definition=$def;
        $this->parentModel=$parentModel;
    }

    static function createDefault($parentModel)
    {
        $defaultDefinition=array(
        "ENGINE"=>"InnoDb",
        "CHARACTER SET"=>"utf8",
        "COLLATE"=>"utf8_general_ci",
        "TABLE_OPTIONS"=>array(
            "ROW_FORMAT"=>"FIXED"
            )
        );
        if(!is_a($parentModel,'\lib\model\BaseModel'))
            return new MysqlOptionsDefinition($parentModel,$defaultDefinition);
        $ownershipField=$parentModel->getOwnershipField();
        if($ownershipField)
        {
            $defaultDefinition["INDEXES"][]=array("FIELDS"=>array($ownershipField),"UNIQUE"=>"false");
        }
        $role=$parentModel->getRole();
        if($role=="MULTIPLE_RELATION")
        {
            // Se crean indices sobre todos los campos relacion.Si,ademas, las relaciones son unicas,se crea un indice unico.
            $def=$parentModel->getDefinition();
            $mulF=$def["MULTIPLE_RELATION"]["FIELDS"];

            foreach($mulF as $value)
                $defaultDefinition["INDEXES"][]=array("FIELDS"=>array($value),"UNIQUE"=>"false");

            if($def["MULTIPLE_RELATION"]["UNIQUE_RELATIONS"])
                $defaultDefinition["INDEXES"][]=array("FIELDS"=>$mulF,"UNIQUE"=>"true");
        }
        /*$aliases=$parentModel->getInvRelationships();
        foreach($aliases as $key=>$value)
        {
            $defaultDefinition["ALIASES"][$key]=array("LAZY_LOAD"=>"true");
        }*/
        return new MysqlOptionsDefinition($parentModel,$defaultDefinition);
    }
    function getDefinition()
    {
        $def=$this->definition;
        $modelFields=$this->parentModel->__getFields();
        // Se comprueba que todos los campos que existen en los INDEXES, existen en los campos del objeto.
        $indexes=array();
        if(isset($def["INDEXES"]))
        {
            foreach($def["INDEXES"] as $key=>$value)
            {
                // TODO: Hacer que esto funcione tambien para los tipos compuestos.
                if(!is_array($value["FIELDS"]))
                {
                    if(!$modelFields[$value["FIELDS"]])
                        continue;
                    $indexes[]=$value;
                }
                else
                {

                    $doExit=false;
                    for($k=0;$k<count($value["FIELDS"]) && !$doExit;$k++)
                    {
                        if(!$modelFields[$value["FIELDS"][$k]])
                            $doExit=true;
                    }
                    if(!$doExit)
                        $indexes[]=$value;
                }
            }
        }
        if(count($indexes)>0)
            $def["INDEXES"]=$indexes;

            $aliasDef=array();
        if(isset($def["ALIASES"]))
        {
            $aliases=$this->parentModel->aliases;
            foreach($def["ALIASES"] as $key=>$value)
            {
                if($aliases[$key])
                    $aliasDef[$key]=$value;
            }
            unset($def["ALIASES"]);
        }

        if(count(array_keys($aliasDef))>0)
            $def["ALIASES"]=$aliasDef;
        return $def;
    }
}
?>
