<?php
namespace lib\model;

class ModelDescriptor
{
    var $layer;
    var $className;
    var $namespaceClassName;
    var $isPrivate;
    var $baseDir;
    var $definition;
    var $package;
    function __construct($name, $layer = null, $package)
    {
        $this->package=$package;
        $baseDir=$package->getBasePath();
        $name = str_replace("/", '\\', $name);
        $name = str_replace("\\\\", "\\", $name);
        $this->baseDir = $baseDir;
        $parts = explode('\\', ($name[0] == '\\' ? substr($name, 1) : $name));


        if ($parts[0] == "model") {
            $layer = $parts[1];
            array_shift($parts);
            array_shift($parts);
        } else {
            $layer = $parts[0];
            array_unshift($parts, "model");
        }

        // Un objeto puede vernir sin namespace.En ese caso, el primer caracter del nombre
        // no va a ser '\'.El nombre se convierte al namespace por defecto.
        if ($layer == null)
            $layer = DEFAULT_NAMESPACE;

        // ->className y ->namespaceClassName coinciden para clases raices de un namespace.
        $this->layer = $layer;
        $this->className = $parts[count($parts) - 1];
        array_pop($parts);
        $nParts = count($parts);
        if ($nParts == 0)
            $this->namespaceClassName = $this->className;
        else {
            $this->namespaceClassName = implode('\\', $parts);
            $this->isPrivate = true;
        }

    }
    function getBaseDir()
    {
        return $this->baseDir;
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
        if ($this->isPrivate) {
            return '\model\\' . $this->namespaceClassName . '\\' . $this->className;
        } else {
            return '\model\\' . $this->className;
        }
    }

    function getNamespaceModel()
    {
        return $this->namespaceClassName;
    }

    function getDestinationFile($extraPath = null)
    {
        if (!$this->isPrivate)
            return $this->baseDir . "/model/" . $this->layer . "/objects/" . $this->className . "/" . ($extraPath ? $extraPath : "");

        return $this->baseDir . "/model/" . $this->layer . "/objects/" . str_replace('\\', '/objects/', $this->namespaceClassName) . "/objects/" . $this->className . "/" . ($extraPath ? $extraPath : "");

    }

    function getParentNamespace($tree = null)
    {
        if (!$this->isPrivate)
            return '\model\\' . $this->layer;
        else
            return '\model\\' . $this->layer . '\\' . $this->namespaceClassName;
    }

    function getNamespaced($tree = null)
    {
        return $this->getParentNamespace() . '\\' . $this->className;
    }

    function getUnderNamespaced()
    {
        if (!$this->isPrivate)
            return $this->className;
        else
            return str_replace('\\', '_', $this->namespaceClassName) . '_' . $this->className;
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
        if (!$this->isPrivate)
            return $this->className;
        else
            return $this->namespaceClassName . '\\' . $this->className;

    }

    function getDefinition()
    {
        if ($this->definition != null)
            return $this->definition;
        $file = $this->getPath("Definition.php");
        if (!is_file($file))
            return null;
        include_once($file);
        $defClass = $this->getNamespaced() . '\\Definition';
        $inst = new $defClass();
        $this->definition = $inst->getDefinition();
        return $this->definition;
    }

    function getDefaultSerializer()
    {
        $defArr = $this->getDefinition();
        if (isset($defArr["DEFAULT_SERIALIZER"]))
            return $defArr["DEFAULT_SERIALIZER"];
        return null;
    }
    function getInstance($serializer=null)
    {
        $cName=$this->getNamespaced();
        return new $cName($serializer);
    }
    function includeModel()
    {
        include_once($this->getPath($this->className . ".php"));
    }

    // Paths de acciones.
    function getActionFileName($actionName)
    {
        return $this->getPath("/actions/" . $actionName . ".php");
    }

    function getNamespacedAction($actionName)
    {
        return $this->getNamespaced() . '\\actions\\' . $actionName;
    }

    function getDataSourceFileName($actionName)
    {
        return $this->getPath("/datasources/" . $actionName . ".php");
    }
    function getNamespacedDatasource($datasourceName)
    {
        return $this->getNamespaced() . '\\datasources\\' . $datasourceName;
    }

    function getNamespacedActionException($actionName)
    {
        return $this->getNamespacedAction($actionName) . "Exception";
    }

    function getFormFileName($formName)
    {
        return $this->getPath("/html/forms/" . $formName . ".php");
    }

    function getForms()
    {
        $startPath = $this->getPath("/html/forms");
        try {
            $files = \lib\php\FileTools::getFilesInDirectory($startPath, false, array("php"), $relative = true);
        } catch (\lib\php\FileToolsException $e) {
            if ($e->getCode() == \lib\php\FileToolsException::ERR_INVALID_DIRECTORY)
                return array();
            throw $e;
        }
        return $files;
    }

    function getFormWidgets()
    {
        $startPath = $this->getPath("/html/forms");
        try {
            $files = \lib\php\FileTools::getFilesInDirectory($startPath, false, array("wid"), $relative = true);
        } catch (\lib\php\FileToolsException $e) {
            if ($e->getCode() == \lib\php\FileToolsException::ERR_INVALID_DIRECTORY)
                return array();
            throw $e;
        }
        return $files;
    }

    function getViews()
    {
        $startPath = $this->getPath("/html/views");
        try {
            $files = \lib\php\FileTools::getFilesInDirectory($startPath, false, ".wid", $relative = true);
        } catch (\lib\php\FileToolsException $e) {
            if ($e->getCode() == \lib\php\FileToolsException::ERR_INVALID_DIRECTORY)
                return array();
            throw $e;
        }
        return $files;
    }

    function getNamespacedForm($formName)
    {
        return $this->getNamespaced() . "\\html\\forms\\" . $formName;
    }
    function getSerializer($serPath,$parameters)
    {
        include_once($this->getDestinationFile("/serializers/".$serPath.".php"));
        if($serPath[0]!='/')
            $serPath='/'.$serPath;
        $cName=$this->getNamespaced().'\serializers'.str_replace('/','\\',$serPath);
        return new $cName($parameters);
    }
    // Retorna true si el $target (una cadena, o un objeto) apunta al mismo objeto
    // que nosotros.
    function equals($target)
    {
        if (is_string($target)) {
            $target=\lib\model\ModelService::getModelDescriptor($target);
        }

        return $target->getNamespaced() == $this->getNamespaced();
    }

    function sameNamespace($target)
    {
        if (is_string($target)) {
            $target = new ModelName($target);
        }
        if (!$target->isPrivate() && !$this->isPrivate())
            return true;
        if ($target->isPrivate() && $this->isPrivate())
            return $this->getNamespaceModel() == $target->getNamespaceModel();
        if ($target->isPrivate())
            return $target->getNamespaceModel() == $this->className;
        return $this->getNamespaceModel() == $target->className;

    }

}

?>
