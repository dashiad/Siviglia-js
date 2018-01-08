<?php
namespace lib\reflection\html;
class UrlPathDefinition extends \lib\reflection\base\BaseDefinition
{
   function __construct($paths)
   {
        $this->paths=$paths;
   }
   function save()
   {      
      $site=$this->paths;
     $code="<?php\n  namespace Website;\n  include_once(LIBPATH.\"/UrlResolver.php\");\n  class Urls extends \\UrlResolver\n  {\n";
     $code.="\t\tfunction __construct()\n\t\t{\n\t\t\t\$currentSite=trim(WEBPATH,\"/\");\n\t\t\tparent::__construct(\n";
       $code.="\t\t\t\tarray(\$currentSite=>array(\"LAYOUT\"=>\"index.wid\",\n\t\t\t\t\t\"SUBPAGES\"=>";
     $code.=$this->dumpArray($site,8);
     $code.="\n\t\t\t\t\t)\n\t\t\t\t)\n);\n\t\t\t}\n\t\t}\n?>";
     file_put_contents(WEBROOT."/Website/Urls.php",$code);      
   }
}
