<?php
namespace model\reflection\Model;
include_once(PROJECTPATH."/model/reflection/objects/Model/ModelComponent.php");
include_once(PROJECTPATH."/model/reflection/objects/Model/objects/Type/TypeReflectionFactory.php");
class Field extends \model\reflection\Model\ModelComponent
{
    var $targetRelation="";
        function __construct($name,$parentModel,$definition)
        {
            parent::__construct($name,$parentModel,$definition);              
            $this->shortlabel=io($definition,"SHORTLABEL",$name);
            $this->label=io($definition,"LABEL",$name);
            $this->type=$this->createType($this->definition);
            if(isset($definition["TARGET_RELATION"]))
                $this->targetRelation=$definition["TARGET_RELATION"];
        }        
        function isDescriptive()
        {
            return io($this->definition,"SHORTLABEL",false);
        }
        function isLabel()
        {
            $res=io($this->definition,"LABEL",false);
            if($res===false)
                return false;
            return $res==true || $res=="true" || $res==1;
        }
        function isState()
        {
            return $this->definition["TYPE"]=="State";
        }
        function setStates($states)
        {
            $this->definition["VALUES"]=$states;
        }
        static function isFieldARelation($definition)
        {
            $type=\lib\model\types\TypeFactory::getType(null,$definition);
            return is_a($type,'\lib\model\types\Relationship');
        }
        function isRelation($definition=null)
        {
            if($definition==null)
                $definition=$this->definition;
            return Field::isFieldARelation($definition);

        }
        function isUnique()
        {
            return io($this->definition,"UNIQUE",false);
        }

        static function createField($name,$parentModel,$definition=null)
        {
           return new Field($name,$parentModel,$definition);
        }

		function getType()
		{
			return array($this->name=>\model\reflection\Model\Type\TypeReflectionFactory::getReflectionType($this->definition));
		}
        function getRawType()
        {
            $type=$this->getType();
            foreach($type as $key=>$value)
            {
                $res[$key]=\lib\model\types\TypeFactory::getType(null,$value->getDefinition());
            }
            return $res;
        }

        function isAlias()
        {
            return false;
        }
        function isSearchable()
        {
             if(!isset($this->definition["SEARCHABLE"]))
                 return false;
            return $this->definition["SEARCHABLE"];
        }

        function getTypeSerializer($serializerType)
        {
            $def=array($this->name=>\lib\model\types\TypeFactory::getSerializer($definition["TYPE"],$serializerType));
            return $def;
        }

        function getDefaultInputName()
        {
            $fullclass=get_class($this->type->getInstance());
            $parts=explode('\\',$fullclass);
            $className=$parts[count($parts)-1];
            return $className;
        }

        function getDefinition()
        {
            $rawT=$this->getRawType();
            $fieldNames=array_keys($rawT);
            $def=$rawT[$fieldNames[0]]->getDefinition();
            if($this->isRequired())
                $def["REQUIRED"]=true;            
            $def["LABEL"]=$this->label;
            $def["SHORTLABEL"]=$this->shortlabel;
            $def["DESCRIPTIVE"]=$this->isDescriptive()?"true":"false";
            $def["ISLABEL"]=$this->isLabel()?"true":"false";
            $targetRelation=$this->getTargetRelation();
            if($targetRelation!="")
                $def["TARGET_RELATION"]=$targetRelation;
            if(isset($this->definition["UNIQUE"]))
                $def["UNIQUE"]="true";
            return $def;
        }
        function getRawDefinition()
        {
            return $this->definition;
        }
        function getTargetRelation()
        {
            return $this->targetRelation;
        }

        
}
 
