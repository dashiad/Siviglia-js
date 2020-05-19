<?php 
namespace model\ads\Comscore\types;

include (__DIR__."/BaseType.php");

class MonthException extends \lib\model\types\BaseTypeException {
    const ERR_BAD_FORMAT = 1;
    const ERR_BAD_DATE   = 2;
}

class Month extends BaseType
{
    
    public const BASE_DATE   = "1999-12"; // este mes es 0, ejemplos: 2000-02=2, 2020-02=242
    public const DATE_FORMAT = "Y-m"; 
    
    function serialize($name, $type, $serializer, $model=null)
    {
        if($type->hasValue()) {
            return [$name=>$type->getValue()];
        } else {
            return [$name => "NULL"];
        }
    }    
    
    
    public function unserialize($name, $type, $value, $serializer, $model=null)
    {
        $model->{$name}=unserialize($value[$name]);
    }
   
    
    protected function monthToInt(String $month) : Int 
    {
        $origin = new \DateTime(self::BASE_DATE);
        //$value  = new \DateTime($month);
        $value  = \DateTime::createFromFormat(self::DATE_FORMAT, $month);

        if (!$value)
            throw new MonthException(MonthException::ERR_BAD_FORMAT);
        
        $diff = $origin->diff($value);
        if ($diff->invert) // TODO: posible valor máximo (confirmar si se pueden pedir datos de más de 15 meses)
            throw new MonthException(MonthException::ERR_BAD_DATE);
        return 12*$diff->y + $diff->m;
    }
    
    protected function intToMonth(Int $int) : String 
    {
        $date = new \DateTime(self::BASE_DATE);
        $date->modify("+$int months");
        
        return $date->format('Y-m');
    }
    public function _setValue($val, $validationMode = null)
    {
        $this->value = $this->monthToInt($val);
        $this->valueSet = true;
    }

    public function _validate($value)
    {
        $value = trim($value);
        $intValue = $this->monthToInt($value);
        
        // comprobar validez
        
        return true;
    }
    
    public function _getValue()
    {
        return $this->intToMonth($this->value);
    }

    public function _equals($value)
    {
        return $this->value===$this->monthToInt($value);
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
