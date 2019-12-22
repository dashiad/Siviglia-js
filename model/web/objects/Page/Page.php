<?php
namespace model\web;

use lib\model\BaseModelDefinition;
use lib\model\BaseTypedObject;

class PageException extends \lib\model\BaseException
{
    const ERR_PAGE_NOT_FOUND=1;
    const ERR_LOGIN_REQUIRED=3;
    const ERR_NOT_ALLOWED=2;
    const ERR_INVALID_PARAMETERS=2;
}

/**
 FILENAME:/var/www/percentil/backoffice//web/objects/CustomSections/CustomSections.php
  CLASS:CustomSections
*
*
**/
class Page extends \lib\model\BaseModel
{
    private $__pageConfig;
    private $__pageDef;
    const PAGE_ROLE_VIEW="View";
    const PAGE_ROLE_CREATE="Create";
    const PAGE_ROLE_EDIT="Edit";
    const PAGE_ROLE_LIST="List";
    const PAGE_ROLE_GENERIC="Generic";

    const PAGE_PERMISSION_PUBLIC="PUBLIC";
    const PAGE_PERMISSION_LOGGED="LOGGED";
    const PAGE_PERMISSION_OWNER="OWNER";
    const PAGE_PERMISSION_MODEL="MODEL";
    const PAGE_PERMISSION_SITE="SITE";


    function __construct($serializer = null, $definition = null)
    {
        $this->__objName = \lib\model\ModelService::getModelDescriptor('\model\web\Page');
        if (!$definition)
            $this->__def=Page::loadDefinition($this);
        else
            $this->__def=BaseModelDefinition::fromArray($definition);

        BaseTypedObject::__construct($this->__def->getDefinition());

        $this->__aliasDef = & $this->__objectDef["ALIASES"];

        if ($serializer)
        {
            $this->__serializer = $serializer;
            if(!isset($this->__objectDef["DEFAULT_WRITE_SERIALIZER"]))
                $this->__writeSerializer=$this->__serializer;
        }
    }
    function getTableName()
    {
        return "Page";
    }
    static function loadDefinition($model)
    {
        include_once(__DIR__."/Definition.php");
        // Se hace new() por si la definicion requiere inicializacion de constantes.
        return new \model\web\Page\Definition();
    }

    public function checkPermissions($user)
    {
        try {
            $this->getPageDefinition();
            $this->__pageDef->checkPermissions($user);
        }catch(\lib\model\permissions\AccessDefinitionException $e)
        {
            throw new PageException(PageException::ERR_NOT_ALLOWED);
        }
    }

    static function getPageFromPath($path,$site,$request,$params)
    {
        if($site==null)
            $currentSite=\model\web\Site::getCurrentWebsite();
        else
            $currentSite=\model\web\Site::getSiteFromNamespace($site);

        $ds=\getDataSource('\model\web\Page',"FullList");
        $ds->path=$path;
        $ds->id_site=$currentSite->id_site;
        $it=$ds->fetchAll();
        if($ds->count()==0)
            return null;
        return Page::getPageInstance($it[0],$request,$params);

    }
    static function getPageFromName($name,$site,$request,$params)
    {
        if($site==null)
            $currentSite=\model\web\Site::getCurrentWebsite();
        else
            $currentSite=\model\web\Site::getSiteFromNamespace($site);

        $ds=\getDataSource('\model\web\Page',"FullList");
        $ds->name=$name;
        $ds->id_site=$currentSite->id_site;
        $it=$ds->fetchAll();
        if($ds->count()==0)
            return null;
        return Page::getPageInstance($it[0],$request,$params);
    }

    static function getPageInstance($data,$request,$params)
    {
        $site=\getModel('\model\web\Site',array("id_site"=>$data->id_site));
        //$path=$site->getPagePath($data->path);
        $parts=explode("/",$data->path);
        $name=array_pop($parts);
        $pageName=ucfirst(strtolower($name))."Page";
        $namespace=$site->getPageClass($data->path);
        $fullName=$namespace.'\\'.$pageName;
        $defName=$namespace.'\\Definition';
        $definition=new $defName();
        $definition->loadFields($params,"HTML");
        if(!class_exists($fullName))
        {
            debug("Error instanciando pagina ".$fullName,true);
        }
        $instance = new $fullName();
        $instance->loadFromArray($data->getRow(),"MYSQL");
        $instance->setPageDefinition($definition,$params);
        return $instance;
    }
    /*
     * Se sobreescribe el metodo get para obtener variables de la definicion de pagina, con
     * prioridad sobre los campos del modelo.
     */
    function __get($varName)
    {
        try{
            if($this->__pageDef)
                return $this->__pageDef->{$varName};
            return parent::__get($varName);
        }
        catch(\lib\model\BaseTypedException $e)
        {
            return parent::__get($varName);
        }
    }

     function initializePage($params){}

    public function getPageName()
    {
        $parts=explode("/",$this->path);
        $last=array_pop($parts);
        return ucfirst(strtolower($last));
    }
    public function getPageClassName()
    {
        return $this->getPageName()."Page";
    }
    private function getPageDefinitionPath()
    {
        return $this->getPagePath() . "/Definition.php";
    }
    public function getPageDefinition()
    {
        if($this->__pageConfig==null) {
            include_once($this->getPageDefinitionPath());
            $className = $this->id_site[0]->getPageClass($this->path) . "\\Definition";
            $objDef=new $className();
            $this->setPageDefinition($objDef);
        }
        return $this->__pageConfig;
    }
    public function getPageDefinitionObject()
    {
        if($this->__pageDef==null)
            $this->getPageDefinition();
        return $this->__pageDef;
    }
    function setPageDefinition($obj,$params)
    {
        $this->__pageDef=$obj;

        try
        {
            $obj->loadFromArray($params,"HTML");
        }catch(\lib\model\BaseTypedException $e)
        {
            throw new PageException(PageException::ERR_INVALID_PARAMETERS);
        }
        $this->__pageConfig = $obj->getDefinition();
    }
    function getPagePath()
    {
        return $this->id_site[0]->getPagePath($this->path);
    }
    function getSectionResourcesPath()
    {
        return $this->id_site[0]->getSectionResourcesPath($this->path);
    }
    function getRelativeSectionResourcesPath()
    {
        return $this->id_site[0]->getRelativeSectionResourcesPath($this->path);
    }
    function savePageDefinition()
    {
        $this->id_site[0]->addPage($this,$this->getPageDefinition());
    }
    function savePageUrl()
    {
        $this->id_site[0]->addUrl($this->url,$this);
    }
    function addUrl($url)
    {
        $this->id_site[0]->addUrl($url,$this);
    }
    function save($serializer=null)
    {
        $this->id_site[0]->removePage($this);
        $this->id_site[0]->removeUrl($this->path,$this);
        if($this->__isNew())
            $this->{"*date_add"}->setAsNow();
        $this->{"*date_modified"}->setAsNow();
        parent::save($serializer);
        $this->id_site[0]->addPage($this);
        $this->id_site[0]->addUrl($this->path,$this);
    }

    function saveConfig($config)
    {
        $namespace=$this->id_site[0]->getPageClass($this->path);

        ob_start();
        print_r($config);
        $buf=ob_get_clean();
        $pageDef= <<<EOT
<?php
    namespace $namespace;
    class Definition extends \model\web\Page\PageDefinition
    {
        private \$definition=$buf;
    }
EOT;
        file_put_contents($this->getPageDefinitionPath(),$pageDef);
    }

    function generatePageFiles()
    {
        $path=$this->id_site[0]->getPagePath();
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        // Se pre-genera una clase php para este layout.
        $namespace=$this->id_site[0]->getPageClass();
        $className=$this->getPageClassName();
        $classFile=<<<EOT
<?php
namespace $namespace;

class $className extends \model\web\Page
{
    function initializePage(\$params)
    {
    }
}
?>
EOT;
        $basePath=$this->id_site[0]->getPagePath($this->path);
        $classPath=$basePath."/".$this->getPageClassName().".php";
        file_put_contents($classPath,$classFile);
        $pageWidget=<<<EOT
[*:PAGE]
    [_:CONTENT][_*][#]
[#]
EOT;
        $curWidPath=$this->getLayoutPath($this->getPageName().".wid");
        file_put_contents($curWidPath,$pageWidget);
        $privateWid=$this->getPageName();
        $pageWidget=<<<EOT
[*$privateWid]

[#]
EOT;
        $mainWidgetPath=$basePath."/".$this->getPageName().".php";
        file_put_contents($mainWidgetPath,$pageWidget);
        $repo=\lib\php\Git::open(CUSTOMPATH."/..");
        $repo->run(" add ".$classPath);
        $repo->run(" add ".$curWidPath);
        $repo->run(" add ".$mainWidgetPath);
    }


    function getWidgetPath($layoutName=null,$isWork=null)
    {
        $dir=$this->id_site[0]->getPagePath($this->path)."/widgets".($isWork===true?"_work":"")."/";
        if(!is_dir($dir))
        {
            mkdir($dir,0777);
        }
        if($layoutName==null)
            return $dir;
        return $dir.$layoutName;
    }
    function getWorkWidgetPath($layoutName=null)
    {
        return $this->getWidgetPath($layoutName,true);
    }
    function getLayoutPath($isWork=false)
    {
        $site=$this->id_site[0];
        return $this->id_site[0]->getPagePath($this->path)."/".$this->getPageName().($isWork==true?"_work":"").".php";
    }
    function getWorkLayoutPath()
    {
        return $this->getLayoutPath(true);
    }
    function getLayout(){
        return file_get_contents($this->getLayoutPath());
    }
    function getCachePath($isWork=false){
        return $this->id_site[0]->getCachePath($isWork);
    }


    function getEditableLayout($subwidget=null){

        if($subwidget)
        {
            // Al editar un subwidget, se mira primero si el original existe.
            $orig=$this->getWidgetPath($subwidget.".wid");
            if(is_file($orig))
                $contents=file_get_contents($orig);
            else
            {
                $contents="";
                file_put_contents($orig,$contents);
                // Se aniade a GIT
                $repo=\lib\php\Git::open(PROJECTPATH."/..");
                $repo->run(" add ".$orig);
            }
            // Se lee / crea el widget de trabajo.
            $origWork=$this->getWidgetPath($subwidget.".wid",true);
            if(is_file($origWork))
                return file_get_contents($origWork);

            file_put_contents($origWork,$contents);
            return $contents;
        }
        // Se obtiene el layout principal de la plantilla
        $workFile=$this->getWorkLayoutPath();
        if(!is_file($workFile))
            file_put_contents($workFile,file_get_contents($this->getLayoutPath()));
        return file_get_contents($workFile);
    }

    function saveLayout($fseclang,$subWidget=null){

        file_put_contents(
            ($subWidget==null?$this->getWorkLayoutPath():$this->getWorkWidgetPath($subWidget.".wid")),
            $fseclang
        );
    }
    function acceptChanges($subWidget=null)
    {
        if($subWidget)
        {
            $ruta=$this->getWidgetPath($subWidget.".wid");
            $ruta_work=$this->getWorkWidgetPath($subWidget.".wid");
            file_put_contents($ruta,file_get_contents($ruta_work));
            $repo=\lib\php\Git::open(CUSTOMPATH."/..");
            $repo->run(" add ".$ruta);
        }
        else
        {
            $contents=$this->getEditableLayout();
            file_put_contents($this->getLayoutPath(),$contents);
        }
    }
    function getFiles()
    {
        $ds=\getDataSource("\\model\\web\\Page\\PageResource","FullList");
        $ds->id_page=$this->id_page;
        return $ds->fetchAll();
    }
    function deleteFile($id)
    {
        $ds=\getDataSource("\\model\\web\\Page\\PageResource","FullList");
        $ds->id_pageResource=$id;
        $it=$ds->fetchAll();
        if($ds->count()==0)
            return;

        @unlink($this->getSectionResourcesPath().basename($it[0]->path));
        $repo=\lib\php\Git::open(PROJECTPATH."/..");
        try {
            $repo->run(" rm  '".$this->getSectionResourcesPath().str_replace(" ",'\ ',basename($it[0]->path))."'");
        }catch(\Exception $e){}
        $resource=new \model\web\Page\PageResource();
        $resource->load($it->getRow());
        $resource->delete();
    }

    function addFile($tmpPath,$name)
    {
        $fpath = $this->getRelativeSectionResourcesPath();
        $frelative = $fpath.'/'.$name;
        $docRoot=$this->id_site[0]->getDocumentRoot();
        move_uploaded_file($tmpPath,$docRoot."/".$frelative);
        /* TODO: falla en nfs de kirondo, se quita hasta que se pueda revisar por qué */
        //$repo=\lib\php\Git::open(CUSTOMPATH."/..");
        //$repo->run(" add  '".CUSTOMPATH."/html".str_replace(" ",'\ ',$frelative)."'");
        $resource=new \model\web\Page\PageResource();
        $resource->id_page=$this->{"*id_page"}->getValue();
        $resource->path=$frelative;
        $resource->save();
    }
    function deleteSectionResourcesPath(){
        $path=$this->getSectionResourcesPath();
        $this->deleteDirectory($path);
    }

    function deleteSectionFiles(){
        $path=$this->getPagePath();
        $this->deleteDirectory($path);
    }

    function deleteDirectory($directory) {
        $repo=\lib\php\Git::open(PROJECTPATH."/..");

        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    if (filetype($directory."/".$file) == "dir"){
                        $this->deleteDirectory($directory."/".$file);
                    } else {
                        try{
                            $repo->run(" rm  ".$directory."/".$file);
                        }catch (\Exception $e){}
                    }
                }
            }
            reset($files);
            rmdir($directory);
        }
    }
    function delete($serializer=null)
    {
        $this->deleteSectionResourcesPath();
        $this->deleteSectionFiles();
        $this->id_site[0]->removePage($this);
        $this->id_site[0]->removeUrl($this->path,$this);
        $ds=\getDataSource("\\model\\web\\Page\\PageResource","Delete");
        $ds->id_page=$this->id_page;
        $ds->fetchAll();
        parent::delete($serializer);
    }

    function discardChanges($subWidget=null){

        if($subWidget)
        {
            $source=$this->getWidgetPath($subWidget.".wid");
            $dest=$this->getWorkWidgetPath($subWidget.".wid");
            file_put_contents($dest,file_get_contents($source));
        }
        else
        {
            $path_work=$this->getWorkLayoutPath();
            $path=$this->getLayoutPath();
            if (is_file($path)){
                copy($path, $path_work);
            } /*else{
                $path_wid=$this->getSectionPath()."/".ucfirst(strtolower($this->data["tag"])).".wid";
                copy($path_wid,$path_work);
            }*/
        }
    }
    function getSubWidgets()
    {
        $widgetDir=$this->getWidgetPath();
        $dir=opendir($widgetDir);
        $results=array();
        while($cf=readdir($dir))
        {
            if(!is_dir($widgetDir."/".$cf))
            {
                $results[]=str_replace(".wid","",$cf);
            }
        }
        return $results;
    }
    function deleteSubWidget($widgetName)
    {
        $widgetPath=$this->getWidgetPath($widgetName.".wid");
        $repo=\lib\php\Git::open(PROJECTPATH."/sites");
        try{
            $repo->run(" rm -rf ".$widgetPath);
        }catch(\Exception $e){}
        $widgetWork=$this->getWorkWidgetPath($widgetName.".wid");
        @unlink($widgetWork);
    }
    function getWidgetPaths($isWork=false)
    {
        $def=$this->getPageDefinition();
        $widgetPath=array();
        if(isset($def["WIDGETPATH"]))
        {
            $widgetPath=$def["WIDGETPATH"];
        }
        $widgetPath[]=$this->id_site[0]->getPagePath($this->path)."/widgets".($isWork===true?"_work":"")."/";
        // Se incluyen los paths del sitio actual (ojo, no el de la pagina, porque esta pagina podria ser llamada desde otros sites).
        $curSite=\Registry::getService("site");
        $sitePaths=$curSite->getExtraWidgetPath();
        $widgetPath=array_merge($widgetPath,$sitePaths);
        $widgetPath[]=$this->id_site[0]->getRoot()."/widgets/";
        $widgetPath[]=PROJECTPATH."/output/html/Widgets";
        $widgetPath[]=PROJECTPATH;
        return $widgetPath;
    }

    function render($renderType, $request, $outputParams)
    {
        $fileType = ucfirst($renderType) . 'Renderer';
        include_once(LIBPATH . '/output/html/renderers/' . $fileType . '.php');
        $className = "\\lib\\output\\html\\renderers\\" . $fileType;
        $renderer = new $className();
        $renderer->render($this, $request, $outputParams);
    }

    function onFormSuccess($form)
    {
        \lib\Router::routeToReferer();
    }
    function onFormError($form)
    {
        \lib\Router::routeToReferer();
    }
    function generateUrl($name, $params=array())
    {
        $router=\Registry::getService("router");
        return $router->generateUrl($name,$params);
    }
    function canAccess($permsService,$user)
    {
        $this->getPageDefinition();
        $perms=$this->__pageDef->getRequiredPermissions();
        if(!$perms)
            return true;
        return $permsService->canAccess($perms,$this,$user);
    }
}
