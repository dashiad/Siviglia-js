<?php
namespace lib\reflection\site;
class Site extends \model\reflection\base\BaseDefinition
{

    function __construct()
    {
    }
    function loadConfiguration()
    {
        $file=$this->findConfigurationFile();

        if( $file )
        {
            include_once($file);
            return;
        }
            $this->generateDefaultConfiguration();
            $info=$this->getProjectInfo();
            include_once($info["projectPath"]."/".$this->getConfigFileName($info["projectName"]));
    }

    function getConfiguration()
    {
        extract($this->getProjectInfo());
        include_once($this->getConfigFileName($projectName));
        if( !defined("PROJECTPATH") )
        {

            return $this->generateDefaultConfiguration();
        }


        $defs=get_defined_constants();

        global $Config;
        return $Config;
    }

    function getProjectInfo()
    {
         $path=str_replace(implode(DIRECTORY_SEPARATOR,explode("/","/model/reflection/Site/Site.php")),"",__FILE__);

         $parts=explode(DIRECTORY_SEPARATOR,$path);
         $projectName=$parts[count($parts)-1];
         $projectPath=implode("/",$parts);
         return array("projectName"=>$projectName,"projectPath"=>$projectPath);
    }

    function getConfigFileName($projectId)
    {
        return "CONFIG_".$projectId.".php";
    }

    function generateDefaultConfiguration()
    {

            extract($this->getProjectInfo());

            $definition=array(
                "PROJECTNAME"=>$projectName,
                "PROJECTPATH"=>$projectPath,
                "DEVELOPMENT"=>1,
                "WEBPATH"=>"http://".$_SERVER["SERVER_NAME"]."/".$projectName);
        global $APP_NAMESPACES;
        foreach($APP_NAMESPACES as $layer)
        {
            $definition["SERIALIZERS"][$layer]=array(
                "TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"127.0.0.1",
                    "user"=>"root",
                    "password"=>"",
                    "database"=>array("NAME"=>$layer))
            );

        }
            $this->generateHtaccess($projectPath,$projectPath);
            $this->save($definition);
            return $definition;
    }

    function generateHtaccess($path,$configFilePath)
    {
        if( is_file($path."/.htaccess") )
        {
            $contents=file_get_contents($path."/.htaccess");
            $contents=preg_replace('/php\_value include\_path.*$/',"",$contents);
        }
        $contents.="php_value include_path \"".get_include_path().PATH_SEPARATOR.$configFilePath."\"";
        file_put_contents($path."/.htaccess",$contents);
    }

    function set($definition)
    {
        $this->save($definition);
    }

    function findConfigurationFile()
    {
        $info=$this->getProjectInfo();
        $pathArray = explode( PATH_SEPARATOR, get_include_path() );
            $configFile="CONFIG_".$info["projectName"].".php";

            for( $k=0;$k<count($pathArray);$k++ )
            {
                if( is_file($pathArray[$k].DIRECTORY_SEPARATOR.$configFile))
                {
                    return $pathArray[$k].DIRECTORY_SEPARATOR.$configFile;
                }
            }
            // No configuration file found.
            return null;
    }

    function save($definition)
    {

        $destFile=$this->findConfigurationFile();


        $ds=DIRECTORY_SEPARATOR=='\\'?'\\\\':DIRECTORY_SEPARATOR;
        $definition["PROJECTPATH"]=trim($definition["PROJECTPATH"],DIRECTORY_SEPARATOR).$ds;
        $definition["DIRSEP"]=$ds;
        if(! $destFile )
        {
            $info=$this->getProjectInfo();
            $destFile=$info["projectPath"].DIRECTORY_SEPARATOR.$this->getConfigFileName($info["projectName"]);
        }

        $configFile=<<<'EOD'
<?php
/**
 Configuration file for {PROJECTNAME}
 This file can be auto-generated by the editor, and should be in your include_path.
 The web editor creates an .htaccess for you, in the folder specified by PROJECTPATH.
 In case you wish to move this configuration file, be sure you also modify your include path accordingly.
*/
/**
  PROJECTNAME: This projects Name.Used by the editor, it's not required for accessing the web site.
*/
define("PROJECTNAME","{PROJECTNAME}");

/**
  PROJECTPATH: The folder containing this project.
*/
define("PROJECTPATH","{PROJECTPATH}");

/**
  WEBPATH: The url where the public project pages reside.This is the "public url", so it must point to the html/ folder 
  found inside PROJECTPATH.You can use a VirtualHost with its document root pointing to that folder.
*/
define("WEBPATH","{WEBPATH}");

/**
  INTERNAL_WEBPATH: The url to the root folder of this project.This is, the url pointing to PROJECTPATH.This shouldnt be
  publicly accessible in a production environment.
*/
define("INTERNAL_WEBPATH","{INTERNAL_WEBPATH}");

/**
  SERIALIZERS: Configuration for the default serializers (db connections), both for the app and the web layer.
  You can use the same configuration for both layers, if you want to keep them together.
*/
global $SERIALIZERS;
$SERIALIZERS={SERIALIZERS};

/**
   DEVELOPMENT: A convenience flag you probably may use.
*/
define("DEVELOPMENT",{DEVELOPMENT});
/**
  No more configuration parameters required.From this point, only a few shortcuts more are defined, based on previous definitions.
  Be sure to check web/config/Config.php and app/config/Config.php for configuration details for each of those layers.
*/
define("LIBPATH",PROJECTPATH."lib{DIRSEP}");
define("APPPATH",PROJECTPATH."app{DIRSEP}");
define("OBJECTSPATH",APPPATH."objects{DIRSEP}");
EOD;
        if( $definition["SERIALIZERS"] )
        {
            $definition["SERIALIZERS"]=$this->dumpArray($definition["SERIALIZERS"]);
        }

        $keys=array_map(function($item){return "{".$item."}";},array_keys($definition));
        $contents=str_replace($keys,array_values($definition),$configFile);
        file_put_contents($destFile,$contents);
    }

}
?>
