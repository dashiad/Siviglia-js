<?php
namespace lib\reflection\model;
class ObjectDefinition
{
        var $layer;
        var $className;
        var $namespaceClassName;
		var $isPrivate;
        var $baseDir;
        var $definition;
        function __construct($name,$layer=null)
        {
            $name=str_replace("/",'\\',$name);
	        $name=str_replace("\\\\","\\",$name);
            $parts=explode('\\',($name[0]=='\\'?substr($name,1):$name));
            $nParts=count($parts);
            if($parts[0]=="model")
                array_shift($parts);
            $nParts--;
            // Un objeto puede vernir sin namespace.En ese caso, el primer caracter del nombre
            // no va a ser '\'.El nombre se convierte al namespace por defecto.
            if($layer==null)
                $layer=DEFAULT_NAMESPACE;

            global $APP_NAMESPACES;
            if(!in_array($parts[0],$APP_NAMESPACES))
            {
                array_unshift($parts,$layer);
                $nParts++;
            }

            // ->className y ->namespaceClassName coinciden para clases raices de un namespace.
            $this->layer=$parts[0];
            $this->namespaceClassName=$parts[1];
            $this->className=$parts[count($parts)-1];
            if(count($parts)==3)
            {
                $this->isPrivate=true;
            }
        }
    function getLayer()
    {
        return $this->layer;
    }
        function isPrivate()
        {
            return $this->isPrivate;
        }
        function getClassName()
        {
            return $this->className;
        }
        function getNormalizedName()
        {
            if($this->isPrivate) {
                return '\model\\'.$this->namespaceClassName.'\\'.$this->className;
            }
            else {
                return '\model\\'.$this->className;
            }
        }
        function getNamespaceModel()
        {
            return $this->namespaceClassName;
        }
        
        function getDestinationFile($extraPath=null)
        {
            if(!$this->isPrivate)
                return PROJECTPATH."/model/".$this->layer."/objects/".$this->namespaceClassName."/".($extraPath?$extraPath:"");

            return PROJECTPATH."/model/".$this->layer."/objects/".$this->namespaceClassName."/objects/".$this->className."/".($extraPath?$extraPath:"");

         }

        function getParentNamespace($tree=null)
        {			
            if(!$this->isPrivate)
                return '\model\\'.$this->layer;
            else
                return '\model\\'.$this->layer.'\\'.$this->namespaceClassName;
        }
        function getNamespaced($tree=null)
        {			
            return $this->getParentNamespace().'\\'.$this->className;
        }
        function getUnderNamespaced()
        {
            if(!$this->isPrivate)
                return $this->className;
            else
                return $this->namespaceClassName.'_'.$this->className;
        }

        function getNamespace()
        {
            return $this->getNamespaced();
        }
        function getPath($extra)
        {
            return $this->getDestinationFile($extra);
        }
        function __toString()
        {
            if(!$this->isPrivate)
                return $this->className;
            else
                return $this->namespaceClassName.'\\'.$this->className;

        }
        function getDefinition()
        {
            if($this->definition!=null)
                return $this->definition;
            require_once($this->getPath("Definition.php"));
            $defClass=$this->getNamespaced().'\\Definition';
            $inst=new $defClass();
            $this->definition=$inst::$definition;
            return $this->definition;
        }
        function getDefaultSerializer()
        {
            $defArr=$this->getDefinition();
            if(isset($defArr["DEFAULT_SERIALIZER"]))
                return $defArr["DEFAULT_SERIALIZER"];
            return null;
        }
        function includeModel()
        {
            include_once($this->getPath($this->className.".php"));
        }
        // Paths de acciones.
        function getActionFileName($actionName)
        {
            return $this->getPath("/actions/".$actionName.".php");
        }
        function getNamespacedAction($actionName)
        {
            return $this->getNamespaced().'\\actions\\'.$actionName;
        }
        function getDataSourceFileName($actionName)
        {
            return $this->getPath("/datasources/".$actionName.".php");
        }
        function getNamespacedActionException($actionName)
        {
            return $this->getNamespacedAction($actionName)."Exception";
        }
        function getFormFileName($formName)
        {
            return $this->getPath("/html/forms/".$formName.".php");
        }
        function getNamespacedForm($formName)
        {
            return $this->getNamespaced()."\\html\\forms\\".$formName;
        }
        // Retorna true si el $target (una cadena, o un objeto) apunta al mismo objeto
        // que nosotros.
        function equals($target)
        {
            if(is_string($target))
            {
                $target=new ObjectDefinition($target);
            }
            
            return $target->getNamespaced()==$this->getNamespaced();
        }
        function sameNamespace($target)
        {
            if(is_string($target))
            {
                $target=new ObjectDefinition($target);
            }
            if(!$target->isPrivate() && !$this->isPrivate())
                return true;
            if($target->isPrivate() && $this->isPrivate())
                return $this->getNamespaceModel()==$target->getNamespaceModel();
            if($target->isPrivate())
                return $target->getNamespaceModel()==$this->className;
            return $this->getNamespaceModel()==$target->className;
            
        }
}

?>
