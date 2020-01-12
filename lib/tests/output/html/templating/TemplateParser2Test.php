<?php


namespace lib\tests\output\html\templating;
namespace lib\tests\storage\ES;
include_once(__DIR__."/../../../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/output/html/templating/TemplateParser2.php");
include_once(LIBPATH."/output/html/templating/TemplateHTMLParser.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;

class TemplateParser2Test extends TestCase
{

    function render($layout,$include=false)
    {
        $this->clearCache(__DIR__."/cache");
        $widgetPath=array(__DIR__."/widgets");

        $oLParser=new \CLayoutHTMLParserManager();
        $oManager=new \CLayoutManager(PROJECTPATH."../","html",$widgetPath,array());
        $definition=array("LAYOUT"=>__DIR__."/layouts/".$layout.".php",
            "CACHE_SUFFIX"=>"php",
            "TARGET"=>"./cache",
            true);
        ob_start();
        $output=$oManager->renderLayout($definition,$oLParser,$include);
        if($include)
        {
            include(__DIR__."/cache/".$layout.".php");
            $output=ob_get_clean();
        }
        else
        {
            ob_clean();
        }


        $okResult=file_get_contents(__DIR__."/results/".$layout.".php");
        $trimmedOutput=str_replace("\r\n","",$output);
        $trimmedOkResult=str_replace("\r\n","",$okResult);
        $this->assertEquals($trimmedOkResult,$trimmedOutput);
    }

    function testSimple1()
    {
        $this->render("Simple1");
    }
    function testSimple2()
    {
        $this->render("Simple2");
    }
    function testLayoutNested1()
    {
        $this->render("LayoutNested1");
    }
    function testWidgetNested1()
    {
        $this->render("WidgetNested1");
    }
    /*
     *  NOTA : Este test es importante mirarlo. La plantilla usa un widget BOX. Este widget BOX a su vez utiliza WINDOW, pero los tags de
     *  BOX y WINDOW estan mezclados. Este es BOX.wid:
     *  <div class="box">
            [*:WINDOW]
                [_TITLE]  <--- TAG PERTENECIENTE A BOX.wid
                    <div class="boxTitle">
                        [_:TITLE][_*][#] <-- EL TAG TITLE PERTENECE A WINDOW.wid, y el tag [_*] peretenece a BOX
                    </div>
                [#]
            [#]
        </div>
     *  Lo importante a entender es que el <div class="boxTitle"> va a perderse.Por que? Lo que se esta renderizando es WINDOW,y donde WINDOW permite
     *  especificar contenido es *DENTRO* de su tag TITLE, no fuera.El TITLE de box esta intentando insertar contenido entre el comienzo de WINDOW y el
     *  TITLE de window, y eso no está permitido, ya que al estar fuera de cualquier tag (de WINDOW), es ambiguo.
     *  El widget más parecido seria:
         <div class="box">
            [*:WINDOW]
                [_TITLE]  <--- TAG PERTENECIENTE A BOX.wid
                        [_:TITLE]<div class="boxTitle">[_*]</div>[#] <-- EL TAG TITLE PERTENECE A WINDOW.wid, y el tag [_*] peretenece a BOX
                [#]
            [#]
        </div>
     *  Aunque esto no es equivalente a lo anterior, pero sí más correcto.No se altera el funcionamiento de WINDOW.
     */
    function testWidgetMixed1()
    {
        $this->render("WidgetMixed1");
    }
    function testWidgetMixed2()
    {
        $this->render("WidgetMixed2");
    }
    function testWidgetMixed3()
    {
        $this->render("WidgetMixed3");
    }
    function testComplex1()
    {
        $this->render("Complex1",true);
    }
    function clearCache($dirname)
    {
            if (is_dir($dirname))
                $dir_handle = opendir($dirname);
            if (!$dir_handle)
                return false;
            while($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dirname."/".$file)) {
                        if (is_writable($dirname . "/" . $file))
                            @unlink($dirname . "/" . $file);
                    }
                    else
                        $this->clearCache($dirname.'/'.$file);
                }
            }
            closedir($dir_handle);
            rmdir($dirname);
            return true;

    }
}