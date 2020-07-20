<?php
/**
 * Class ModelProvider
 * @package lib\model\types\libs
 *  (c) Smartclip
 */


namespace lib\model;
use lib\model\permissions\AclException;

class PackageException extends \lib\model\BaseException
{
    const ERR_UNKNOWN_PACKAGE=100;
    const ERR_UNKNOWN_CLASS=101;
    const ERR_UNKNOWN_RESOURCE_TYPE=102;
    const TXT_UNKNOWN_PACKAGE="Paquete desconocido:[%package%]";
    const TXT_UNKNOWN_CLASS="Clases desconocida:[%class%]";
    const TXT_UNKNOWN_RESOURCE_TYPE="Tipo de recurso desconocido:[%type%]";
}

class Package
{
    var $name;
    var $basePath;
    var $baseNamespace;
    var $fullPath;
    var $configInstance;
    const MODEL             ="Model";
    const ACTION            ="Action";
    const DATASOURCE        ="Datasource";
    const TYPE              ="Type";
    const TYPE_METADATA     ="TypeMetadata";
    const DEFINITION        ="Definition";
    const CONFIG            ="Config";
    const WIDGET            ="Widget";
    const WORKER            ="Worker";
    const HTML_FORM          ="HtmlForm";
    const HTML_FORM_TEMPLATE ="HtmlFormTemplate";
    const HTML_VIEW          ="HtmlView";
    const JS_TYPE           ="JsType";
    const JS_MODEL           ="JsModel";
    const JS_FORM           ="JsForm";
    const JS_VIEW           ="JsView";

    static $resourceMetadata=[
        Package::MODEL=>["plural"=>"Models","label"=>"Model"],
        Package::ACTION=>["plural"=>"Actions","label"=>"Action"],
        Package::DATASOURCE=>["plural"=>"Models","label"=>"Model"],
        Package::TYPE=>["plural"=>"Types","label"=>"Type"],
        Package::TYPE_METADATA=>["plural"=>"Types Metadata","label"=>"Type Metadata"],
        Package::DEFINITION=>["plural"=>"Definitions","label"=>"Definition"],
        Package::CONFIG=>["plural"=>"Configs","label"=>"Config"],
        Package::WIDGET=>["plural"=>"Widgets","label"=>"Widget"],
        Package::WORKER=>["plural"=>"Workers","label"=>"Worker"],
        Package::HTML_FORM=>["plural"=>"Html Forms","label"=>"Html Form"],
        Package::HTML_FORM_TEMPLATE=>["plural"=>"Html Form Templates","label"=>"Html Form Template"],
        Package::HTML_VIEW=>["plural"=>"Html Views","label"=>"Html View"],
        Package::JS_TYPE=>["plural"=>"Js Types","label"=>"Js Type"],
        Package::JS_MODEL=>["plural"=>"Js Model","label"=>"Js Model"],
        Package::JS_FORM=>["plural"=>"Js Forms","label"=>"Js Form"],
        Package::JS_VIEW=>["plural"=>"Js Views","label"=>"Js View"]
    ];

    static $packages=array();
    function __construct($baseNamespace, $basePath,$name=null)
    {
        $parts=explode('\\',$baseNamespace);
        $k=0;
        while($parts[$k]=="" || $parts[$k]=="model")$k++;
        if($name!==null)
            $this->name=$name;
        else
            $this->name=$parts[$k];
        Package::$packages[$this->name]=$this;
        $len=strlen($basePath);
        if($len > 0) {
            if ($basePath[$len - 1] !== '/') {
                if ($basePath[$len - 1] == '\\')
                    $basePath[$len - 1] = "/";
                else
                    $basePath .= "/";
            }
        }
        $this->basePath = PROJECTPATH.$basePath;
        $this->baseNamespace = rtrim($baseNamespace,'\\');
        $this->fullPath=$this->basePath.str_replace('\\',DIRECTORY_SEPARATOR,ltrim($baseNamespace,'\\'));
        $this->configInstance=null;
        $configFilePath=$this->fullPath."/config/Config.php";
        if(is_file($configFilePath))
        {
            include_once($configFilePath);
            $className=$baseNamespace.'\config\Config';
            $this->configInstance=new $className();
        }
    }
    function getName()
    {
        return $this->name;
    }
    function getConfig()
    {
        return $this->configInstance;
    }

    function getBasePath()
    {
        return $this->basePath;
    }
    function getFullPath()
    {
        return $this->fullPath;
    }
    function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    function getModelDescriptor($objectName)
    {
        return new \lib\model\ModelDescriptor($objectName,null,$this);

    }

    function includeFile($className)
    {
        return $this->includeModel($className);
    }

    function includeModel($modelName)
    {
        $descriptor = $this->getModelDescriptor($modelName, $this);
        $descriptor->includeModel();
    }
    function getModels($path=null,$prefix=null)
    {
        if($path==null)
            $path=$this->getFullPath();
        $path=$path."/objects";
        if(!is_dir($path))
            return null;
        $dir = new \DirectoryIterator($path);
        $objects=array();
        if($prefix==null)
            $prefix=$this->getBaseNamespace();
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $name=$fileinfo->getFilename();
                // Proteccion contra carpetas que existan dentro de objects, pero que no contengan modelos.
                if(is_file($fileinfo->getRealPath()."/".$name.".php") && is_file($fileinfo->getRealPath()."/Definition.php")) {
                $current=array(
                    "name"=>$name,
                    "package"=>$this->name,
                    "path"=>$fileinfo->getRealPath(),
                    "class"=>$prefix.'\\'.$name
                );
                $subobjects=$this->getModels($current["path"],$current["class"]);
                if($subobjects)
                    $current["subobjects"]=$subobjects;
                $objects[]=$current;
            }
        }
        }
        return $objects;
    }
    public function getWorkers(?String $path=null, ?String $prefix=null) : ?Array
    {
        $prefix  = $prefix ?? "model\\$this->baseNamespace\\";
        $path    = $path   ?? $this->getFullPath();
        $path   .= "/objects/";
        $workers = [];

        if(!is_dir($path))
            return null;

        $dir = new \DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $namespace = $prefix.$fileinfo->getFilename().'\\workers\\';
                $curPath = $path.$fileinfo->getFilename().'/objects/workers/objects/';
                if (is_dir($curPath)) {
                    $curDir = new \DirectoryIterator($curPath);
                    foreach ($curDir as $curFile) {
                        if (!$curFile->isDot() && $curFile->isDir()) {
                            $workers[] = $namespace.$curFile->getFilename();
                        }
                    }
                }
            }
        }
        return $workers;
    }

    static function getInfoFromClass($className)
    {
        $normalized=str_replace("\\",'/',$className);
        $classes=self::getResourcesByType("class");
        $regPrefix="^/?model/(?<package>[^/]+)/(?<model>[^/]+)";

        $info=null;
        for($k=0;$k<count($classes);$k++)
        {
            $c=$classes[$k];
            $reg=$regPrefix;

            $reg .= "(?:/(?!Definition)(?<submodel>[^/]+))?";
            if (isset($c["directory"]) && $c["directory"] !== "")
                $reg .= ("/".($c["directory"] ));
            if (isset($c["class"])) {
                $reg .= ("/".$c["class"]);
            }
            else {
                    if(!isset($c["noitem"]))
                        $reg .= "/(?<item>(.*))";
                }

            $reg.="$";
            $matches=[];
            if(preg_match("#".$reg."#",$normalized,$matches))
            {
                if(isset($matches["submodel"]) && $matches["submodel"]=="")
                    unset($matches["submodel"]);
                $package=Package::$packages[$matches["package"]];
                if(!$package)
                {
                    throw new PackageException(PackageException::ERR_UNKNOWN_PACKAGE,["package"=>$matches["package"]]);
                }
                $startClass='\model\\'.$matches["package"].'\\'.$matches["model"];
                $startFile=$package->getFullPath()."/objects/".$matches["model"];
                switch($c["resource"])
                {
                    case Package::MODEL:{
                        if(!isset($matches["submodel"]))
                            $startFile=$startFile."/".$matches["model"].".php";
                        else {
                            $startClass .= ('\\' . $matches["submodel"]);
                            $startFile .= ('/objects/' . $matches["submodel"] . "/" . $matches["submodel"] . ".php");
                        }
                    }break;
                    default:{
                        $startClass.=(isset($matches["submodel"])?'\\'.$matches["submodel"]:'').
                            ($c["directory"]!==""?'\\'.str_replace('/','\\',$c["directory"]):"").'\\'.
                            (isset($c["class"])?$c["class"]:$matches["item"]);
                        $startFile.=(isset($matches["submodel"])?'/objects/'.$matches["submodel"]:'').
                            ($c["directory"]!==""?'/'.$c["directory"]:"").
                            "/".(isset($c["class"])?$c["class"]:$matches["item"]).".php";
                    }
                }
                return [
                    "package"=>$matches["package"],
                    "model"=>$matches["model"],
                    "submodel"=>isset($matches["submodel"])?$matches["submodel"]:null,
                    "resource"=>$c["resource"],
                    "item"=>$matches["item"],
                    "class"=>$startClass,
                    "file"=>$startFile,
                ];

            }
        }
        if($info==null)
            throw new PackageException(PackageException::ERR_UNKNOWN_CLASS,["class"=>$className]);
        return $info;
    }
    // Obtiene todos los elementos de un tipo definido en un modelo, sean datasources, forms, vistas, etc.


    static function getInfo($package,$model,$submodel,$resourceType,$item="*",$includeProjectPath=true)
    {
        $c=Package::getResourceById($resourceType);

        if(!isset(Package::$packages[$package]))
            throw new PackageException(PackageException::ERR_UNKNOWN_PACKAGE,["package"=>$package]);
        $packageClass=Package::$packages[$package];

        $class='\model\\'.$package.'\\'.$model;
        $path=$packageClass->getFullPath()."/objects/".$model;
        if($submodel!==null)
        {
            $class.='\\'.$submodel;
            $path.='/objects/'.$submodel;
        }
        if($resourceType==Package::MODEL)
        {
            if($submodel!==null)
                $path.=("/".$submodel.".php");
            else
                $path.=("/".$model.".php");
        }
        else
        {
            $class.='\\';
            $path.='/';
            if($c["directory"]!="") {
                $path .= $c["directory"];
                $class.=str_replace('/','\\',$c["directory"]);
            }
            if($c["class"]) {
                $class .= $c["class"];
                $path.=($c["class"].".php");
            }
            else
            {
                if($c["file"])
                {
                    $path.="/".$c["file"];
                    // $class no tiene sentido
                    $class=null;
                }
                else
                {
                    if($c["type"]=="class")
                    {
                        $class.=('\\'.($item!=="*"?$item:""));
                        $path.=('/'.$item.".php");
                    }
                    else
                    {
                        // $class no tiene sentido
                        $class=null;
                        $path.=('/*'.$c["extension"]);
                    }
                }
            }
        }



        $info = [
            "package" => $package,
            "model" => $model,
            "submodel" => $submodel,
            "resource" => $resourceType,
            "class" => $class,
            "file" => $includeProjectPath==false? str_replace(realpath(PROJECTPATH),"",realpath($path)):realpath($path)
        ];
        if($item!=="*") {
            return $info;
        }
        else
        {
                $src=glob($path);
                $result=[];
                for($k=0;$k<count($src);$k++)
                {
                    $cur=$info;
                    $curFile=basename($src[$k]);
                    $p=explode(".",$curFile);
                    $p2=$src[$k];
                    $cur["file"]=$includeProjectPath==false? str_replace(realpath(PROJECTPATH),"",realpath($p2)):realpath($p2);
                    $cur["class"].=$p[0];
                    $cur["item"]=$p[0];
                    $result[]=$cur;
                }
                return $result;
        }
    }

    static $packageResourcesByType=[];
    static function getResourceById($resourceId)
    {
        $meta=Package::getResourceMetaData();
        $res=array_filter($meta,function($item) use ($resourceId){return $item["resource"]==$resourceId;});
        if(count($res)==0)
            throw new PackageException(PackageException::ERR_UNKNOWN_RESOURCE_TYPE,["type"=>$resourceId]);
        return array_shift($res);
    }
    static function getResourcesByType($type)
    {
        if(!isset(Package::$packageResourcesByType[$type])) {

            $res = [];
            $meta=self::getResourceMetaData();
            for ($k=0;$k<count($meta);$k++) {
                $v=$meta[$k];
                if ($v["type"] == $type) {
                    $res[] = $v;
                }
            }
            Package::$packageResourcesByType[$type] = $res;
        }
        return Package::$packageResourcesByType[$type];

    }
    function _getModelResourceTree($modelName,$parentModel=null)
    {
        $model=$parentModel==null?$modelName:$parentModel;
        $submodel=$parentModel==null?null:$modelName;
        $modelInfo=Package::getInfo($this->name,$model,$submodel, Package::MODEL,null,false);
        $curName=$modelName;
        $modelInfo["name"]=$modelInfo["model"];
        $modelInfo["children"]=[];

        $metaDatas=Package::getResourceMetaData();

        for($j=0;$j<count($metaDatas);$j++)
        {
            $metaData=$metaDatas[$j];
            switch($metaData["resource"])
            {
                case Package::MODEL:{

                }break;
                default:{
                    $info=Package::getInfo($this->name,$model,$submodel,$metaData["resource"]);
                    if(count($info)>0) {
                        $modelInfo["children"][]= ["resource" => $metaData["resource"] . "_container", "name" => $metaData["resource"],
                            "children" => Package::getInfo($this->name, $model, $submodel, $metaData["resource"],"*",false)
                        ];

                    }
                }
            }
        }
        if(isset($modelInfo["subobjects"]))
        {
            $newNode=["resource"=>"Submodel_Container","name"=>"Submodels","children"=>[]];
            for($k=0;$k<count($modelInfo["subobjects"]);$k++)
            {
                $newNode["children"][]=$this->_getModelResourceTree($curName,$modelInfo["subobjects"][$k]["name"]);
            }
            $modelInfo["children"][]=$newNode;
        }
        return $modelInfo;

    }
    function getResourceTree()
    {
        $pkgNode=["resource"=>"Package","name"=>$this->name,"children"=>[]];
        $models=$this->getModels();
        if($models) {
            for ($k = 0; $k < count($models); $k++) {
                $pkgNode["children"][] = $this->_getModelResourceTree($models[$k]["name"]);
            }
        }
        return $pkgNode;
    }
    static function getResourceMetaData()
    {

        return [
            [
                "resource"=>Package::MODEL,
                "type"=>"class",
                "directory"=>"",
                "noitem"=>true
            ],

            [
                "resource"=>Package::ACTION,
                "type"=>"class",
                "directory"=>"actions"//,
                //"extension"=>".php"
            ],
            [
                "resource"=>Package::DATASOURCE,
                "type"=>"class",
                "directory"=>"datasources"
            ],
            [
                "resource"=>Package::HTML_FORM,
                "type"=>"class",
                "directory"=>"html/forms"
            ],
            [
                "resource"=>Package::HTML_FORM_TEMPLATE,
                "type"=>"file",
                "directory"=>"html/forms",
                "extension"=>".wid"
            ],
            [
                "resource"=>Package::HTML_VIEW,
                "type"=>"file",
                "directory"=>"html/views",
                "extension"=>".wid"
            ],
            [
                "resource"=>Package::JS_MODEL,
                "type"=>"file",
                "directory"=>"js",
                "file"=>"Model.js"
            ],
            [
                "resource"=>Package::JS_FORM,
                "type"=>"file",
                "directory"=>"js/Siviglia/forms",
                "extension"=>".js"
            ],
            [
                "resource"=>Package::JS_VIEW,
                "type"=>"file",
                "directory"=>"js/Siviglia/views",
                "extension"=>".js"
            ],
            [
                "resource"=>Package::TYPE,
                "type"=>"class",
                "directory"=>"types",
            ],
            [
                "resource"=>Package::TYPE_METADATA,
                "type"=>"class",
                "directory"=>"metadata/types"
            ],
            [
                "resource"=>Package::JS_TYPE,
                "type"=>"file",
                "directory"=>"js/types",
                "extension"=>".js"
            ],
            [
                "resource"=>Package::DEFINITION,
                "type"=>"class",
                "directory"=>"",
                "class"=>"Definition"
            ],
            [
                "resource"=>Package::CONFIG,
                "type"=>"class",
                "directory"=>"config",
                "class"=>"Config"
            ],
            [
                "resource"=>Package::WIDGET,
                "type"=>"file",
                "directory"=>"widgets",
                "extension"=>".wid"
            ],
            [
                "resource"=>Package::WORKER,
                "type"=>"class",
                "directory"=>"workers"
            ]


        ];
    }
    function installPermissions($manager)
    {
        $models=$this->getModels();
        if($models===null)
            return;
        // Creamos u obtenemos el grupo asociado a este paquete.
        $manager->createGroup("/model/".$this->name,\lib\model\permissions\PermissionsManager::PERM_TYPE_MODULE);
        $this->recurse_installPermissions($manager,$models);

    }
    function recurse_installPermissions($manager,$models)
    {
        for($k=0;$k<count($models);$k++)
        {
            $current=$models[$k];
            $class=str_replace('\\','/',$current["class"]);
            $manager->createGroup($class,\lib\model\permissions\PermissionsManager::PERM_TYPE_MODULE);
            if(isset($current["subobjects"]))
                $this->recurse_installPermissions($manager,$current["subobjects"]);
        }

    }
}
