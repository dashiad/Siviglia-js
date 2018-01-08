<?php
namespace lib\output\html\renderers;

class HtmlRenderer
{
    public function render($page, $request, $outputParams, $language = null)
    {
        global $oCurrentUser;
        if (!$language) {
            $language = DEFAULT_LANGUAGE;
        }
         \Registry::$registry["PAGE"]=$page;
         $isWork=io(\Registry::$registry,"IS_WORK",false);
         $widgetPath=$page->getWidgetPaths();
         include_once(LIBPATH."/output/html/templating/TemplateParser2.php");
         include_once(LIBPATH."/output/html/templating/TemplateHTMLParser.php");

         $oLParser=new \CLayoutHTMLParserManager();
         $oManager=new \CLayoutManager(PROJECTPATH."../","html",$widgetPath,array("L"=>array(
             "lang"=>$language,
             "LANGPATH"=>$page->id_site[0]->getCachePath()."lang/",
             "realm"=>$page->id_site[0]->namespace,
         )));
         $definition=array("LAYOUT"=>$page->getLayoutPath($isWork),
                          "CACHE_SUFFIX"=>"php",
                          "TARGET"=>$page->getCachePath().$language."/");
         $oManager->renderLayout($definition,$oLParser,true);
    }
}
