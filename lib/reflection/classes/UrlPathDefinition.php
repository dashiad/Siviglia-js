<?php
namespace lib\reflection\classes;
class UrlPathDefinition extends BaseDefinition
{
   function __construct($paths)
   {
        $this->paths=$paths;
   }
   function save()
   {
      $result=array();
      foreach($this->paths as $curPath)      
        $result=array_merge_recursive($result,$curPath);

     $code="<?php\n  namespace Website;\n  include_once(LIBPATH.\"/UrlResolver.php\");\n  class Urls extends \\UrlResolver\n  {\n";
     $code.="\t\tfunction __construct()\n\t\t{\n\t\t\t\\UrlResolver::\$paths=";
     $code.=$this->dumpArray($result,5);
     $code.=";\n\t\t\t}\n\t\t}\n?>";
     file_put_contents(WEBROOT."/Website/Urls.php",$code);
      
   }
}
