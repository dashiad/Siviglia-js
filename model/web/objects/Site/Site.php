<?php
namespace model\web;
// Exception Class

class SiteException extends \lib\model\BaseException
{
    const ERR_NO_SITE_FOR_HOST=100;
}

/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/Sites.php
  CLASS:Site
*
*
**/

class Site extends \lib\model\BaseModel
{
    var $config;
    static $currentSite;
    const SITECACHE_CLASSMAP=1;
    const SITECACHE_LAYOUTS=2;
    const SITECACHE_ROUTES=3;
    const SITECACHE_FILEMAP=4;

    function getRelativeRoot(){return \lib\Paths::getRelativeSitePath()."/".$this->namespace;}
    function getRoot(){return PROJECTPATH.$this->getRelativeRoot();}
    function getCachePath($isWork=false)
    {
        $path=$this->getRoot()."/cache".($isWork?"/work":"");
        if(!is_dir($path))
            mkdir($path,0777,true);
        return $path."/";
    }
    function getRouteCachePath()
    {
        $base=$this->getCachePath();
        $base.="routes";
        if(!is_dir($base))
            mkdir($base,0777,true);
        return $base."/routes.srl";

    }
    function getRoutesPath(){return $this->getRoot()."/routes";}
    function getRoutesNamespace(){return $this->getNamespace().'\\routes';}

    function getDocumentRoot(){return $this->getRoot()."/html/";}
    function getClassPath(){return $this->getRoot()."/".ucfirst($this->namespace).".php";}
    function toRelative($path){return "/".str_replace(PROJECTPATH,"",$path);}
    function getNamespace(){return \lib\Paths::getSiteNamespace().'\\'.$this->namespace;}
    function getConfig(){
        $name=$this->getNamespace()."\\Config";
        return new $name();
    }

    function normalizePagePath($path,$separator)
    {
        $parts=explode("/",$path);
        if($parts[0]=="")
            $parts=array_shift($parts);
        $newParts=array_map(function($item){return ucfirst($item);},$parts);
        return array("norm"=>implode($separator,$newParts),"last"=>$newParts[count($newParts)-1]);
    }
    function getPage($pagePath)
    {
        $norm=$this->normalizePagePath($pagePath,"/");
        return $this->getRoot()."/pages/".$norm["norm"]."/".$norm["last"].".php";
    }
    function getPagePath($pagePath)
    {
        $norm=$this->normalizePagePath($pagePath,"/");
        return $this->getRoot()."/pages/".$norm["norm"];
    }
    function getPageClass($pagePath){
        $norm=$this->normalizePagePath($pagePath,'\\');
        return $this->getNamespace().'\pages\\'.$norm["norm"];
    }


    function getRelativeSectionResourcesPath($pagePath){
        $norm=$this->normalizePagePath($pagePath,'/');
        return "/pages/".$norm["norm"];
    }

    function getSectionResourcesPath($pageName)
    {
        return \lib\Paths::getSiteDocumentRoot($this->name).$this->getRelativeSectionResourcesPath($pageName);
    }

    static function getClass($name){return '\sites\\'.ucfirst($name);}

    function loadConfig()
    {
        $target=stream_resolve_include_path($this->getRoot()."/config/Config.php");
        if($target)
        {
            include_once($target);
            $cName=$this->getNamespace().'\\Config';
            return new $cName;
        }
        return false;
    }

    function getCache($cacheType)
    {
        if(!$this->caches)
        {
            $baseDir=$this->getCachePath();
            $this->caches=array(
                Site::SITECACHE_CLASSMAP=>new \lib\cache\SerializedFileCache($baseDir."/classMap.srl",array()),
                Site::SITECACHE_LAYOUTS=>new \lib\cache\DirectoryCache($baseDir."/pages",array()),
                Site::SITECACHE_ROUTES=>new \lib\cache\SerializedFileCache($baseDir."/routes.srl",array()),
                Site::SITECACHE_FILEMAP=>new \lib\cache\SerializedFileCache($baseDir."/paths.srl",array())
            );
        }
        return $this->caches[$cacheType];
    }
    function getUrlPaths()
    {
        $base=$this->getRoutesPath();
        return array(
            "Urls"=>$base."/Urls",
            "Definitions"=>$base."/Definitions"
        );
    }
    function getCanonicalUrl()
    {
        return $this->canonical_url;
    }

    function getName()
    {
        return $this->namespace;
    }
    function getWidgetPath()
    {
        // Retornar el WIDGETPATH del site, incluyendo el del site por defecto si no es este mismo.
        $paths=array($this->getRelativeRoot()."/widgets");
        return $paths;
    }

    function cleanup()
    {
        foreach($this->caches as $key=>$value)
            $value->save();
    }

    function getLink($tag=null)
    {
        if(!$tag)
            return $this->getCanonicalUrl();
        $protocol=\Request::$request["isSSL"]?"https":"http";
        return $protocol."://".$this->getCanonicalUrl()."/".$tag;
    }

    function getId()
    {
        return $this->id_site;
    }

    static function getCurrentWebsite()
    {
        if(isset(Site::$currentSite))
            return Site::$currentSite;

        Site::$currentSite=Site::getSiteFromHost(\Request::$request->getCurrentSite());
        return Site::$currentSite;
    }

    static function getSiteFromHost($host)
    {
        $ds=\getDataSource("\\model\\web\\Site","FullList");
        $ds->host=$host;
        $it=$ds->fetchAll();
        if($ds->count()==0)
        {
            throw new SiteException(SiteException::ERR_NO_SITE_FOR_HOST,array("host"=>$host));
        }
        return \getModel("\\model\\web\\Site",array("id_site"=>$it[0]->id_site));
    }
    static function getSiteFromNamespace($name)
    {
        $oM=new Site();
        $oM->namespace=$name;
        $oM->loadFromFields();
        if($oM->isLoaded())
            return $oM;
        return null;
    }

    function getLanguageNamespace()
    {
        return $this->namespace;
    }

    function getIdLanguage()
    {
        return $this->{"!id_lang"};
    }

    static function getEditableSites()
    {
        $ds=\getDataSource('\\model\\web\\Site',"FullList");
        $it=$ds->fetchAll();
        if($ds->count()==0)
            return null;
        return $it;
    }
    static function getAllSites()
    {
        $ds=\getDataSource('\\model\\web\\Site',"FullList");
        $it=$ds->fetchAll();
        if($ds->count()==0)
            return null;
        return $it;
    }
    function getSections()
    {
        return $this->Sections;
    }
    function getLocalPrefix()
    {
        return $this->name;
    }

    function getPageRoutingFiles($page)
    {
        $path=$page->path;
        $parts=explode("/",$path);
        $pName=array_pop($parts);

        $sPath="";
        $sNamespace="";

        if(count($parts)>0) {
            $sPath="/".implode("/" . $parts);
            $sNamespace .= "\\" . implode("\\" . $parts);
        }
        return array(
            "definition"=>array(
                "file"=>$this->getRoutesPath()."/Definitions".$sPath."/Pages.php",
                "namespace"=>$this->getRoutesNamespace."\\Definitions".$sNamespace
            ),
            "urls"=>array(
                "file"=>$this->getRoutesPath()."/Urls".$sPath."/Pages.php",
                "namespace"=>$this->getRoutesNamespace."\\Urls".$sNamespace
            ),
            "tag"=>$pName
        );
    }

    function addPage($page,$definition)
    {
        $this->manageUrls("definition",$page,function($def,$tag) use ($definition) { $def[$tag]=$definition;return $def; });
    }
    function removePage($page)
    {
        $this->manageUrls("definition",$page,function($def,$tag) { unset($def[$tag]);return $def; });
    }

    function addUrl($url,$page)
    {
        $this->manageUrls("urls",$page,function($def,$tag) use ($url) { unset($def[$url]);return $def; });
    }
    function removeUrl($url,$page)
    {
        $this->manageUrls("urls",$page,function($def,$tag) use ($url) { unset($def[$url]);return $def; });
    }
    function manageUrls($type,$page,$func)
    {
        $info=$this->getPageRoutingFiles($page);
        include_once($info[$type]["file"]);
        $target=$info[$type]["namespace"]."\\Pages";
        $namespace=$info[$type]["namespace"];
        $def=$target::$definition;
        $def=$func($def,$info["tag"]);
        ob_start();
        print_r($def);
        $buf=ob_get_clean();
        $pageDef= <<<EOT
<?php
    namespace $namespace;
    class Pages
    {
        static \$definition=$buf;
    }
EOT;
        file_put_contents($info[$type]["file"],$pageDef);
        \lib\routing\RouteBuilder::rebuildSiteUrls($this);
    }
}
?>