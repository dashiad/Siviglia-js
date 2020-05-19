<?php 
namespace model\ads\Comscore\types;

include (__DIR__."/BaseType.php");

class Boolean extends BaseType
{
    
    function serialize($name, $type, $serializer, $model=null)
    {
        if($type->hasValue()) {
            return [$name=>$type->getValue()?true:false];
        } else {
            return [$name => "NULL"];
        }
    }    
    
    public function unserialize($name, $type, $value, $serializer, $model=null)
    {
        $model->{$name}=unserialize($value[$name]);
    }
    
    public function _setValue($val, $validationMode = null)
    {
        $this->value = $val;
        $this->valueSet = true;
    }

    public function _validate($value)
    {
        return is_bool($value);
    }
    
    public function _getValue()
    {
        return $this->value===true;
    }

    public function _equals($value)
    {
        return $this->value===$value;
    }

    public function getMetaClassName()
    {
        return self::class;
    }

    public function _copy($val)
    {
        $this->value = $val->getValue();
        $this->valueSet = true;
    }

}
