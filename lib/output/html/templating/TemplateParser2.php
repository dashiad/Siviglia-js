<?php
/*

  Siviglia Framework templating engine

  BSD License

  Copyright (c) 2012, Jose Maria Rodriguez Millan
  All rights reserved.

  Redistribution and use in source and binary forms, with or without modification, are permitted provided that
  the following conditions are met:

  * Redistributions of source code must retain the above copyright notice, this list of conditions and
    the following disclaimer.
  * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
    the following disclaimer in the documentation and/or other materials provided with the distribution.
  * Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to endorse or
    promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED.
IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


Please, send bugs, feature requests or comments to:

dashiad at hotmail.com

You can find more information about this class at:

http://xphperiments.blogspot.com

*/

include_once(dirname(__FILE__)."/"."Grammar.class.php");
include_once(dirname(__FILE__)."/Lock.php");
define("WIDGET_EXTENSION","wid");
function print_backtrace_and_exit($msg)
{
    echo $msg;
    exit();
}
abstract class CGrammarParser {
    var $grammar;
    function createGrammar(& $grammarObj)
    {
        $this->grammar=$grammarObj;

        $funcs=array_keys($this->grammar->params["nt"]);
        $prefix=create_function('$a','return "eval_".$a;');

        $nonTerminals=array_map($prefix,$funcs);
        for($k=0;$k<count($nonTerminals);$k++)
        {
            $callbackName=$nonTerminals[$k];
            if(!method_exists(get_called_class(),$callbackName))
                $callbackName="defaultCallback";
            $callbacks[substr($nonTerminals[$k],5)]=new FunctionObject($this,$callbackName);
        }
        $this->grammar->setPointCuts($callbacks);
    }
    abstract function initializeGrammar();
    function compile($text)
    {

        $output=$this->grammar->compile($text);
        if($output=="")
        {
            echo "ERROR:".$this->grammar->getError();
            echo "There's an error in the following template:";
            echo "<pre>".htmlentities($text)."</pre>";
            echo "<br><br>Exiting...";
            exit();
        }
        return $output;
    }
    function defaultCallback($data)
    {
        //echo "<div style=\"background-color:red;color:white;margin:1px\">LLAMADO DEFAULTCALLBACK";
        //print_r($data);
        //echo "</div>";
        return $data;
    }

}

define("PHP_CODE_REGEXP","~<\?php?.*\?>~sU");
class CWidgetGrammarParser extends CGrammarParser {

    var $WidgetStack=array();
    static $currentTemplate=null;
    static $currentWidget=null;
    var $parentWidget;
    var $context;
    var $layoutManager;
    var $codeExpr;
    var $curLevel;

    function __construct($codeExpr,$curLevel,$parentWidget,$manager,$context)
    {
        $this->parentWidget=$parentWidget;
        $this->context=$context;
        $this->layoutManager=$manager;
        $this->codeExpr=$codeExpr;
        $this->curLevel=$curLevel;

        CWidgetGrammarParser::$widgetStack=array();
        $this->initializeGrammar();
    }
    function initializeGrammar()
    {
    $this->grammar= new Grammar(array(
            'root'=>'code',
            'nt'=>array(
            'passthruText'=>new AltParser(array(
                    // Texto html
                    "text"=>"~([^[<{]++|\[(?![#@=_*])|<(?![?])|\{(?![%]))*~ms",
                    "php"=>PHP_CODE_REGEXP,
            )),
            "json_value"=>new AltParser(array('~"[^"]*"~',"~[+-]*[0-9]*[\.]*[0-9]*~",new SubParser("json_expr"))),

            "json_assignable"=>new AltParser(array(new SubParser("json_value"),new SubParser("json_array"))),
            "json_array"=>new SeqParser(array("[",
                                              new ListParser(new SubParser("json_assignable"),new Symbol(",")),
                                              "]")
                                        ),
            "json_assign_expr"=>new SeqParser(array("tag"=>'~"[a-zA-Z0-9_]+"~',
                                                           ":",
                                                            "data"=>new SubParser("json_assignable"))),
            "json_expr"=>new SeqParser(array("{",new ListParser(new SubParser("json_assign_expr"),new Symbol(",")),"}")),

            "content_tag"=>new SeqParser(array(
                            "[_*",
                           "phpAssign"=>new MaybeParser(new SeqParser(array("->$","varName"=>"~[a-zA-Z_][a-zA-Z0-9_]*~"))),
                            "]")),
             "widget_open_tag"=>new SeqParser(array(
                                            "openTag"=>"~\[[*@]:*~",
                                            //"tag"=>"~[/a-zA-Z0-9_\\-@]+~",
                                            "tag"=>"~[/a-zA-Z0-9_@]+~",
                                            "parameters"=>new MaybeParser(new SeqParser(array("(","expr"=>new SubParser("json_expr"),")"))),
                                            "control"=>new MaybeParser(PHP_CODE_REGEXP),
                                            "]"
                                                )
                                              ),
                "open_tag"=>new SeqParser(array(
                                            "openTag"=>"~\[_:*~",
                                            "tag"=>"~[/a-zA-Z0-9_@]+~",
                                            "widgetAssign"=>new MaybeParser(new SeqParser(array(":","widgetName"=>"~[/a-zA-Z0-9_@\\-]+~"))),
                                            "phpAssign"=>new MaybeParser(new SeqParser(array("=>","varName"=>"~[a-zA-Z_][a-zA-Z0-9_]*~"))),
                                            "parameters"=>new MaybeParser(new SeqParser(array("(","expr"=>new SubParser("json_expr"),")"))),
                                            "control"=>new MaybeParser(PHP_CODE_REGEXP),
                                            "]"
                                                )
                                           ),

              "close_tag"=>new SeqParser(array("[#",
                                                new MaybeParser(new EregSymbol("~[/a-zA-Z0-9_]+~")),
                                               "control"=>new MaybeParser(PHP_CODE_REGEXP),"]")),
                "tag_contents"=>new MaybeParser(new AltParser(array(
                                "simpleText"=>new MultiParser(new AltParser(array(
                                                "passthru"=>new SubParser("passthruText"),
                                                "content"=>new SubParser("content_tag"),
                                                "subwidget"=>new SubParser("subwidget"),
                                                "widget"=>new SubParser("widget")
                                            )
                                        )
                                    )
                            )
                        )
                    ),
              "subwidget"=>new SeqParser(array("tag"=>new SubParser("open_tag"),"contents"=>new SubParser("tag_contents"),"tag_close"=>new SubParser("close_tag"))),
              "widget"=>new SeqParser(array("tag"=>new SubParser("widget_open_tag"),"contents"=>new SubParser("tag_contents"),"tag_close"=>new SubParser("close_tag"))),
              "subwidgetFile"=>new SeqParser(array("contents"=>new SubParser("tag_contents"))),
              "layoutFile"=>new MultiParser(new AltParser(array(new SubParser("passthruText"),new SubParser("widget")))),
              "code"=>new SubParser("subwidgetFile")
            )
            ));
    $this->createGrammar($this->grammar);
    }
    var $treeRoot;
    static $widgetStack=array();

    /**
     *
     *    EVALUACION DE ESTRUCTURA JSON:
     *
     **/
    function eval_close_tag($params)
    {
        array_pop(CWidgetGrammarParser::$widgetStack);
        return $params;
    }
    function eval_json_value($params)
    {

        return $params["result"];
    }
    function eval_json_assignable($params)
    {
        return $params["result"];
    }
    function eval_json_array($params)
    {
        return "[".implode("",$params[1])."]";

    }
    function eval_json_assign_expr($params)
    {
        return $params["tag"].":".$params["data"];
    }
    function eval_json_expr($params)
    {
        return "{".implode("",$params[1])."}";
    }
    /**
     * FIN DE EVALUACION DE JSON
     */

    function eval_content_tag($params)
    {
        $info=array(
            "TYPE"=>"TAG_CONTENT"
        );

        if(isset($params["tag"]["phpAssign"]))
            $info["ASSIGN_TO"]=$params["tag"]["phpAssign"]["varName"];

        return $info;
    }

    function eval_subwidget($params)
    {
        $subwidgetPrefix=$params["tag"]["openTag"];
        if($subwidgetPrefix[1]=="@") // Es un plugin
            $type="PLUGIN";
        $info=array(
            "TYPE"=>"SUBWIDGET",
            "NAME"=>$params["tag"]["tag"],
            "TAG"=>$params["tag"],
            "PASSTHRU"=>$subwidgetPrefix[1]=="=",
            "LEVEL"=>strlen($subwidgetPrefix)-2,
            "CONTROL"=>array("start"=>$params["tag"]["control"],"end"=>$params["tag_close"]["control"]),
            "CONTEXT"=>$this->context
        );
        $targetVariable=null;
        if(isset($params["tag"]["phpAssign"])) {
            $info["ASSIGN_TO"]=$params["tag"]["phpAssign"];
        }

        $paramExpr=$params["tag"]["parameters"]["expr"];
        if($paramExpr)
        {
            $info["PARAMS"]=$paramExpr;
        }
        if($params["contents"])
        {
            $c=$params["contents"];
            if(!is_array($params["contents"]))
                $c=array($c);
            $info["CONTENTS"]=$c;
        }
        // Se mira ahora el php embebido dentro del tag (ifs, bucles,etc)
        $info["CONTROL"]=$this->parseControl($info);
        /*if($control)
        {
            array_unshift($info["CONTENTS"],$control[0]);
            $info["CONTENTS"][]=$control[1];
        }*/
        if($targetVariable)
        {
            $info["CONTENTS"]=array($this->parseVariableAssign($targetVariable,$info["CONTENTS"]));
        }
        return $info;
    }




    function eval_widget($params)
    {
        $subwidgetPrefix=$params["tag"]["openTag"];
        $type="WIDGET";
        $info=array(
            "TYPE"=>$type,
            "NAME"=>$params["tag"]["tag"],
            "TAG"=>$params["tag"],
            "PASSTHRU"=>$subwidgetPrefix[1]=="=",
            "LEVEL"=>strlen($subwidgetPrefix)-2,
            "CONTROL"=>array("start"=>$params["tag"]["control"],"end"=>$params["tag_close"]["control"]),
            "CONTEXT"=>$this->context
        );
        $paramExpr=isset($params["tag"]["parameters"]["expr"])?$params["tag"]["parameters"]["expr"]:null;

        if($subwidgetPrefix[1]=="@") // Es un plugin
        {
            $result = $this->layoutManager->parsePlugin($info, $info["NAME"], $params["contents"]);
            if(isset($result["FILE"]))
                $this->layoutManager->addDependency($result["FILE"], "plugin");
            else {
                for($k=0;$k<count($result);$k++)
                {
                    if($result[$k]["TYPE"]=="PHP")
                    {
                        $result[$k]["TEXT"]=$this->context->remapVariables($result[$k]["TEXT"]);
                        $result[$k]["CONTEXT"]=$this->context;
                    }
                }
            }
            return $result;
        }

        if(!is_array($params["contents"]))
            $c=array($params["contents"]);
        else
            $c=$params["contents"];
        $location="";
        $widgetContents=$this->layoutManager->layoutLoader->findWidget($params["tag"]["tag"],$location);
        $newContext=new SubwidgetFileContext();
        $this->layoutManager->addDependency($location,"widget");
        $paramSpec=null;
        if($paramExpr)
        {
            // Si hay mapeo de parametros, hay que pasar variables que estan definidas en el contexto local, a variables definidas en el contexto remoto

           $paramSpec=$this->parseParams($paramExpr,
                    $this->context->getPrefix(),
                    $newContext->getPrefix()
                );
           // Se mira si hay replaces de widget
            if(isset($paramSpec["WIDGETPARAMS"]))
            {
                $s=array();
                $r=array();
                foreach($paramSpec["WIDGETPARAMS"] as $k=>$v)
                {
                    $s[]="[|*".$k;
                    $r[]=$v;
                }
                $widgetContents=str_replace($s,$r,$widgetContents);
            }
        }

        $oParser = new CWidgetGrammarParser("subwidgetFile", 0, $info, $this->layoutManager,$newContext);
        $this->layoutManager->currentWidget = array("FILE" => $location, "NAME" => $params["tag"]["tag"]);
        $widget=$oParser->compile($widgetContents,$this->layoutManager->getLang(),$this->layoutManager->getTargetProtocol());
        if(!$widget)
        {
            echo "ERROR AL COMPILAR " . $params["tag"]["tag"] . " FILE :" . $location;
            exit();
        }
        $result=array();
        // Si el contexto actual es el "top", los elementos del layout que tienen que hacer match con el widget, son
        // los que tengan un nivel "0".Esto es porque en el layout top, todos los elementos estan en nivel 0.
        // Si el contexto actual no es el top, los elementos de nivel 1 de $c["CONTENTS"] tienen que hacer match con los
        // elementos de nivel 0 del widget.
        // Atencion, que lo que hay en $widget es un objeto de tipo SUBWIDGET_FILE
        $isTop=$this->context->isTopLayout()?0:1;
        $tmpLevel=$this->curLevel;
        $this->curLevel=$info["LEVEL"];
        $tempResult=$this->resolveWidgetReferences($c,$widget["CONTENTS"],$isTop,$result);
        $this->curLevel=$tmpLevel;
        $this->dumpBoth($c,$widget["CONTENTS"],$tempResult,"**WIDGET::".$params["tag"]["tag"],true);

        if($paramSpec!=null)
            array_unshift($tempResult,$paramSpec);
        // Se mira ahora el php embebido dentro del tag (ifs, bucles,etc)
        $info["CONTROL"]=$this->parseControl($info);
        /*if($control)
        {
            array_unshift($tempResult,$control[0]);
            $tempResult[]=$control[1];
        }*/
            // Se comprime $result
        $tt=$this->compressHTML($tempResult);
        return $tt;
        //$common->subLoad();
    }
    function compressHTML($tempResult)
    {
        $result=array();
        $n=count($tempResult);
        $current='';
        for($k=0;$k<$n;$k++)
        {

            while($k<$n && ($tempResult[$k]==null || $tempResult[$k]["TYPE"]=="HTML"))
            {
                if($tempResult[$k]!=null)
                    $current.=$tempResult[$k]["TEXT"];
                $k++;
            }
            if($current!='')
                $result[]=array("TYPE"=>"HTML","TEXT"=>$current);
            $current='';
            while($k<$n && ($tempResult[$k]==null || $tempResult[$k]["TYPE"]=="PHP"))
            {
                if($tempResult[$k]!=null)
                    $current.=$tempResult[$k]["TEXT"];
                $k++;
            }
            if($current!='')
                $result[]=array("TYPE"=>"PHP","TEXT"=>$current);

            if($k<$n)
                $current=$tempResult[$k]["TEXT"];
        }
        return $result;
    }
    function dumpArr($el,$title)
    {
        /*echo '<div style="border:1px solid black;margin-left:20px;background-color:white;padding:10px">';
        echo '<h3>'.$title.'</h3>';
        echo '<h4>'.$el["TYPE"].'<b>'.(isset($el["NAME"])?$el["NAME"]:"").'</b> ('.(isset($el["LEVEL"])?$el["LEVEL"]:"").')</h4>';
        if(isset($el["CONTENTS"]))
        {
            echo '<div style="margin:5px;background-color:gray"><h5>Contents</h5>';
            for($k=0;$k<count($el["CONTENTS"]);$k++) {
                if(!$el["CONTENTS"][$k])continue;
                $this->dumpArr($el["CONTENTS"][$k], "");
            }
            echo '</div>';
        }
        if(isset($el["TEXT"]))
        {
            echo '<div style="margin:5px">'.htmlentities($el["TEXT"]).'</div>';
        }
        echo '</div>';*/
    }
    function dumpArr2($arrNodes,$title)
    {
        $i=0;
        for($k=0;$k<count($arrNodes);$k++,$i++) {
            if($arrNodes[$k]!=null) {
                echo '<div style="background-color:#AAA">'.$i."</div>";
                $this->dumpArr($arrNodes[$k], $title);
            }
        }

    }
    function dumpBoth($layout,$widget,$result,$title,$resIsArray=false)
    {/*
        echo '<div style="margin-left:20px"><h2>'.$title.'</h2>';
        echo '<table border=1><tr><td valign="top">';
        $this->dumpArr2($layout,"LAYOUT");
        echo '</td><td valign="top">';
        $this->dumpArr2($widget,"WIDGET");
        echo '</td><td valign="top">';
        if(!$resIsArray)
            $this->dumpArr($result,"RESULT");
        else
        {
            $this->dumpArr2($result,"WIDGET");
        }
        echo '</td></tr></table>';
        echo '</div>';*/
    }

    function resolveWidgetReferences($layout,$widget,$sourceLevel)
    {

        // Se va uno a uno por los elementos del $widget, buscando elementos del layout que coincidan.
        $result=array();
        for($k=0;$k<count($widget);$k++)
        {
            switch($widget[$k]["TYPE"])
            {
                case "SUBWIDGET":
                {
                    // En el widget, hay un subwidget.Buscamos si la plantilla define instancias de ese subwidget.
                    for($j=0;$j<count($layout);$j++)
                    {
                        $cl=$layout[$j];
                        // Si en el layout encontramos un subwidget, pero es de un nivel inferior al requerido,
                        // significa que tenemos una estructura del tipo :
                        // Layout:
                        // [_:a]
                        //      [_b]
                        //          [_:c]
                        // Widget:
                        //  [_a]
                        //     [_c]
                        //
                        // Hemos encontrado b.Lo que necesitamos hacer es mantener b, y el contenido de b, procesarlo sin
                        // cambiar de widget.

                        if($cl["TYPE"]=="SUBWIDGET")
                        {
                            if($this->curLevel != $cl["LEVEL"])
                            {
                                /* Estamos en el caso de que entre 2 subwidgets remotos, hay un subwidget local
                                   Tenemos que copiar el local, y sus hijos, siempre que no sea un subwidget de nivel 1
                                */

                                $sres=$cl;
                                unset($sres["CONTENTS"]);
                                if(isset($cl["CONTENTS"])) {
                                    //$sres["CONTENTS"] = $this->resolveWidgetReferences($cl["CONTENTS"], array($widget[$k]), $widget[$k]["LEVEL"]); // $sourceLevel);
                                    $sres["CONTENTS"] = $this->resolveWidgetReferences($cl["CONTENTS"], $widget[$k]["CONTENTS"], $widget[$k]["LEVEL"]); // $sourceLevel);
                                    //$sres=($result,$this->resolveWidgetReferences($cl["CONTENTS"], $widget[$k]["CONTENTS"], $widget[$k]["LEVEL"])); // $sourceLevel);
                                }
                                //$this->dumpBoth(array($cl),array($widget[$k]),$sres,"Subwidget diferente nivel");
                                $result[]=$sres;
                                continue;

                            }
                            if($layout[$j]["NAME"]==$widget[$k]["NAME"])
                            {
                                if(isset($layout[$j]["CONTROL"]) && $layout[$j]["CONTROL"]["start"]!=null) {
                                    $result[] = $layout[$j]["CONTROL"]["start"];
                                }

                                if(isset($layout[$j]["PARAMS"]))
                                {
                                    $lpar=$layout[$j]["PARAMS"];
                                    $parameters=json_decode($lpar,true);
                                    $parentPrefix=$layout[$j]["CONTEXT"]->phpPrefix;
                                    $localPrefix=$widget[$k]["CONTEXT"]->phpPrefix;
                                    $text="<?php \n";
                                    foreach($parameters as $key=>$value)
                                    {
                                        $isVar=false;
                                        $isReference=false;
                                        $isGlobal=false;
                                        $varName=null;
                                        if($value[0]=='$') {
                                            $isVar=true;
                                            $varName=substr($value,1);
                                        }
                                        else {
                                            if($value[0]=="&" && $value[1]=='$') {
                                                $isVar=true;
                                                $isReference=true;
                                                $varName=substr($value,2);
                                            }
                                        }

                                            if($isVar)
                                            {
                                                if(is_array($layout[$j]["CONTEXT"]->noWidGlobals)) {
                                                    $isGlobal=in_array('$'.$varName,$layout[$j]["CONTEXT"]->noWidGlobals);
                                                }

                                                $text.='$'.$localPrefix.$key.'= '.($isReference?"&":"").'$'.
                                                                                    ($isGlobal?"":$parentPrefix).$varName.";\n";
                                            }
                                            else
                                            {
                                                    $text.='$'.$localPrefix.$key."='".str_replace("'","\\'",$value)."';\n";
                                            }
                                    }
                                    $text.="\n?>";
                                    $result[]=array("TYPE" => "PHP", "TEXT" => $text);
                                    }
                                $lc=isset($layout[$j]["CONTENTS"])?$layout[$j]["CONTENTS"]:array();
                                $wc=isset($widget[$k]["CONTENTS"])?$widget[$k]["CONTENTS"]:array();
                                $subResult=$this->resolveWidgetReferences($lc,$wc,$sourceLevel);
                                $this->dumpBoth($lc,$wc,$subResult,"Subwidget de mismo nivel",true);
                                // Se comprueba aqui si el widget tiene un ASSIGN_TO
                                // Si eso fuera asi, $subResult no contiene nada.Esto es porque, en el widget, este subwidget (tag) no tiene
                                // un subtag de contenido.
                                // Es decir, en la plantilla es : [_a=>$mivar][#]. Al no tener un tag de contenido ([_*]), la llamada
                                // a resolveWidgetReferences va a devolver vacio.Por eso, tenemos que obtener el valor directamente del
                                // layout, de lo que nos ha llegado, y no de lo que hemos parseado.
                                if(isset($widget[$k]["ASSIGN_TO"]))
                                {
                                    $targetVariable = $widget[$k]["ASSIGN_TO"]["varName"];
                                    // Lo obtenemos del layout, no del $subResult
                                    $processed=$this->compressHTML($layout[$j]["CONTENTS"]);
                                    // Sin embargo, el contexto debe ser el del subwidget, no el del $this (que apunta al layout)
                                    $subResult=$this->parseVariableAssign($targetVariable,$processed,$widget[$k]["CONTEXT"]);
                                }
                                if($subResult!=null)
                                    $result=array_merge($result,$subResult);
                                if(isset($layout[$j]["CONTROL"]) && $layout[$j]["CONTROL"]["end"]!=null) {
                                    $result[] = $layout[$j]["CONTROL"]["end"];
                                }
                            }
                        }

                    }
                }break;
                case "TAG_CONTENT":
                {
                    // LLegamos a un tag content...Copiamos todo lo que haya en el widget en este momento:
                    if(isset($widget[$k]["ASSIGN_TO"]))
                    {
                        if(count($layout)==1 && $layout[0]["TYPE"]=="HTML")
                        {
                            $result[]=$this->parseVariableAssign($widget[$k]["ASSIGN_TO"],$layout[0]["TEXT"]);
                            break;
                        }
                    }
                    for($s=0;$s<count($layout);$s++)
                    {
                        if(!$layout[$s])
                            break;
                        $result[]=$layout[$s];
                    }
                }break;
                case "PHP":
                {
                    $result[]=$widget[$k];
                }break;
                default:
                {
                    if($widget[$k]!=null)
                    $result[]=$widget[$k];
                }
            }
        }
        return $this->compressHTML($result);
        //return $result;
    }
    function parseParams($paramsExpr,$parentPrefix,$localPrefix)
    {

        $data = json_decode($paramsExpr, true);
        $text="";
        $code="";
        $widgetParams=array();
        foreach($data as $key=>$value) {
            // Se obtienen los parametros de widget, que se utilizan para pasar nombres de widget como
            // parametro a otros widgets.
            if($key[0]=="|")
            {
                // Es un nombre de widget a sustituir en el widget cargado.
                $widgetParams[substr($key,1)]=$value;
                continue;
            }
            ob_start();
            var_export($value);
            $exported=ob_get_clean();
            $replaced=preg_replace('/[\'"](&{0,1}\$)([^\'"]*)[\'"]/', '\1'.$parentPrefix.'\2',$exported);
            $text.=('$'.$localPrefix.$key."=".$replaced.";\n");
        }
        if($text!="")
            $code = "<?php " . $text . " ?>";
        return array("TYPE" => "PHP", "TEXT" => $code, "WIDGETPARAMS"=>$widgetParams);
    }
    function parseVariableAssign($varName,$children,$context=null)
    {
        $c=$context==null?$this->context:$context;
        if(count($children)==1 && $children[0]["TYPE"]=="HTML")
        {
            if($varName[0]!='$')
                $varName='$'.$varName;
            return array(array("TYPE"=>"PHP","TEXT"=>$c->remapVariables("<?php ".$varName."='".addslashes($children[0]["TEXT"])."';?>")));
        }
        return $children;
    }
    function parseControl($value)
    {
        if($value["CONTROL"] && $value["CONTROL"]["start"])
        {

            $start=$value["CONTROL"]["start"];

            $end=$value["CONTROL"]["end"];
            $start=trim($start);
            $end=trim($end);
            if(substr($start,-1)=="{")
            {
                if(substr($end,1)!="}")
                    $end="}".$end;
            }
            $fullC=$start."/* --CONTROL-- */".$end;
            $remapped=$this->context->remapVariables($fullC);
            $parts=explode("/* --CONTROL-- */",$remapped);
            return array("start"=>array("TYPE"=>"PHP","TEXT"=>$parts[0]),
                         "end"=>array("TYPE"=>"PHP","TEXT"=>$parts[1]));
        }
        return null;
    }

    function eval_passthruText($params)
    {
        switch($params["selector"])
        {
        case "text":
            {
                $trimmed=trim($params["result"]);
                if($trimmed=="")
                    return null;
                // Evitar problemas con jquery
                if($trimmed[0]=="$" && ($trimmed[1]!="(" && $trimmed[1]!="."))
                    return array(
                        "TYPE"=>"PHP",
                        "TEXT"=>"<?php echo ".$trimmed.";?>"
                    );
                else
                    return array("TYPE"=>"HTML","TEXT"=>$params["result"]);

            }break;
        case "php":
            {
                $res= array("TYPE"=>"PHP",
                            "TEXT"=>$this->context->remapVariables($params["result"]),
                            "CONTEXT"=>$this->context
                );
                return $res;
            }break;
        }
    }
    /**
     *         tag_contents::= dataText-><datasource_text> || simpleText=>( passthru-><passthruText>
                               || subwidget-><subwidget>
                               || widget-><widget>)*.
     */
    function eval_tag_contents($params)
    {
        $results=array();
        $cc=& $params["result"];
        if($cc!=null) {

            $nItems = count($cc);
            for ($k = 0; $k < $nItems; $k++) {
                $c = $cc[$k];
                if ($c["selector"] == "widget")
                    $results = array_merge($results, $c["result"]);
                else {
                    if($params["result"][$k]["result"]!==null)
                        $results[] = $params["result"][$k]["result"];
                }
            }
        }
        return $results;

    }

    function eval_subwidgetFile($param)
    {
        $filteredContents=array();
        if(is_array($param["contents"]))
        {
            for($k=0;$k<count($param["contents"]);$k++)
            {
                if($param["contents"][$k])
                    $filteredContents[]=$param["contents"][$k];
            }
        }
        return array("TYPE"=>"SUBWIDGET_FILE","CONTENTS"=>$filteredContents);
    }

    function eval_layoutFile($param)
    {
        $nParams=count($param);
        $results=array();
        for($k=0;$k<$nParams;$k++)
        {
            if($param[$k]["result"])
                $results[]=$param[$k]["result"];
        }
        return array("TYPE"=>"LAYOUT_FILE","CONTENTS"=>$results);
    }
}


class SubwidgetFileContext
{
    static $counter=0;
    static $stack=array();
    var $contents;
    var $phpPrefix;
    var $phpState;
    var $noWidGlobals;
    var $contextType;
    const CONTEXTYPE_TOPLAYOUT=0;
    const CONTEXTYPE_WIDGETFILE=1;

    function __construct($contextType=SubwidgetFileContext::CONTEXTYPE_WIDGETFILE)
    {
        $this->phpPrefix="v".SubwidgetFileContext::$counter;
        SubwidgetFileContext::$counter++;
        SubwidgetFileContext::$stack[]=$this;
        $this->contextType=$contextType;
        $this->phpState = array("CONTEXT"=>"global");
    }
    function getPrefix()
    {
        return $this->phpPrefix;
    }
    static function getCurrent()
    {
        $c=count(SubwidgetFileContext::$stack);
        return SubwidgetFileContext::$stack[$c-1];
    }
    function isTopLayout()
    {
        return $this->contextType==SubwidgetFileContext::CONTEXTYPE_TOPLAYOUT;
    }
    function remove()
    {
        array_pop(SubwidgetFileContext::$stack);
    }
    function remapVariables($text,$prefix=null)
    {
        $state = $this->phpState;
        if(!$prefix)
            $prefix = $this->phpPrefix;
        $tokens = token_get_all($text);
        $nTokens = count($tokens);
        $newText = "";
        $k = 0;
        $oldPrefix = "";
        $lastWasObject = false;
        $lastWasGlobal = false;

        while ($k < $nTokens) {

            if (is_array($tokens[$k])) {

                if ($oldPrefix) {
                    $prefix = $oldPrefix;
                    $oldPrefix = "";
                }
                if ($lastWasObject) {
                    $newText .= $tokens[$k][1];
                    $lastWasObject = false;
                    $k++;
                    continue;
                }
                if ($lastWasGlobal) {
                    if( $tokens[$k][0] == T_WHITESPACE ) {
                        $k++;
                        $newText .= ' ';
                        continue;
                    }
                    $lastWasGlobal = false;
                    //if ($this->currentWidget)
                    //    $this->currentWidget["GLOBALS"][] = $tokens[$k][1];
                    //else
                    $this->noWidGlobals[]=$tokens[$k][1];

                }

                    //if (isset($this->currentWidget["GLOBALS"]) && in_array($tokens[$k][1], $this->currentWidget["GLOBALS"])) {
                if (isset($this->noWidGlobals) && in_array($tokens[$k][1], $this->noWidGlobals)) {
                        $oldPrefix = $prefix;
                        $prefix = "";
                }

                switch ($tokens[$k][0]) {
                    //case T_OPEN_TAG:{$newText.='<?php ';echo "<h1>OPEN</h1>";}break;
                    //case T_OPEN_TAG_WITH_ECHO:{$newText.='<?=';}break;
                    case T_STRING_VARNAME: {
                        $newText .= '$' . $prefix . substr($tokens[$k][1], 1);
                    }
                        break;
                    case T_ENCAPSED_AND_WHITESPACE: {
                        //$newText .= '$' . $prefix . substr($tokens[$k][1], 1);
                        $newText.=$tokens[$k][1];
                    }
                        break;
                    case T_FUNCTION: {
                        if ($state["CONTEXT"] == "global")
                            $state["CONTEXT"] = "function";
                        $newText .= $tokens[$k][1];
                    }
                        break;
                    case T_VARIABLE: {
                        // Evitamos sobreescribir variables superglobales
                        if ($tokens[$k][1] == '$GLOBALS' || $tokens[$k][1][1] == '_')
                            $newText .= $tokens[$k][1];
                        else {

                            if ($state["CONTEXT"] == "global")
                                $newText .= '$' . $prefix . substr($tokens[$k][1], 1);
                            else
                                $newText .= $tokens[$k][1];
                        }
                    }
                        break;
                    case T_DOUBLE_COLON:
                    case T_OBJECT_OPERATOR: {
                        $lastWasObject = true;
                        $newText .= $tokens[$k][1];
                    }
                        break;
                    case T_GLOBAL: {
                        $lastWasGlobal = true;
                        $newText .= $tokens[$k][1];
                    }
                        break;
                    default: {
                        $newText .= $tokens[$k][1];
                    }
                }
            } else {
                $lastWasObject = false;
                $newText .= $tokens[$k];
                if ($state["CONTEXT"] != "global") {

                    if ($tokens[$k] == "{")
                        $state["BRACKETS"]++;
                    if ($tokens[$k] == "}") {
                        $state["BRACKETS"]--;
                        if ($state["BRACKETS"] == 0)
                            $state["CONTEXT"] = "global";
                    }
                }

            }

            $k++;
        }
        $this->phpState = $state;
        return $newText;
    }
}
class LayoutLoader
{
    var $widgetPath;
    var $manager;
    static $lastWidget="";
    function __construct($manager,$widgetPath)
    {
        $this->widgetPath=$widgetPath;
        $this->manager=$manager;
    }
    function findWidgetFile($widgetName,$widgetPath)
    {
        if(!$widgetPath)
            $widgetPath="";

        reset($this->widgetPath);
        foreach($this->widgetPath as $key=>$value)
        {
            //echo "TRYING ".$value."/".$widgetPath."/".$widgetName.".".WIDGET_EXTENSION."<br>";
            if(defined("USE_WORK_WIDGETS"))
            {
                if(is_file($value."/".$widgetPath."/".$widgetName."_work.".WIDGET_EXTENSION))
                {
                    LayoutLoader::$lastWidget=$value."/".$widgetPath."/".$widgetName."_work.".WIDGET_EXTENSION;
                    return LayoutLoader::$lastWidget;
                }
            }
            if(is_file($value."/".$widgetPath."/".$widgetName.".".WIDGET_EXTENSION))
            {
                //echo "LOADING ".$value."/".$widgetPath."/".$widgetName.".".WIDGET_EXTENSION;
                LayoutLoader::$lastWidget=$value."/".$widgetPath."/".$widgetName.".".WIDGET_EXTENSION;
                return LayoutLoader::$lastWidget;
            }
        }

        echo "WIDGET NO ENCONTRADO :: $widgetPath / $widgetName<br>";
        echo "LAST WIDGET:".LayoutLoader::$lastWidget;
        var_dump($this->widgetPath);
        die();
    }

    function findWidget($widgetName,& $widgetLocation,$widgetPath=null)
    {
        $widgetFile=$this->findWidgetFile($widgetName,$widgetPath);
        if(!$widgetFile)
        {
            die("UNKNOWN WIDGET::".$widgetName);
        }
        $widgetLocation=$widgetFile;
        $contents=file_get_contents($widgetFile);
        $parsed=$this->processPrecompilers($contents);
        if($parsed!=$contents)
            file_put_contents($widgetFile,$parsed);
        return $parsed;
    }
    function processPrecompilers($content)
    {
        if($this->manager->preCompilers===null)
        {
            $this->preCompilers=array();
            $srcDir=dirname(__FILE__).'/'.$this->manager->getTargetProtocol().'/preCompilers';
            $d=opendir($srcDir);
            if($d)
            {
                while($f=readdir($d))
                {
                    $fullName=$srcDir.DIRECTORY_SEPARATOR.$f;
                    if(is_file($fullName))
                    {
                        include_once($fullName);
                        $className=str_replace(".php","",$f);
                        $ins=new $className($this,$this->manager->getTargetProtocol());
                        $this->manager->preCompilers[]=$ins;
                    }
                }
            }
        }
        for($k=0;$k<count($this->manager->preCompilers);$k++)
        {
            $content=$this->manager->preCompilers[$k]->parse($content);
        }
        return $content;

    }
}




class CLayoutManager
{
    var $dependencies;
    var $widgetPath;
    var $pluginParams;
    var $lang;
    var $currentWidget;
    var $currentLayout;
    var $initializedPlugins=array();
    var $varCounter=0;
    var $preCompilers;
    var $layoutParser;
    var $layoutLoader;
    static $defaultWidgetPath;

    function __construct($basePath,$targetProtocol,$widgetPath=null,$pluginParams=array(),$lang="es")
    {
        $this->targetProtocol=$targetProtocol;
        if(!$widgetPath)
            $widgetPath=CLayoutManager::$defaultWidgetPath;
        $this->layoutLoader=new LayoutLoader($this,$widgetPath);
        $this->widgetPath=$widgetPath;
        $this->dependencies=array();
        $this->staticData=array();
        $this->pluginParams=$pluginParams;
        $this->lang=$lang;
        $this->basePath=$basePath;
        $this->preCompilers=null;

    }
    static function setDefaultWidgetPath($path)
    {
        CLayoutManager::$defaultWidgetPath=$path;
    }
    static function getDefaultWidgetPath()
    {
        return CLayoutManager::$defaultWidgetPath;
    }
    function getBasePath()
    {
        return $this->basePath;
    }
    function getTargetProtocol()
    {
        return $this->targetProtocol;
    }
    function getLang()
    {
        return $this->lang;
    }
    function addDependency($widgetName,$widgetType="widget",$pluginParam=null)
    {
        if($widgetName=="")
            return;

        $this->dependencies[$widgetName]["TYPE"]=$widgetType;
        if($widgetType=="plugin")
            $this->dependencies[$widgetName]["PARAM"][]=$pluginParam;
    }

    function getPluginParams($pluginName)
    {
        if(!isset($this->pluginParams[$pluginName]))
            return array();
        return $this->pluginParams[$pluginName];
    }
    function parsePlugin($parent,$name,$contents)
    {

        $pluginName=dirname(__FILE__).'/'.$this->getTargetProtocol().'/plugins/'.$name.".php";
        $parent["FILE"]=$pluginName;
        include_once($pluginName);
        $plugin=new $name($parent,$contents,$this);
        if(!in_array($pluginName,$this->initializedPlugins))
        {
            $plugin->initialize();
            $this->initializedPlugins[]=$pluginName;
        }
        return $plugin->parse();
    }

    function getVarPrefix($widgetName)
    {
        return "v".($this->varCounter++)."_";
        $widgetName=str_replace("/","_",$widgetName);
        $suffix="_".$this->suffixes[$widgetName]["COUNTER"];
        if($suffix=="_0")
            $suffix="";
        $this->suffixes[$widgetName]["COUNTER"]++;

        return $widgetName.$suffix;
    }
    function getLayout()
    {
        return $this->currentLayout;
    }
    function renderLayout($layoutDefinition,$layoutParser,$include=false)
    {
        $this->layoutParser=$layoutParser;
        $fileName=isset($layoutDefinition["TEMPLATE"])?$layoutDefinition["TEMPLATE"]:$layoutDefinition["LAYOUT"];
        $this->currentLayout=$fileName;
        $targetDir=$layoutDefinition["TARGET"];

        if($targetDir)
            $compiledDir=$targetDir;
        else
        {
            $compiledDir=dirname($fileName)."/cache/".$this->lang."/".$this->targetProtocol."/";
        }
        $pathInfo=pathinfo($fileName);
        $base=$pathInfo["basename"];

        // El siguiente codigo  supone que la extension (ej, .wid) solo aparece al final del nombre de fichero.
        if(isset($layoutDefinition["CACHE_SUFFIX"]))
	    {
            $suffix=$layoutDefinition["CACHE_SUFFIX"];
            $base=str_replace(".".$pathInfo["extension"],
                        $suffix[0]=="."?$suffix:".".$suffix,
                        $base
                        );
	    }


        $compiledFile=$compiledDir."/".$base;
        //include_once($compiledFile);
        //return;

        $depsFile=$compiledDir."/deps_".$base;

        $this->currentWidget=array("FILE"=>$fileName);

        $mustRebuild=$this->checkCacheFile($fileName,$compiledFile,$depsFile);


        //$mustRebuild=true;
        if($mustRebuild)

        {
            @mkdir($compiledDir,0777,true);

            // Se obtiene el lock
            $lock=new Lock($compiledDir,$base);

            $lock->lock();


            // Cuando se obtiene el lock, se comprueba si realmente tenemos que reconstruir.
            $mustRebuild=$this->checkCacheFile($fileName,$compiledFile,$depsFile);
            if(true || $mustRebuild)
            {

                $contents=file_get_contents($fileName);
                if(isset($layoutDefinition["PREFIX"]))
                    $contents=$layoutDefinition["PREFIX"].$contents;

                if(isset($layoutDefinition["SUFFIX"]))
                    $contents=$contents.$layoutDefinition["SUFFIX"];

                $parsed=$this->processPrecompilers($contents);
                // En caso de que los preprocesadores hayan cambiado el fichero, se guarda
                if($parsed!=$contents)
                    file_put_contents($fileName,$parsed);
                $contents=$parsed;
                $oldMemoryLimit=ini_get('memory_limit');
                ini_set('memory_limit', '512M');
                $result=$this->processContents($contents);

                ini_set('memory_limit', $oldMemoryLimit);
                // El texto final se envia a los plugins, para que hagan las
                // ultimas sustituciones.

                foreach($this->initializedPlugins as $pluginClass)
                {
                    $cName=basename($pluginClass,".php");
                    $obj=new $cName(null,null,$this);
                    $result=$obj->postParse($result);
                }
                file_put_contents($compiledFile,$result);

                // Se almacenan las dependencias
                if(is_array($this->dependencies))
                {
                    $deps=array();
                    foreach($this->dependencies as $key=>$value)
                    {
                        if($value["TYPE"]=="plugin")
                        {
                            $deps[]="*".$key."[".implode("@@",$value["PARAM"])."]";
                        }
                        else
                            $deps[]=$key;
                    }
                    if(count($deps)>0)
                        file_put_contents($depsFile,implode(",",$deps));
                }

            }

            $lock->unlock();

        }

        if($include)
        {
           include($compiledFile);
        }
        else
        {
            if($mustRebuild)
                return $result;
            else
                return file_get_contents($compiledFile);

        }
    }
    var $layoutStack=array();

    function processContents($contents)
    {
        $topContext=new SubwidgetFileContext(SubwidgetFileContext::CONTEXTYPE_TOPLAYOUT);
        $widgetParser=new CWidgetGrammarParser('layoutFile',1,null,$this,$topContext);
        $layout=$widgetParser->compile($contents);
        if($layout && isset($layout["TYPE"]) && $layout["TYPE"]=="SUBWIDGET_FILE")
        {
            if(isset($layout["TEXT"]))
                return $layout["TEXT"];
            else
            {
                if(isset($layout["CONTENTS"]))
                {
                    $t="";
                    for($k=0;$k<count($layout["CONTENTS"]);$k++)
                    {
                        if(isset($layout["CONTENTS"][$k]["TEXT"])) {
                            if(is_array($layout["CONTENTS"][$k]["TEXT"]))
                            {
                                $t=11;
                            }
                            $t .= $layout["CONTENTS"][$k]["TEXT"];
                        }
                    }
                    return $t;
                }
            }
        }

        var_dump($layout);
        die("Unexpected output from processing contents file");
    }



    function isProcessed($widgetName)
    {
        if($this->staticData[$widgetName])
            return true;
        return false;
    }
    function addStaticData($widgetName,$datatype,$data)
    {
        $this->staticData[$widgetName][$datatype][]=$data;
    }
    function checkCacheFile($fileName,$compiledFile,$depsFile)
    {
        if(!is_file($compiledFile))
        {
            return true;
        }
        else
        {

            clearstatcache();
            $mustRebuild=false;
            $compiledInfo=stat($compiledFile);
            $layoutInfo=stat($fileName);

            if($layoutInfo["mtime"] > $compiledInfo["mtime"])
                return true;

            if($mustRebuild==false)
            {

                if(!is_file($depsFile))
                    return true;
                else
                {
                    $widgetDeps=explode(",",file_get_contents($depsFile));

                    foreach($widgetDeps as $key=>$value)
                    {
                        if($value[0]=="*")
                            continue;

                        $widgetInfo=@stat($value);
                        if(!$widgetInfo || $widgetInfo["mtime"]>$compiledInfo["mtime"])
                        {
                            return true;
                            break;
                        }
                    }
                }
            }
            return false;
        }
    }

    function processPrecompilers($content)
    {
        if($this->preCompilers===null)
        {
            $this->preCompilers=array();
            $srcDir=dirname(__FILE__).'/'.$this->getTargetProtocol().'/preCompilers';
            $d=opendir($srcDir);
            if($d)
            {
                while($f=readdir($d))
                {
                    $fullName=$srcDir.DIRECTORY_SEPARATOR.$f;
                    if(is_file($fullName))
                    {
                        include_once($fullName);
                        $className=str_replace(".php","",$f);
                        $ins=new $className($this,$this->getTargetProtocol());
                        $this->preCompilers[]=$ins;
                    }
                }
            }
        }
            for($k=0;$k<count($this->preCompilers);$k++)
            {
                $content=$this->preCompilers[$k]->parse($content);
            }
            return $content;

    }
}

// Funcion global de ayuda al debugging
function TemplateDebug()
{
    $debugging=true;
}
