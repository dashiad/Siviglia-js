<?php
namespace lib\reflection\model;
class ModelComponent
{
    var $parentModel;
    var $name;
    var $definition;
    var $required;
    function __construct($name,$parentModel,$definition)
    {
        $this->name=$name;
        $this->parentModel=$parentModel;
        $this->definition=$definition;
        $this->required=isset($definition["REQUIRED"])?$definition["REQUIRED"]:false;
    }
    function getName()
    {
        return $this->name;
    }
    function getLabel()
    {
        return isset($this->definition["LABEL"])?$this->definition["LABEL"]:$this->name;
    }

    function getShortLabel()
    {
            return $this->definition["SHORTLABEL"]?$this->definition["SHORTLABEL"]:$this->name;
    }
    function isRequired()
    {
        $notRequiredFlags=\lib\model\types\BaseType::TYPE_SET_ON_SAVE |
            \lib\model\types\BaseType::TYPE_REQUIRES_SAVE;

        $curType=\lib\model\types\TypeFactory::getType($this->parentModel,$this->definition);

        if($curType->flags & $notRequiredFlags && $curType->isEditable())
            return false;

        return $this->required;
    }

    function getFormInput($form,$name,$definition,$inputsDefinition=null)
    {
        $label=(isset($definition["LABEL"])?$definition["LABEL"]:$name);
        $help=(isset($definition["HELP"])?$definition["HELP"]:"**Insert Field Help**");
        $input=null;
        if(isset($inputsDefinition) && isset($inputsDefinition))
        {
            $input=$inputsDefinition["TYPE"];
        }
        if(!$input)
        {
            $input="/types/inputs/".$this->getDefaultInputName($definition);
        }     
        $errors=$this->getFormInputErrors($definition);
        
        return $this->getInputPattern($name,$label,$help,$definition["REQUIRED"],$input,$errors,$form);
    }
    function getFormInputErrors($definition)
    {        
        $type=\lib\model\types\TypeFactory::getType($this->parentModel,$definition);
        $errors=array();
        if($type)
        {
            $type=$type->getRelationshipType(); // So autoincrements return ints.        
            // Se obtienen las cadenas de errores por defecto para este campo.
            $errors=array();
            $this->fillTypeErrors($type,$definition,$errors);              
        }
        return $errors;
    }
    function getDefaultInputParams($form=null,$actDef=null)
    {
         return null;
    }

    function getInputPattern($name,$label,$help,$required,$inputType,$errors,$form)
    {
        if($help=="")$help="**Insert Field Help**";

        $inputStr="\n\n\t\t\t\t[*/FORMS/inputContainer({\"name\":\"".$name."\"})]\n\t\t\t\t\t[_LABEL]".$label."[#]\n";
        $inputStr.="\t\t\t\t\t[_HELP]".$help."[#]\n";
        if($required["REQUIRED"])
            $inputStr.="\t\t\t\t\t[_REQUIRED][#]\n";
        $inputStr.="\t\t\t\t\t[_INPUT]\n";

        $inputStr.="\t\t\t\t\t\t[*:".$inputType."({\"model\":\"\$currentModel\",\"name\":\"".$name."\",\"form\":\"\$form\"})][#]\n\t\t\t\t\t[#]\n";
        if(isset($errors))
        {        
            $inputStr.="\t\t\t\t\t[_ERRORS]\n";
            foreach($errors as $key2=>$value2)
            {
                $inputStr.="\t\t\t\t\t\t[_ERROR({\"type\":\"".$key2."\",\"code\":\"".$value2."\"})][@L]".$this->parentModel->objectName->getNormalizedName()."_".$form->action->getName()."_".$name."_".$key2."[#][#]\n";
            }   
            $inputStr.="\t\t\t\t\t[#]\n";
        }
        $inputStr.="\t\t\t\t[#]\n";
        return $inputStr;
    }
    


    function fillTypeErrors($type,$definition,& $errors)
    {                
        // Se obtienen las constantes de la clase base, BaseType, para filtrarlas.
        // Se deben filtrar ya que dichas constantes son para excepciones internas,
        // no relacionadas con los errores que pueden aparecer en un formulario.
        $baseClass=new \ReflectionClass('\lib\model\types\BaseTypeException');
        $baseExceptions=$baseClass->getConstants();
                
        if($definition["REQUIRED"])                        
            unset($baseExceptions["ERR_UNSET"]); // Queremos que se procese esta excepcion
                            
        $errors=array("INVALID"=>2);
        $typeList=array_values(class_parents($type));
        $nEls=array_unshift($typeList,get_class($type));
        $typeList=array_values(array_reverse($typeList));
        
        foreach($typeList as $key=>$value)
        {
            $parts=explode("\\",$value);
            $className=$parts[count($parts)-1];
            $exceptionClass=$value."Exception";
            if( !class_exists($exceptionClass) )
                continue;
            
            $reflectionClass=new \ReflectionClass($exceptionClass);
            $constants=$reflectionClass->getConstants();
            foreach($constants as $key2=>$value2)
            {
                
                if(array_key_exists($key2,$baseExceptions))
                        continue;
                // Se filtran las excepciones que existen en la clase base.                
                if( strpos($key2,"ERR_")===0 )
                {
                    $key2=substr($key2,4);
                }
                $errors[$key2]=$value2;
            }  
            
        }
        
                
    }

    function createType($definition)
    {
        if(!isset($this->definition["TYPE"]))
            return null;
        $type=$this->definition["TYPE"]."Type";

        $typeReflectionFile=LIBPATH."/reflection/model/types/$type".".php";
        $dtype=$type;

        if(!is_file($typeReflectionFile))
        {
            $dtype="BaseType";
            $typeReflectionFile=LIBPATH."/reflection/model/types/BaseType.php";
        }
        $className='\lib\reflection\model\types\\'.$dtype;

        include_once($typeReflectionFile);

        // Los "alias" siempre tienen un parentModel.Los "types", no necesariamente.
        // Por eso, los constructores de alias tienen $parentModel como primer parametro del constructor.

         $instance=new $className($definition);     
         $instance->setTypeName($type);
         return $instance;

    }

       function getDefinition()
     {
         return $this->definition;
     }
       function dumpArray($arr,$initialNestLevel=0)
       {
           return \lib\php\ArrayTools::dumpArray($arr,$initialNestLevel);     
       }


}
?>
