<?php
namespace model\reflection\Model;

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
            $type=\lib\model\types\TypeFactory::getType(null,$definition,null);
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
			return array($this->name=>\model\reflection\Model\TypeReflectionFactory::getReflectionType($this->definition));
		}
        function getRawType()
        {
            $type=$this->getType();
            foreach($type as $key=>$value)
            {
                $res[$key]=$value->typeInstance;
            }
            return $res;
        }

        function __isAlias()
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
            $curType=$rawT[$fieldNames[0]];

            $typeDef=$rawT[$fieldNames[0]]->getDefinition();
            if(isset($typedef["REQUIRED"]) && $this->isRequired())
                $curType->REQUIRED=true;
            if(isset($typedef["LABEL"]))
                $curType->LABEL=$this->label;
            if(isset($typedef["SHORTLABEL"]))
                $curType->SHORTLABEL=$this->shortlabel;
            if(isset($typedef["DESCRIPTIVE"]))
                $curType->DESCRIPTIVE=$this->isDescriptive()?"true":"false";
            if(isset($typedef["ISLABEL"]))
                $curType->ISLABEL=$this->isLabel()?"true":"false";
            if(isset($typedef["TARGET_RELATION"]) && $this->getTargetRelation())
                $curType->TARGET_RELATION=$this->getTargetRelation();
            if(isset($typedef["UNIQUE"]))
                $curType->UNIQUE=true;
            return $curType->getValue();
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

