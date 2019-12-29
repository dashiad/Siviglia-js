<?php
namespace lib\model\types;
use phpDocumentor\Reflection\Type;

class TypeSwitcherException extends \lib\model\types\BaseTypeException
{
    const ERR_MISSING_TYPE_FIELD=101;
    const ERR_INVALID_TYPE=102;
    const ERR_MISSING_CONTENT_FIELD=103;
    const TXT_MISSING_TYPE_FIELD="Type field missing";
    const TXT_INVALID_TYPE="Type [%type%] is not allowed";
    const TXT_MISSING_CONTENT_FIELD="Content field [%field%] is missing";
}
class TypeSwitcher extends BaseContainer
{

    var $currentType;
    var $allowed_types;
    var $type_field;
    var $implicit_type;
    var $content_field;

    function __construct($def,$neutralValue=null)
    {
        parent::__construct($def,null);

        $this->currentType=null;
        $this->allowed_types=$def["ALLOWED_TYPES"];
        $this->type_field=$def["TYPE_FIELD"];
        $this->implicit_type=isset($def["IMPLICIT_TYPE"])?$def["IMPLICIT_TYPE"]:null;
        $this->content_field=isset($def["CONTENT_FIELD"])?$def["CONTENT_FIELD"]:null;
        if(isset($this->definition["DEFAULT"]))
        {
            $this->setValue($this->definition["DEFAULT"]);
        }
    }

    function _setValue($val)
    {
        if(isset($val[$this->type_field]))
            $type=$val[$this->type_field];
        else
        {
            if($this->implicit_type===null)
                throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
            $type=$this->implicit_type;
        }
        if(!$this->isAllowedType($type))
            throw new TypeSwitcherException(TypeSwitcherException::ERR_INVALID_TYPE,["type"=>$type]);

        // Ahora hay que resolver si hay una subkey para el contenido, o no.
        // Si no lo hay, el propio tipo tiene que tener el campo "type_field".
        // Es decir, un caso tipico son las clases derivadas de BaseType. Se editan
        // con un typeswitcher, pero el typeswitcher es transparente.El no tiene ningun valor.
        // Por lo tanto, los diferentes tipos son los que tienen que tener el campo "TYPE".
        // El typeswitcher tiene un valor cuando si que hay un "content_field", es el caso de
        // estructuras del tipo: ["type"=>"..", "content"=>"...."]
        $instance=$this->getTypeInstance($type);
        if($this->content_field==null)
            $instance->setValue($val);
        else
            $instance->setValue($val[$this->content_field]);
        $this->currentType=$type;
        if($instance->hasOwnValue()) {
            $this->valueSet = true;
            $this->value=$instance;
        }
        else
            $this->valueSet=false;
    }

    function _validate($val)
    {
        if(isset($val[$this->type_field]))
            $type=$val[$this->type_field];
        else
        {
            if($this->implicit_type===null)
                throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
            $type=$this->implicit_type;
        }
        if(!$this->isAllowedType($type))
            throw new TypeSwitcherException(TypeSwitcherException::ERR_INVALID_TYPE,["type"=>$type]);

        // Ahora hay que resolver si hay una subkey para el contenido, o no.
        // Si no lo hay, el propio tipo tiene que tener el campo "type_field".
        // Es decir, un caso tipico son las clases derivadas de BaseType. Se editan
        // con un typeswitcher, pero el typeswitcher es transparente.El no tiene ningun valor.
        // Por lo tanto, los diferentes tipos son los que tienen que tener el campo "TYPE".
        // El typeswitcher tiene un valor cuando si que hay un "content_field", es el caso de
        // estructuras del tipo: ["type"=>"..", "content"=>"...."]
        $typeInstance=$this->getTypeInstance($type);
        if($this->content_field!==null)
        {
            if(!isset($val[$this->content_field]))
                throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_CONTENT_FIELD,["field"=>$this->content_field]);
            return $typeInstance->validate($val[$this->content_field]);
        }
        return $typeInstance->validate($val);
    }

    function _getValue()
    {
        if($this->currentType==null)
                return null;
        if($this->content_field==null)
            return $this->value->getValue();
        $result=[];
        $result[$this->type_field]=$this->currentType;
        $result[$this->content_field]=$this->value->getValue();
        return $result;
    }

    function hasValue()
    {
        return $this->valueSet;
    }
    function hasOwnValue()
    {
        return $this->valueSet;
    }
    function _equals($value)
    {
        if($value[$this->type_field]!==$this->currentType)
            return false;
        if($this->content_field==null)
            return $this->value->equals($value);
        else
            return $this->value->equals($value[$this->content_field]);

    }


    function is_set()
    {
        return $this->valueSet;
    }

    function clear()
    {
        $this->valueSet=true;
        $this->value=null;

        $this->currentType=null;
    }

    function __toString()
    {
        return json_encode($this->getValue());
    }


    function isEmpty()
    {
        return $this->valueSet==false;
    }
    function isTypeReference()
    {
        return false;
    }
    function __set($fieldName,$value)
    {
        if($fieldName==$this->type_field)
        {
            if(!$this->isAllowedType($value))
                throw new TypeSwitcherException(TypeSwitcherException::ERR_INVALID_TYPE,["type"=>$value]);
            if($value==$this->currentType)
                return;
            $this->currentType=$value;
            $this->value=$this->getTypeInstance($value);
            $this->valueSet=true;
        }
        else
        {
            if($this->currentType===null)
            {
                if($this->implicit_type===null)
                    throw new TypeSwitcherException(TypeSwitcherException::ERR_INVALID_TYPE,["type"=>"implicit"]);
                $this->currentType=$this->implicit_type;
                $this->value=$this->getTypeInstance($this->currentType);
                $this->valueSet=true;
            }

            if($this->content_field==$fieldName)
            {
                $this->value->setValue($value);
                $this->valueSet=$this->value->hasOwnValue();
                return;
            }

            if(is_a($this->value,'\lib\model\BaseContainer')) {
                $this->value->{$fieldName} = $value;
                $this->valueSet=$this->value->hasOwnValue();
            }
        }

    }
    function __get($fieldName)
    {
        $returnType=false;
        $original=$fieldName;
        if($fieldName[0]=="*") {
            $fieldName = substr($fieldName, 1);
            $returnType = true;
        }
        if($fieldName==$this->type_field)
            return $this->currentType;

        if($this->value==null)
            return null;
        if($fieldName===$this->content_field)
        {
            if($returnType==true)
                return $this->value;
            else
                return $this->value->getValue();
        }
        else
        {
            return $this->value->{$original};
        }
    }
    function isAllowedType($type)
    {
        return isset($this->allowed_types[$type]);
    }
    function getTypeInstance($type)
    {
        $def=$this->allowed_types[$type];
        $instance=\lib\model\types\TypeFactory::getType($this,$def);
        $instance->setParent($this);
        return $instance;
    }
    function _copy($ins)
    {
        $this->__rawSet($ins->getValue());
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/TypeSwitcher.php");
        return '\model\reflection\Types\meta\TypeSwitcher';
    }
}
