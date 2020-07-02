<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 31/12/2017
 * Time: 22:17
 */

namespace lib\model;

class BaseModelDefinitionException extends \lib\model\BaseException
{
    const ERR_NO_SUCH_FIELD=1;
    const ERR_NO_SUCH_ALIAS=2;
    const ERR_NO_PATH=3;
    const ERR_NO_SUCH_DEFINITION=4;
    const TXT_NO_SUCH_FIELD="Field doesnt exist: [%field%]";
    const TXT_NO_SUCH_ALIAS="Alias doesnt exist: [%alias%]";
    const TXT_NO_PATH="Cant traverse the field [%field%] as it is not a relation";
    const TXT_NO_SUCH_DEFINITION="Trying to access unset definition field [%field%]";
}

class BaseModelDefinition
{
    static $definition;
    var $actDef;
    function __construct($def=null)
    {
        $this->actDef=($def==null)?static::$definition:$def;
    }
    function getDefinition()
    {
        return $this->actDef;
    }
    function getTableName()
    {
        $def=$this->getDefinition();
        if(isset($def["TABLE"]))
            return $def["TABLE"];
        return get_class($this);
    }
    static function loadDefinition($model)
    {
        $objName = \lib\model\ModelService::getModelDescriptor('\\'.get_class($model));
        $defname = $objName->getNamespaced().'\Definition';
        include_once($objName->getDestinationFile()."/Definition.php");
        // Se hace new() por si la definicion requiere inicializacion de constantes.
        return new $defname();
    }
    static function fromArray($definition)
    {
        return new BaseModelDefinition($definition);
    }

    function getFieldDefinition($field)
    {
        $def=$this->getDefinition();
        if(!isset($def["FIELDS"][$field]))
            throw new BaseModelDefinitionException(BaseModelDefinitionException::ERR_NO_SUCH_FIELD,array("field"=>$field));
        return $def["FIELDS"][$field];
    }

    function getAliasDefinition($alias)
    {
        $def=$this->getDefinition();
        if(!isset($def["ALIASES"][$alias]))
            throw new BaseModelDefinitionException(BaseModelDefinitionException::ERR_NO_SUCH_ALIAS,array("alias"=>$alias));
        return $def["FIELDS"][$alias];
    }

    function getFieldOrAliasDefinition($field)
    {
        try{
            return $this->getFieldDefinition($field);
        }catch(BaseModelDefinitionException $e)
        {
            return $this->getAliasDefinition($field);
        }
    }
    function getFieldPath($path)
    {
        if(!$path)
            return null;

        if(!is_array($path))
        {
            $path=explode("/",$path);
            if($path[0]=="")
                array_shift($path);
        }
        $def=$this->getFieldOrAliasDefinition($path[0]);
        $field=ModelField::getModelField("none",null,$def);
        $a=array(array("TABLE"=>$this->getTableName(),"FIELD"=>$path[0]));
        if($field->__isRelation())
        {
            $remote=$field->getRemoteObject();
            $remoteDef=BaseModelDefinition::loadDefinition($remote);
            $curField=array_shift($path);
            $next=$remoteDef->getFieldPath($path);
            if(is_array($next))
                return array_merge($a,$next);
            return $a;
        }
        else
        {
            if(count($path)>1)
            {
                // aun queda path, y el campo no era una relacion? Excepcion
                throw new BaseModelDefinitionException(BaseModelDefinitionException::ERR_NO_PATH,array("field"=>$path[0]));
            }
            return array("TABLE"=>$this->getTableName(),"FIELD"=>$path[0]);
        }
    }
    function getOwnerPath()
    {
        $def=$this->getDefinition();
        if(!isset($def["OWNERPATH"]))
            throw new BaseModelDefinitionException(BaseModelDefinitionException::ERR_NO_SUCH_DEFINITION,array("field"=>"OWNERPATH"));
        return $def["OWNERPATH"];
    }

}
