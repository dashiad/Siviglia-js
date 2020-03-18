<?php
namespace model\reflection\Model;
class Type
{
        function __construct($definition)
        {

            if(is_object($definition))
            {
                debug_trace();
                exit();
            }
             $this->definition=$definition;
        }
        function setTypeName($typeName)
        {
            $this->typeName=$typeName;
        }
        function getDefinition()
        {
            if($this->definition)
            {
                $def=$this->definition;
                if(!$def["TYPE"])
                    $def["TYPE"]=$this->typeName;
            }
            else
            {
                $def=array("TYPE"=>$this->typeName);
            }
            return $def;
        }
        function getInstance()
        {
            return \lib\model\types\TypeFactory::getType(null,$this->definition);
        }
        function setName($name)
        {
            $this->definition["NAME"]=$name;
        }
        function isEditable()
        {
            return true;
        }
}
