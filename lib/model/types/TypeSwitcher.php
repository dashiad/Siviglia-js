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
class TypeSwitcher extends BaseContainer implements \ArrayAccess
{

    var $currentType;
    var $allowed_types;
    var $type_field;
    var $implicit_type;
    var $content_field;
    var $subNode;

    function __construct($def,$value=null)
    {


        $this->currentType=null;
        $this->allowed_types=$def["ALLOWED_TYPES"];
        $this->implicit_type=isset($def["IMPLICIT_TYPE"])?$def["IMPLICIT_TYPE"]:null;
        $this->content_field=isset($def["CONTENT_FIELD"])?$def["CONTENT_FIELD"]:null;
        parent::__construct($def,$value);
    }

    function _setValue($val)
    {
        // Ahora hay que resolver si hay una subkey para el contenido, o no.
        // Si no lo hay, el propio tipo tiene que tener el campo "type_field".
        // Es decir, un caso tipico son las clases derivadas de BaseType. Se editan
        // con un typeswitcher, pero el typeswitcher es transparente.El no tiene ningun valor.
        // Por lo tanto, los diferentes tipos son los que tienen que tener el campo "TYPE".
        // El typeswitcher tiene un valor cuando si que hay un "content_field", es el caso de
        // estructuras del tipo: ["type"=>"..", "content"=>"...."]
        $typeInfo=$this->getTypeFromValue($val);
        $instance=\lib\model\types\TypeFactory::getType(null,$typeInfo["def"],$val);
        $this->currentType=$typeInfo["name"];
        if($instance->hasOwnValue()) {
            $this->valueSet = true;
            $this->value=$instance;
            $this->subNode=$instance;
        }
        else {
            $this->valueSet = false;
            $this->subNode=null;
        }
    }
    function getTypeFromValue($val)
    {
        $byType=io($this->definition,"TYPE_FIELD",null);
        if($byType) {
            $typeField = $byType;
            $curType=null;
            if ($typeField != null && isset($val[$typeField])) {
                $curType=$val[$typeField];
            }
            else {
                $curType = io($this->definition, "IMPLICIT_TYPE", null);
                if (!$curType)
                    throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
            }
            $t=$this->definition["ALLOWED_TYPES"][$curType];
            if(!isset($this->definition["CONTENT_FIELD"]))
            {
                return ["name"=>$curType,"def"=>$t];
            }
            else
                {
                    $baseDef=["TYPE"=>"Container","FIELDS"=>[]];
                    $baseDef["FIELDS"][$this->definition["TYPE_FIELD"]]=["TYPE"=>"String"];
                    $baseDef["FIELDS"][$this->definition["CONTENT_FIELD"]]=$t;
                    return ["name"=>$curType,"def"=>$baseDef];
                }
        }
        $byType=io($this->definition,"ON",null);
        if($byType)
        {
            for($k=0;$k<count($this->definition["ON"]);$k++)
            {
                $cur=$this->definition["ON"][$k];
                $f=io($cur,"FIELD",null);
                $op=$cur["IS"];
                $then=$cur["THEN"];
                $cnd=false;
                $v=null;
                if($f===null) {

                    $cnd=true;
                    $v = $val;
                }
                else {
                    $cnd = isset($val[$f]) ? true : false;
                    if($cnd)
                        $v=$val[$f];
                }
                $arrayCheck=null;
                switch($op)
                {
                    case "String":{
                        if(!$cnd)
                            break;
                        if(is_string($v))
                            return ["name"=>$then,"def"=>$this->definition["ALLOWED_TYPES"][$then]];
                    }break;
                    case "Array":{
                        if(!$cnd)
                            break;
                        if($arrayCheck===null)
                            $arrayCheck=\lib\php\ArrayTools::isAssociative($v);
                        if(is_array($v) && !$arrayCheck)
                            return ["name"=>$then,"def"=>$this->definition["ALLOWED_TYPES"][$then]];
                    }break;
                    case "Object":{
                        if(!$cnd)
                            break;
                        if($arrayCheck===null)
                            $arrayCheck=\lib\php\ArrayTools::isAssociative($v);
                        if(is_array($v) && $arrayCheck)
                            return ["name"=>$then,"def"=>$this->definition["ALLOWED_TYPES"][$then]];
                    }break;
                    case "Present":{
                        if(!$cnd)
                            break;
                        return ["name"=>$then,"def"=>$this->definition["ALLOWED_TYPES"][$then]];
                    }break;
                    case "Not Present":{
                        if(!$cnd)
                            return ["name"=>$then,"def"=>$this->definition["ALLOWED_TYPES"][$then]];
                    }break;
                }
            }
            if ($this->definition["IMPLICIT_TYPE"])
                return ["name"=>$this->definition["IMPLICIT_TYPE"],
                    "def"=>$this->definition["ALLOWED_TYPES"][$this->definition["IMPLICIT_TYPE"]]];
        }
        throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
    }

    function _validate($val)
    {
        $typeInfo=$this->getTypeFromValue($val);
        $instance=\lib\model\types\TypeFactory::getType(null,$typeInfo["def"],$val);
        return $instance->validate($val);
    }

    function _getValue()
    {
        if($this->subNode==null)
                return null;
        return $this->subNode->getValue();
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
        return $this->subNode->equals($value);
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
        $this->subNode->null;
    }

    function __toString()
    {
        if($this->subNode===null)
            return "null";
        return json_encode($this->subNode->getValue());
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
        $mustCheck=false;
        $typeField=io($this->definition,"TYPE_FIELD",null);

        if($typeField && $fieldName===$typeField)
            $mustCheck=true;
        else {
            for($k=0;$k<count($this->definition["ON"]);$k++) {
                if($this->definition["ON"][$k]["FIELD"]==$fieldName)
                    $mustCheck=true;
            }
        }
        if($mustCheck==true) {
            $val=[$fieldName=>$value];
            $typeInfo=$this->getTypeFromValue($val);
            if($typeInfo["name"]!==$this->currentType)
                $this->setValue($val);
        }
        else
        {
            if($this->subNode===null)
                throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
            $this->subNode->{$fieldName}=$value;
        }

    }
    function __get($fieldName)
    {
        if($this->subNode===null)
            throw new TypeSwitcherException(TypeSwitcherException::ERR_MISSING_TYPE_FIELD);
        return $this->subNode->$fieldName;
    }
    function isAllowedType($type)
    {
        return isset($this->allowed_types[$type]);
    }
    function getTypeInstance($type)
    {
        $def=$this->allowed_types[$type];
        $instance=\lib\model\types\TypeFactory::getType($this,$def);
        $instance->setParent($this,$type);
        return $instance;
    }
    function _copy($ins)
    {
        $this->__rawSet($ins->getValue());
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/TypeSwitcher.php");
        return '\model\reflection\Types\meta\TypeSwitcher';
    }

    function getTypeFromPath($path)
    {
        if(!is_array($path))
        {
            $path=explode("/",$path);
            if($path[0]=="")
                array_shift($path);
        }
        if(count($path)==0)
            return $this;
        // Consumimos un field, que deberia ser el tipo del typeswitcher que queremos navegar (no tiene por que ser el del
        // valor actual del typeswitcher, que, por otro lado, posiblemente no se le ha asignado ningun valor, ya que solo
        // estamos interesados en el tipo de dato)
        $field=array_shift($path);
        $type = $this->getTypeInstance($field);
        return $type->getTypeFromPath($path);
    }
    public function offsetExists ( $offset ){
        return $this->subNode && is_a($this->subNode,'\ArrayAccess') && $this->subNode->offsetExists($offset);
    }
    public function offsetGet ( $offset )
    {
        if(!$this->subNode || !is_a($this->subNode,'\ArrayAccess'))
            return null;
        return $this->subNode[$offset];
    }
    public function offsetSet ( $offset , $value )
    {
        if(!$this->subNode || !is_a($this->subNode,'\ArrayAccess'))
            return null;
        return $this->subNode[$offset]=$value;
    }
    public function offsetUnset ( $offset ) {}
}
