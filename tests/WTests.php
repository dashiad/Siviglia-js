<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css'>
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js'></script>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../Siviglia.js"></script>
    <script src="../SivigliaStore.js"></script>

    <script src="../SivigliaTypes.js"></script>
    <script src="../Model.js"></script>


    <script src="../../jqwidgets/jqx-all.js"></script>
    <script src="../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="/reflection/css/style.css">
    <link rel="stylesheet" href="../jQuery/JqxWidgets.css">
    <link rel="stylesheet" href="../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../jqwidgets/styles/jqx.light.css">
    <link rel="stylesheet"
          href="highlight/styles/ir-black.css">
    <script src="highlight/highlight.pack.js"></script>
    <style type="text/css">
        .resultError {
            background-color: #cc3636;

        }

        .result {
            margin: 2px;
            padding: 3px;
            color: honeydew;
        }
        .testTitle{
            background-color: #5b6a8a;
            padding: 3px;
            font-size: 20px;
            color: white;
        }
        .testDoc {
            background-color: #9b9ed6;
            padding: 5px;
            border-bottom: 1px solid #AAA;
        }
        .testDiv {
            border: 2px solid black;
            padding: 5px;
            background-color:#6a7592;
            margin:5px 20px;
        }
        .resultOk {
            background-color: green;

        }
        .testResult {
            margin-top:30px;
        }
        .testCont {
            height:300px;
            overflow-y:scroll;
        }
        .testDef {
            width:400px;
            float:left;
            height:250px;
            overflow:auto
        }
        .testView {
            width:400px;
            float:left;
            height:250px;
            overflow:auto
        }
        .testCode {
            height:250px;
            overflow:auto
        }
        .resultTitle {
            background-color: #DDD;
            padding: 5px;
            border: 1px solid #AAA;
        }
        .resultCode {

        }
        .resultContent {
            border: 1px solid #AAA;
            margin: 10px;
            background-color:white;
        }

    </style>
    <style type="text/css">
        #svgChart {
            width: 1000px;
            height: 500px
        }

    </style>
</head>
<style type="text/css">
</style>
<body style="background-color:#EEE">
<?php include_once("../jQuery/JqxWidgets.html"); ?>
<?php include_once("../jQuery/JqxLists.html"); ?>



<script>
    var urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has("test")) {
	//var DEVELOP_MODE=0;  // All tests
        var DEVELOP_MODE=-1; // Latest test
    } else {
	var DEVELOP_MODE = urlParams.get("test");
    }
    var Siviglia=Siviglia || {};
    Siviglia.config={
        baseUrl:'http://reflection.adtopy.com/',
        staticsUrl:'http://statics.adtopy.com/',
        metadataUrl:'http://metadata.adtopy.com/',

        locale:'es-ES',
        // Si el mapper es XXX, debe haber:
        // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
        // 2) Un Mapper en Siviglia.Model.XXXMapper
        // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
        mapper:'Siviglia'
    };
    Siviglia.Model.initialize(Siviglia.config);


</script>
<script>
    var parser = new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
    hljs.initHighlightingOnLoad();


    function countListeners() {
        var s = 0;
        for (var k in Siviglia.Dom.existingListeners)
            s++;
        return s;
    }

    function showResult(name,doc,template,view,result,callback,testNumber,exception)
    {
        var className = "result resultError";
        var message = "ERROR";
        if(!exception)
        {
            if (JSON.stringify(expected) == JSON.stringify(result)) {
                className = "result resultOk";
                message = "OK!";
            }
        }
        else
            message="ERROR:"+exception;


    }
    var cbStack=[];
    var formatHTML = function(code, stripWhiteSpaces, stripEmptyLines) {
        "use strict";
        var whitespace          = ' '.repeat(4);             // Default indenting 4 whitespaces
        var currentIndent       = 0;
        var char                = null;
        var nextChar            = null;


        var result = '';
        for(var pos=0; pos <= code.length; pos++) {
            char            = code.substr(pos, 1);
            nextChar        = code.substr(pos+1, 1);

            // If opening tag, add newline character and indention
            if(char === '<' && nextChar !== '/') {
                result += '\n' + whitespace.repeat(currentIndent);
                currentIndent++;
            }
            // if Closing tag, add newline and indention
            else if(char === '<' && nextChar === '/') {
                // If there're more closing tags than opening
                if(--currentIndent < 0) currentIndent = 0;
                result += '\n' + whitespace.repeat(currentIndent);
            }

            // remove multiple whitespaces
            else if(stripWhiteSpaces === true && char === ' ' && nextChar === ' ') char = '';
            // remove empty lines
            else if(stripEmptyLines === true && char === '\n' ) {
                //debugger;
                if(code.substr(pos, code.substr(pos).indexOf("<")).trim() === '' ) char = '';
            }

            result += char;
        }

        return result;
    }

    function checkTests(restart)
    {
        if(restart!==false) {
            if (DEVELOP_MODE !== 0) {
                if (DEVELOP_MODE === -1)
                    cbStack = [cbStack.pop()];
                else
                    cbStack = [cbStack[DEVELOP_MODE-1]];
            }
        }
        var parser = new Siviglia.UI.HTMLParser();
        while(cbStack.length>0)
        {
            var cItem=cbStack.shift();
            try {
                var rs = cItem.cb.apply(null);

                var nDiv = document.createElement("div");
                nDiv.className="testDiv ";
                var divTitle=document.createElement("div");
                nDiv.appendChild(divTitle);
                divTitle.innerHTML = "Test " + cItem.number + " (" + cItem.name + ") <i style='font-size:10px'>[listeners:"+countListeners()+"]</i>";
                divTitle.className="testTitle";
                var divDoc=document.createElement("div")
                divDoc.innerHTML=cItem.doc;
                divDoc.className="testDoc";
                nDiv.appendChild(divDoc);
                var divCont=document.createElement("div");
                divCont.className="testCont";
                divCont.style.height="250px";
                nDiv.appendChild(divCont);

                var divDef=document.createElement("div");
                divDef.style.overflowY="scroll";
                divDef.className="testDef";
                var cTitle=document.createElement("div");
                cTitle.className="resultTitle";
                cTitle.innerHTML="Templates";
                divDef.appendChild(cTitle);
                var cContent=document.createElement("div");
                cContent.className="resultCode";
                cContent.innerHTML="<pre>"+hljs.highlightAuto(formatHTML(cItem.template)).value+"</pre>";
                divDef.appendChild(cContent);
                divCont.appendChild(divDef);

                divDef=document.createElement("div");
                divDef.style.overflowY="scroll";
                divDef.className="testView";
                cTitle=document.createElement("div");
                cTitle.className="resultTitle";
                cTitle.innerHTML="View";
                divDef.appendChild(cTitle);
                cContent=document.createElement("div");
                cContent.className="resultCode";
                cContent.innerHTML="<pre>"+hljs.highlightAuto(formatHTML(cItem.view)).value+"</pre>";
                divDef.appendChild(cContent);
                divCont.appendChild(divDef);

                divDef=document.createElement("div");
                divDef.style.overflowY="scroll";
                divDef.className="testCode";
                cTitle=document.createElement("div");
                cTitle.className="resultTitle";
                cTitle.innerHTML="Code";
                divDef.appendChild(cTitle);
                cContent=document.createElement("div");
                cContent.className="resultCode";
                cContent.innerHTML="<pre>"+hljs.highlightAuto(cItem.cb.toString()).value+"</pre>";
                divDef.appendChild(cContent);
                divCont.appendChild(divDef);


                divClear=document.createElement("div");
                divClear.style.clear="both";
                nDiv.appendChild(divClear);
                divResult=document.createElement("div");
                divResult.className="testResult";

                cTitle=document.createElement("div");
                cTitle.className="resultTitle";
                cTitle.innerHTML="Result";
                divResult.appendChild(cTitle);
                var cRes=document.createElement("div");
                cRes.id="testCont"+cItem.number;
                cRes.className="resultContent";
                cRes.innerHTML='<div style="display:none">'+cItem.template+'</div>'+cItem.view;
                divResult.appendChild(cRes);
                nDiv.appendChild(divResult);
                document.body.appendChild(nDiv);
                parser.parse($("#testCont"+cItem.number));
            } catch (e) {
                console.dir(e);
            }
        }
    }
    var testNumber=0;
    function runTest(name,doc,template,view,callback) {
        testNumber++;
        if(typeof expectedResult=="undefined")
            expectedResult=true;

        cbStack.push({name:name,
                    doc:doc,
                    template:template,
                    view:view,
                    cb:callback,number:testNumber});


    }
    runTest("Widget Minimo","Widget minimo, con una plantilla que contiene solo un texto, y una clase vacia",
    '<div data-sivWidget="Test.Minimal" data-widgetCode="Test.Minimal"><span>Hello world</span></div>',
        '<div data-sivView="Test.Minimal"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'Minimal':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {

                            },
                            initialize: function (params) {
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue","Uso simple de SivValue: Una variable asignada en preInitialize, es renderizada en el widget",
        '<div data-sivWidget="Test.Widget" data-widgetCode="Test.Widget"><span data-sivValue="[%*message%]"></span></div>',
        '<div data-sivView="Test.Widget"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'Widget':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.message="Hello World! (2)"
                            },
                            initialize: function (params) {
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue2","El valor de sivValue es una ParametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable. Puede referenciar más de una, texto, y expresiones complejas.<br>"+
        "Las variables utilizadas en las expresiones, comienzan por el prefijo '*' cuando hacen referencia a una variable miembro de la clase asociada al widget",
        '<div data-sivWidget="Test.SivValue2" data-widgetCode="Test.SivValue2"><span data-sivValue="Esto <h2>[%*message1%]</h2> una <b><i>[%*message2%]</i></b>"></span></div>',
        '<div data-sivView="Test.SivValue2"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivValue2':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.message1="es"
                                this.message2="prueba"
                            },
                            initialize: function (params) {
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue3","El ciclo de vida de un widget es: llamada a preInitialize, renderizado, llamada a initialize. Esto significa que en preInitialize aun no se ha renderizado la plantilla, y todas las variables usadas en la plantilla, deben inicializarse. Cuando se llama a initialize, ya está creado el widget. Las variables usadas en el widget, están bindeadas a las variables de la clase.En este ejemplo, en initialize, se cambia una variable en un setInterval, actualizandose automáticamente la plantilla. En preInitialize, se inicializa la variable (es requerido para que exista al hacer el primer renderizado de la plantilla), y en initialize se crea el intervalo.",
        '<div data-sivWidget="Test.SivValue3" data-widgetCode="Test.SivValue3"><span data-sivValue="[%*counter%]"></span></div>',
        '<div data-sivView="Test.SivValue3"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivValue3':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.counter=0;
                            },
                            initialize: function (params) {
                                setInterval(function(){this.counter++}.bind(this),1000);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue4","Por defecto, sivValue establece el innerHTML de un campo.Es posible establecer otras parejas atributo-valor, separando cada pareja por ::, y el nombre y el valor por | ",
        '<style type="text/css">.simpleClass {font-weight:bold;color:green}</style><div data-sivWidget="Test.SivValue4" data-widgetCode="Test.SivValue4"><span data-sivValue="class|[%*assignedClass%]::title|El titulo es:[%*title%]">Contenido simple</span></div>',
        '<div data-sivView="Test.SivValue4"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivValue4':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.assignedClass="simpleClass";
                                this.title="Titulo asignado desde el widget";
                            },
                            initialize: function (params) {
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue5","las variables miembro de la clase, se pueden navegar como si fueran paths:",
        '<div data-sivWidget="Test.SivValue5" data-widgetCode="Test.SivValue5"><span data-sivValue="[%*test/0/key%]"></span></div>',
        '<div data-sivView="Test.SivValue5"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivValue5':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.test=[
                                    {
                                        key:"Hello world!"
                                    }
                                ]
                            },
                            initialize: function (params) {
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivValue6","Los paths, a su vez, pueden depender de otros paths.En este test, la variable usada en sivValue, es, a su vez, dependiente de otra variable, que va cambiando segun un intervalo.<br>"+
        "Los paths pueden comenzar opcionalmente por el carácter / , pero los paths anidados (que usan {%...%}, en vez de [%...%]) no permiten ese caracter / extra.",
        '<div data-sivWidget="Test.SivValue6" data-widgetCode="Test.SivValue6"><span data-sivValue="[%/*test/{%*index%}/key%]"></span></div>',
        '<div data-sivView="Test.SivValue6"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivValue6':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.index=0;
                                this.test=[
                                    {
                                        key:"Hello world!"
                                    },
                                    {
                                        key:"Second Message"
                                    }
                                ]
                            },
                            initialize: function (params) {
                                setInterval(function(){this.index=(this.index+1)%2;}.bind(this),1000);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivLoop","SivLoop atraviesa una variable iterable. En cada iteracion, establece una variable contextual (prefijo @), que apunta al elemento actual.",
        '<div data-sivWidget="Test.SivLoop" data-widgetCode="Test.SivLoop"><div data-sivLoop="/*simpleArray" data-contextIndex="current"><div data-sivValue="[%/@current%]"></div></div></div>',
        '<div data-sivView="Test.SivLoop"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivLoop':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.simpleArray=["cad1","cad2","cad3"];
                            },
                            initialize: function (params) {

                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivLoop2","Es posible anidar varios sivloops, utilizando el indice del externo como fuente.<br>En este ejemplo, el array interno itera sobre un dictionary.<br>Un loop, ademas de la variable de contexto (que apunta a los valores), tambien define la variable con el sufijo -index, que apunta a la key. ",
        '<div data-sivWidget="Test.SivLoop2" data-widgetCode="Test.SivLoop2">'+
        '<div data-sivLoop="*simpleArray" data-contextIndex="current"><div style="border:1px solid black">'+
        '<div data-sivLoop="/@current" data-contextIndex="inner"><div data-sivValue="[%/@inner-index%] : [%/@inner%]"></div>'+
        '</div></div></div></div></div>',
        '<div data-sivView="Test.SivLoop2"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivLoop2':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.simpleArray=[
                                    {a:"cad1",b:"cad2",c:"cad3"},
                                    {a:"cad4",b:"cad5",c:"cad6"},
                                    {a:"cad7",b:"cad8",c:"cad9"},

                                ];
                            },
                            initialize: function (params) {

                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivCall","SivCall, junto con sivparams, realiza una llamada al metodo especificado, que recibe como parametros tanto el nodo que contiene sivCall, como los parametros especificados.<br>"+
        "Estos parametros se especifican como un objeto json, y puede contener referencias a variables de clase y de contexto.<br>"+
        "Este ejemplo es el mismo anterior, estableciendo el contenido de los nodos usando SivCall, en vez de SivValue.<br>"+
        "(Nota: para especificar el json dentro de un atributo html, utilizo comillas simples para el atributo, de forma que no hay que escapear las comillas dobles del json)",
        '<div data-sivWidget="Test.SivCall" data-widgetCode="Test.SivCall">'+
        '<div data-sivLoop="*simpleArray" data-contextIndex="current"><div style="border:1px solid black">'+
        '<div data-sivLoop="/@current" data-contextIndex="inner"><div data-sivCall="setContent" data-sivparams=\'{"indice":"/@inner-index","valor":"/@inner"}\'></div>'+
        '</div></div></div></div></div>',
        '<div data-sivView="Test.SivCall"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivCall':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.simpleArray=[
                                    {a:"cad1",b:"cad2",c:"cad3"},
                                    {a:"cad4",b:"cad5",c:"cad6"},
                                    {a:"cad7",b:"cad8",c:"cad9"},

                                ];
                            },
                            initialize: function (params) {

                            },
                            setContent:function(node,params)
                            {
                                node.html(params.indice+" : "+params.valor);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivEvent","SivEvent, junto a sivcallback y sivParams, se utiliza para asignar un gestor de eventos."+
        "Aunque es posible asignar más de 1 evento, el callback y los parámetros son compartidos.<br>"+
        "El nombre de los eventos es el usado por jQuery, y, en caso de especificar más de uno, debe ir separado por comas.<br>",
        '<div data-sivWidget="Test.SivEvent" data-widgetCode="Test.SivEvent">'+
        '<div data-sivEvent="click" data-sivcallback="onclicked" data-sivparams=\'{"phrase":"An alert"}\'>Click aqui</div>'+
        '</div>',
        '<div data-sivView="Test.SivEvent"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivEvent':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                            },
                            initialize: function (params) {

                            },
                            onclicked:function(node,params)
                            {
                                alert(params.phrase);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivIf","SivIf evalua una expresion, y en caso de evaluar a true, renderiza el contenido de su tag."+
        "En este ejemplo, se alterna el valor de una variable, lo que alterna el contenido mostrado.<br>"+
            "la expresion del SivIf se evalua con eval de javascript, por lo que admite los condicionales de javascript",
        '<div data-sivWidget="Test.SivIf" data-widgetCode="Test.SivIf">'+
        '<div data-sivIf="[%/*flipper%] == 1"><div style="background-color:blue;color:white">Valor Uno</div></div>'+
        '<div data-sivIf="[%/*flipper%] == 0"><div style="background-color:green;color:white">Valor Cero</div></div>'+
        '</div>',
        '<div data-sivView="Test.SivIf"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SivIf':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.flipper=1;
                            },
                            initialize: function (params) {
                                setInterval(function(){this.flipper=(this.flipper+1)%2}.bind(this),1000);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivView","Desde un widget, es posible instanciar otros widgets usando SivView desde dentro de la plantilla.<br>"+
        "La plantilla padre puede pasar parámetros a las vistas hijas, usando sivParams.Estos parámetros se reciben en los métodos preInitialize e <br>"+
        "Los parámetros siguen bindeados, por lo que un cambio en las variables pasadas como parametros, provoca el repintado de las vistas.<br>" +
        "El siguiente ejemplo, pasa 2 variables (una de ellas, un valor fijo, y la otra, una variable bindeada del widget) a la vista hija.Se cambia el valor de la variable, y se refresca la vista hija.<br>"+
        "Primero se define las vistas hijas, y luego la vista padre.<br>",
        '<div data-sivWidget="Test.SubView" data-widgetCode="Test.SubView">'+
            '<div data-sivValue="[%*mensajeFijo%]"></div>'+
            '<div data-sivValue="[%*mensajeVariable%]"></div>'+
            '</div>'+
        '<div data-sivWidget="Test.SivViews" data-widgetCode="Test.SivViews">'+
        '<div data-sivView="Test.SubView" data-sivparams=\'{"fijo":"Texto Fijo","variable":"/*variable"}\'></div></div>',
        '<div data-sivView="Test.SivViews"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'SubView':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                                this.mensajeFijo=params.fijo;
                                this.mensajeVariable=params.variable;
                            },
                            initialize:function(params){}
                        }
                    },
                    'SivViews':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params) {
                                this.counter=1;
                                this.variable="Mensaje Variable";
                            },
                            initialize: function (params) {
                                setInterval(function(){this.variable="Mensaje Cambiado "+this.counter;this.counter=(this.counter+1)%2}.bind(this),1000);
                            }
                        }
                    }
                }

            })
        }
    )

    runTest("Transparencia de nodos","Los nodos html que contienen tags de tipo SivWidget, SivView o SivLoop, no estan incluidos en el DOM final.<br>"+
        "En el siguiente ejemplo, los nodos que contienen SivWidget,SivView y SivLoop, establecen colores de fondo, que, como se ve, no están en la salida.",
        '<div data-sivWidget="Test.Transp" data-widgetCode="Test.Transp" style="background-color:yellow">'+
        '<div data-sivLoop="/*anArray" data-contextIndex="current" style="background-color:blue"><div data-sivValue="[%@current%]"></div></div>'+
        '</div>',
        '<div data-sivView="Test.Transp" style="background-color:green"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'Transp':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                                this.anArray=["a","b","c"];
                            },
                            initialize:function(params){}
                        }
                    }
                }

            })
        }
    )

    runTest("Rootnode","Los nodos que componen un widget son accesibles a traves de la propiedad rootNode.<br>"+
        "RootNode contiene todos los nodos hijos del subwidget renderizado (es un objeto jQuery). Es por ello que no es accesible en preInitialize (aún no se ha renderizado el widget).<br>"+
        "En este ejemplo, se usa rootNode para encontrar los hijos, dentro del widget actual, que tienen una clase de estilo",
        '<div data-sivWidget="Test.RootNode" data-widgetCode="Test.RootNode"><div>'+
        '<div class="a">A</div><div class="b">B</div><div class="a">A</div></div>'+
        '</div>',
        '<div data-sivView="Test.RootNode" style="background-color:green"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'RootNode':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                            },
                            initialize:function(params){
                                $(".a",this.rootNode).css("background-color","blue");
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("SivId y ByCode","Los nodos que contienen el atributo data-sivId, se mapean a variables con el mismo nombre en la clase del widget <br>"+
        "Todos los ejemplos hasta ahora, han instanciado los widgets desde HTML, con un tag sivView. En este ejemplo, se instancia una vista a traves de código.<br>"+
        "Los parámetros recibidos son: 1)Nombre de la template, 2)Parametros (recibidos en preInitialize), 3)Bloques (actualmente sin uso), 4)Placeholder (establer a un div vacio), 5)instancia de Siviglia.Path.ContextStack<br>"+
            "En el ejemplo, se crea una instancia del widget Test.Sample, dentro del nodo identificado por sivId=here<br>"+
            "Una vez creada la instancia, se llama a su metodo __build, que devuelve una promesa.El widget estará construido cuando la promesa se resuelva<br>"+
        "Un punto importante, es que los widgets creados desde código, deben ser destruidos cuando no son necesarios (en este caso, se hace en el destruct del propio widget)",
        '<div data-sivWidget="Test.Sample" data-widgetCode="Test.Sample"><b data-sivValue="[%*receivedParam%]"></b></div>'+
        '<div data-sivWidget="Test.ByCode" data-widgetCode="Test.ByCode">'+
        '<div data-sivId="here" style="background-color:yellow"></div>'+
        '</div>',
        '<div data-sivView="Test.ByCode" style="background-color:green"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'Sample':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                                this.receivedParam=params.sentParam;
                                console.dir(params);
                            },
                            initialize:function(params){
                            }
                        }
                    },
                    'ByCode':{
                        inherits: "Siviglia.UI.Expando.View",
                        destruct:function()
                        {
                            if(this.innerWidget)
                                this.innerWidget.destruct();
                        },
                        methods:{
                            preInitialize:function(params)
                            {
                                this.innerWidget=null;
                            },
                            initialize:function(params){

                                this.innerWidget=new Test.Sample("Test.Sample",{sentParam:"Texto Enviado"},{},
                                    $("<div></div>"),
                                    new Siviglia.Path.ContextStack()
                                );

                                this.innerWidget.__build().then(function(instance){
                                    this.here.append(this.innerWidget.rootNode);
                                }.bind(this));
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("Promesas en preInitialize","En muchas ocasiones, las variables necesarias para crear un widget, no estan disponibles al llamar a preInitialize. <br>"+
        "Cuando el contenido de un Widget depende, por ejemplo, de una llamada ajax, y hay que esperar a que ésta llamada termine, desde preInitialize se retorna una promesa.<br>"+
        "El widget se procesará, cuando la promesa se haya resuelto.<br>"+
        "En el ejemplo, se utiliza un setTimeout() para simular la llamada ajax.",
        '<div data-sivWidget="Test.Promise" data-widgetCode="Test.Promise">'+
        '<div data-sivValue="[%*timerValue%]"></div>'+
        '</div>',
        '<div data-sivView="Test.Promise" style="background-color:green"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'Promise':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                                var returnedPromise=$.Deferred();
                                setTimeout(function(){
                                    this.timerValue="Timer finalizado";
                                    returnedPromise.resolve();
                                }.bind(this),3000);
                                return returnedPromise;
                            },
                            initialize:function(params){
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("BaseTypedObject y Widgets","La API de tipos, y los API de widgets, son independientes.Una y otra se pueden usar por separado.<br>"+
        "Cuando se combinan, desde el punto de vista del Widget, es una variable cualquiera, y se accede de la misma forma.<br>"+
        "Como cualquier otro BaseTypedObject, hay que llamar a destruct cuando ya no son necesarios.<br>"+
        "En este ejemplo, se define un BTO en el preInitialize, y se le asigna un valor, usado al renderizar el widget.<br>"+
        "En el initialize, se modifica el valor del BTO, y, en el destructor del widget, se llama al destructor del BTO.",
        '<div data-sivWidget="Test.BTO" data-widgetCode="Test.BTO">'+
        '<div data-sivLoop="*bto/arrField" data-contextIndex="current">' +
            '<div data-sivValue="[%/@current/fieldA%]"></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.BTO" style="background-color:green"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'BTO':{
                        inherits: "Siviglia.UI.Expando.View",
                        destruct:function()
                        {
                            this.bto.destruct();
                        },
                        methods:{
                            preInitialize:function(params)
                            {
                                this.bto=new Siviglia.model.BaseTypedObject({
                                    "FIELDS":{
                                        "arrField":{
                                            "TYPE":"Array",
                                            "ELEMENTS":{
                                                "TYPE":"Container",
                                                "FIELDS":{
                                                    "fieldA":{"TYPE":"String"}
                                                }
                                            }
                                        }
                                    }
                                });
                                this.bto.setValue({arrField:[{fieldA:"Campo1"},
                                        {fieldA:"Campo2"},
                                        {fieldA:"Campo3"}]})
                            },
                            initialize:function(params){
                                setTimeout(function(){
                                    this.bto.arrField.push({fieldA:"Added"});
                                }.bind(this),1000);
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("Layouts alternativos","Cuando se instancia una vista de un widget, es posible especificar un layout alternativo, con el atributo data-sivLayout. <br>"+
            "Se especifican varios layouts, todos apuntando al mismo data-widgetCode.<br>"+
        "Esta característica es uno de los pilares del sistema de widgets.Significa una separación entre la gestión, en código, de un widget, y el html que lo renderiza.<br>"+
        "El separar las clases de los layouts, permite que haya varias formas de mostrar un mismo elemento conceptual.<br>"+
        "En este ejemplo, se utiliza un mismo widget, simulando un menu, que se muestra usando varios layouts diferentes.",
        '<div data-sivWidget="Test.AltLayout" data-widgetCode="Test.AltLayout">'+
            '<ul>'+
            '<div data-sivLoop="/*options" data-contextIndex="current">'+
            '<li><a data-sivValue="href|[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a></li>'+
            '</div>'+
            '</ul>'+
        '</div>'+
        '<div data-sivWidget="Test.AltLayout2" data-widgetCode="Test.AltLayout">'+
            '<div>'+
        '<div data-sivLoop="/*options" data-contextIndex="current">'+
        '<div style="float:left;border-radius:8px;background-color:green;color:white;padding:4px;margin-right:3px"><a data-sivValue="href|/[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a></div>'+
        '</div></div><div style="clear:both"></div></div>',
        '<div data-sivView="Test.AltLayout"></div><div style="height:10px"></div><div data-sivView="Test.AltLayout" data-sivLayout="Test.AltLayout2"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'AltLayout':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods:{
                            preInitialize:function(params)
                            {
                              this.options=[
                                  {"link":"http://elmundo.es","text":"ElMundo"},
                                  {"link":"http://cnn.com","text":"CNN"},
                                  {"link":"http://wwww.google.com","text":"Google"}
                              ];
                            },
                            initialize:function(params){
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("BTO Remoto","Si la definicion del BTO es remota, es equivalente a cualquier llamada Ajax.<br>"+
        "Esto significa, retornar una promesa de preInitialize, y resolverla cuando el BTO esté listo.<br>"+
        "Un BTO remoto puede ser un formulario, o la salida de un datasource.En este caso se utiliza un datasource, y se itera sobre el resultado.<br>"+
            "La llamada a unfreeze() del datasource, dispara la request y retorna una promesa, que es la que es devuelva en preInitialize",
        '<div data-sivWidget="Test.BTO2" data-widgetCode="Test.BTO2">'+
            '<div data-sivLoop="/*ds/data" data-contextIndex="current">'+
            '<div style="border:1px solid black;margin:2px">'+
            '<div data-sivValue="[%/@current/id_site%] : [%/@current/host%]"></div>'+
            '</div>'+
            '</div>'+
        '</div>',
        '<div data-sivView="Test.BTO2" style="background-color:green"></div>' ,
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'BTO2':{
                        inherits: "Siviglia.UI.Expando.View",
                        destruct:function()
                        {
                            this.instance.destruct();
                        },
                        methods:{
                            preInitialize:function(params)
                            {
                                this.ds=new Siviglia.Model.DataSource("/model/web/Site","FullList",{});
                                this.ds.freeze();
                                this.ds.settings.__start=0;
                                this.ds.settings.__count=5;
                                return this.ds.unfreeze();
                            },
                            initialize:function(params){
                            }
                        }
                    }
                }

            })
        }
    )
    runTest("Factorias","Este ejemplo utiliza la funcion stringToContextAndObject para crear una pequeña factoria de widgets.<br>"+
        "Esta factoria simple, utiliza el nombre del tipo del BTO, para instanciar un widget que renderiza ese tipo.<br>"+
        "Se declara una clase base de renderizado de tipos, de las que deriva una clase para renderizar Strings, otra para renderizar Integer, y en el widget principal, se declara un BTO con esos tipos.<br>"+
        "Se itera sobre los campos, y,usando sivCall, se crean las instancias de los widgets asociados al tipo.<br>"+
        "Como nota adicional, los widgets creados por el widget Factoria, se almacenan en una variable, y se destruyen en el destruct(), junto con el bto.<br>",
        '<div data-sivWidget="Test.Factory" data-widgetCode="Test.Factory">'+
        '<div data-sivLoop="/*mybto/__definition/FIELDS" data-contextIndex="current">'+
        '<div data-sivCall="getTypeWidget" data-sivParams=\'{"field":"@current-index","type":"@current/TYPE"}\'></div>'+
           // '<div data-sivValue="[%@current/TYPE%]"></div>'+
        '</div>'+
        '</div>'+
        '<div data-sivWidget="Test.Integer" data-widgetCode="Test.Integer"><div style="background-color:green" data-sivValue="Entero: [%/*currentValue%]"></div></div>'+
        '<div data-sivWidget="Test.String" data-widgetCode="Test.String"><div style="background-color:yellow" data-sivValue="Cadena: [%/*currentValue%]"></div></div>'
        ,
        '<div data-sivView="Test.Factory"></div>' ,
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    'TypeRenderer':{
                        inherits: "Siviglia.UI.Expando.View",
                        methods: {
                            preInitialize: function (params){
                                this.currentValue=params.field.getValue();
                            },
                            initialize:function(params){}
                        }
                    },
                    'Integer': {
                        inherits: "Test.TypeRenderer"
                    },
                    'String': {
                        inherits:"Test.TypeRenderer"
                    },
                    'Factory':{
                        inherits: "Siviglia.UI.Expando.View",
                        destruct:function()
                        {
                            this.mybto.destruct();
                            for(var k in this.createdWidgets)
                                this.createdWidgets[k].destruct();
                        },
                        methods:{

                            preInitialize:function(params)
                            {
                                this.mybto=new Siviglia.model.BaseTypedObject({
                                    "FIELDS":{
                                        "textField":{
                                            "TYPE":"String"
                                            },
                                        "integerField":{
                                            "TYPE":"Integer"
                                        }
                                    }
                                });
                                this.mybto.setValue({"textField":"Campo de texto","integerField":11223344})
                                this.createdWidgets=[];
                            },
                            initialize:function(params){

                            },
                            getTypeWidget:function(node,params)
                            {
                                var field=this.mybto["*"+params.field];
                                var type=params.type;
                                var widgetName="Test."+type;
                                var ctx=Siviglia.Utils.stringToContextAndObject(widgetName);
                                var targetWidget=new ctx.context[ctx.object](widgetName,{field:field},{},$("<div></div>"),new Siviglia.Path.ContextStack());
                                targetWidget.__build().then(function(instance){
                                    node.append(instance.rootNode);
                                })
                                 this.createdWidgets[params.field]=targetWidget;

                            }
                        }
                    }
                }

            })
        }
    )
    runTest("Inputs Simples","Primera prueba de inputs de JqxWidgets.<br>"+
        "Se prueban los inputs sobre campos simples.Se usa una clase derivada de Form.Form, a su vez, deriva de Container.La funcion getInput que es llamada desde la plantilla, esta definida en Container.<br>",
        '<div data-sivWidget="Test.Input1" data-widgetParams="" data-widgetCode="Test.Input1">'+
        '<div class="type">'+
        '<div class="label">Cadena</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"stringType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Enum</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"enumType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Entero</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"integerType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Decimal</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"decimalType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Texto</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"textType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Boolean</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"booleanType"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input1"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input1": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        "stringType":
                                            {
                                                TYPE: "String",
                                                MINLENGTH: 3,
                                                LABEL: "Hola",
                                                HELP: "La ayuda"
                                            },
                                        enumType: {
                                            "LABEL":"EnumType",
                                            TYPE: "Enum",
                                            VALUES: ["One", "Two", "Three"],
                                            LABEL: "MiEnum"
                                        },
                                        integerType: {
                                            LABEL:"IntegerType",
                                            TYPE: "Integer",
                                            MAX: 1000
                                        },
                                        decimalType: {
                                            LABEL:"DecimalType",
                                            TYPE: "Decimal",
                                            NINTEGERS: 5,
                                            NDECIMALS: 2
                                        },
                                        textType: {
                                            LABEL:"textType",
                                            TYPE: "Text"
                                        },
                                        booleanType: {
                                            LABEL:"BooleanType",
                                            TYPE: "Boolean"
                                        }
                                    }
                                });
                                this.typedObj.stringType = "abcde";
                                this.typedObj.enumType = "Two";
                                this.typedObj.integerType = 10;
                                this.typedObj.decimalType = 8.3;
                                this.typedObj.textType = "Esta es una prueba";
                                this.typedObj.booleanType = true;
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Inputs Simples","Prueba parcial de errores.Se establece un valor invalido, y se ve si el input inmediatamente muestra el error.<br>",

        '<div data-sivWidget="Test.Input1" data-widgetParams="" data-widgetCode="Test.Input1">'+
        '<div class="type">'+
        '<div class="label">Cadena</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"stringType"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input1"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input1": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        "stringType":
                                            {
                                                TYPE: "String",
                                                MINLENGTH: 3,
                                                LABEL: "Hola",
                                                HELP: "La ayuda"
                                            }
                                    }
                                });
                                try {
                                    this.typedObj.stringType = "ab";
                                }catch(e)
                                {} // Aqui ignoramos la excepcion, queremos que se pinte el input con el error.
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Inputs Simples","Segunda prueba de JqxWidgets<br>"+
        "Se prueba un entero con un source, y una serie de campos enteros y de cadena, con sources enlazados, de forma que unos dependen de otros. .<br>"+
            "",
        '<div data-sivWidget="Test.Input2" data-widgetParams="" data-widgetCode="Test.Input2">'+
        '<div class="type">'+
        '<div class="label">Combo con source Array</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"comboType"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Combo enlazado 1, con source Array</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"comboType2a"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Combo enlazado 2 (dependiente) con source Array</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"comboType2b"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">Combo enlazado 3 (dependiente de 2) con source Array</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"comboType2c"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input2"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input2": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        comboType: {
                                            LABEL:"ComboType",
                                            "TYPE": "Integer",
                                            "SOURCE": {
                                                "TYPE": "Array",
                                                "DATA": [
                                                    {"a": 1, "message": "Opcion 1"},
                                                    {"a": 2, "message": "Opcion 2"},
                                                    {"a": 3, "message": "Opcion 3"},
                                                    {"a": 4, "message": "Opcion 4"},
                                                    {"a": 5, "message": "Opcion 5"},
                                                    {"a": 6, "message": "Opcion 6"},
                                                    {"a": 7, "message": "Opcion 7"}
                                                ],
                                                "LABEL": "message",
                                                "VALUE": "a"
                                            }
                                        },
                                        comboType2a: {
                                            "LABEL":"Combo2",
                                            "TYPE": "String",
                                            "SOURCE": {
                                                "TYPE": "Array",
                                                "DATA": [
                                                    {"val": "one", "label": "Sel one"},
                                                    {"val": "two", "label": "Sel two"}
                                                ],
                                                "LABEL": "label",
                                                "VALUE": "val"
                                            }
                                        },

                                        comboType2b: {
                                            "LABEL":"ComboType2B",
                                            "TYPE": "Integer",
                                            "SOURCE": {
                                                "TYPE": "Array",
                                                "DATA":
                                                    {
                                                        "one": [
                                                            {"a": 1, "message": "Opcion 1"},
                                                            {"a": 2, "message": "Opcion 2"},
                                                        ],
                                                        "two": [
                                                            {"a": 10, "message": "xxOpcion 1"},
                                                            {"a": 11, "message": "xxOpcion 2"},
                                                        ]
                                                    }
                                                ,
                                                "LABEL": "message",
                                                "VALUE": "a",
                                                "PATH": "/{%#../comboType2a%}"

                                            }
                                        },

                                        comboType2c:
                                            {
                                                "LABEL":"ComboType2c",
                                                "TYPE": "Integer",
                                                "SOURCE": {
                                                    "TYPE": "Array",
                                                    "DATA":
                                                        {
                                                            1: [{"a": 20, "message": "Third - 1 - 1"}, {
                                                                "a": 21,
                                                                "message": "Third - 1 - 2"
                                                            }],
                                                            2: [{"a": 22, "message": "Third - 2 - 1"}, {
                                                                "a": 23,
                                                                "message": "Third - 2 - 2"
                                                            }],
                                                            10: [{"a": 24, "message": "Third - 3 - 1"}, {
                                                                "a": 25,
                                                                "message": "Third - 3 - 2"
                                                            }],
                                                            11: [{"a": 26, "message": "Third - 4 - 1"}, {
                                                                "a": 27,
                                                                "message": "Third - 4 - 2"
                                                            }]
                                                        }
                                                    ,
                                                    "LABEL": "message",
                                                    "VALUE": "a",
                                                    "PATH": "/{%#../comboType2b%}"
                                                }
                                            },
                                    }
                                });
                                this.typedObj.stringType = "abcde";
                                this.typedObj.enumType = "Two";
                                this.typedObj.integerType = 10;
                                this.typedObj.decimalType = 8.3;
                                this.typedObj.textType = "Esta es una prueba";
                                this.typedObj.booleanType = true;
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Inputs Simples","Tercera prueba de JqxWidgets<br>"+
        "Se prueban 2 cadenas dependientes, con source remoto<br>"+
        "",
        '<div data-sivWidget="Test.Input3" data-widgetParams="" data-widgetCode="Test.Input3">'+
        '<div class="type">'+
        '<div class="label">String con source DATASOURCE (MODEL LIST)</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"modelSelector"}\'></div>'+
        '</div>'+
        '<div class="type">'+
        '<div class="label">String con source DATASOURCE enlazado (MODEL FIELDS)</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"fieldSelector"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input3"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input3": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        modelSelector:
                                            {
                                                "LABEL":"ModelSelector",
                                                "TYPE": "String",
                                                "SOURCE": {
                                                    "TYPE": "DataSource",
                                                    "MODEL": "/model/reflection/Model",
                                                    "DATASOURCE": "ModelList",
                                                    "LABEL": "smallName",
                                                    "VALUE": "smallName"
                                                }
                                            },

                                        fieldSelector:
                                            {
                                                "LABEL":"FieldSelector",
                                                "TYPE": "String",
                                                "SOURCE": {
                                                    "TYPE": "DataSource",
                                                    "MODEL": "/model/reflection/Model",
                                                    "DATASOURCE": "FieldList",
                                                    "PARAMS": {
                                                        "model": "[%#../modelSelector%]"
                                                    },
                                                    "LABEL": "NAME",
                                                    "VALUE": "FIELD"
                                                }
                                            }
                                    }
                                });

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Input de Container","Test de input para el tipo Container<br>",
        '<div data-sivWidget="Test.Input4" data-widgetParams="" data-widgetCode="Test.Input4">'+
        '<div class="type">'+
        '<div class="label">Container:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleContainer"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input4"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input4": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleContainer:
                                            {
                                                "LABEL":"SimpleContainer",
                                                "TYPE": "Container",
                                                "FIELDS": {
                                                    "Field1": {
                                                        "LABEL": "Field 1",
                                                        "TYPE": "String"
                                                    },
                                                    "Field2": {
                                                        "LABEL": "Field 2",
                                                        "TYPE": "Integer"
                                                    }
                                                }
                                            }
                                    }
                                });
                                this.typedObj.simpleContainer={"Field1":"AAA","Field2":555};

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Input de Container: Uso de INPUTPARAMS","Mismo test anterior, pero utilizando INPUTPARAMS para sobreescribir el widget utilizado para el input Container<br>"+
            "Un formulario puede parametrizar los inputs por defecto, o parametrizarlos, usando el campo INPUTPARAMS, con el path a los inputs que se quieren parametrizar",
        '<div data-sivWidget="Test.Input5" data-widgetParams="" data-widgetCode="Test.Input5">'+
        '<div class="type">'+
        '<div class="label">Container:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleContainer"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input5"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input5": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleContainer:
                                            {
                                                "LABEL":"SimpleContainer",
                                                "TYPE": "Container",
                                                "FIELDS": {
                                                    "Field1": {
                                                        "LABEL": "Field 1",
                                                        "TYPE": "String"
                                                    },
                                                    "Field2": {
                                                        "LABEL": "Field 2",
                                                        "TYPE": "Integer"
                                                    }
                                                }
                                            }
                                    },
                                    "INPUTPARAMS":{
                                        "/simpleContainer":{
                                            "INPUT": "GridContainer",
                                            "JQXPARAMS":{width:700,height:500}
                                        }
                                    }
                                });
                                this.typedObj.simpleContainer={"Field1":"AAA","Field2":555};

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Input de Dictionary","Input de Dictionary por defecto.<br>",

        '<div data-sivWidget="Test.Input6" data-widgetParams="" data-widgetCode="Test.Input6">'+
        '<div class="type">'+
        '<div class="label">Dictionary:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleDictionary"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input6"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input6": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleDictionary:
                                            {
                                                LABEL:"SimpleDictionary",
                                                "TYPE": "Dictionary",
                                                "VALUETYPE": {
                                                    "TYPE": "Container",
                                                    "FIELDS": {
                                                        "Field1": {
                                                            "LABEL": "Field 1",
                                                            "TYPE": "String"
                                                        },
                                                        "Field2": {
                                                            "LABEL": "Field 2",
                                                            "TYPE": "Integer"
                                                        }
                                                    }
                                                }
                                            }
                                    }
                                });
                                this.typedObj.simpleDictionary=
                                    {
                                        aa:{"Field1":"AAA","Field2":555},
                                        bb:{"Field1":"ZZZ","Field2":666}
                                    };

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Input de Dictionary (Tipos simples)","Input de Dictionary por defecto.<br>",

        '<div data-sivWidget="Test.Input7" data-widgetParams="" data-widgetCode="Test.Input7">'+
        '<div class="type">'+
        '<div class="label">Dictionary:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleDictionary"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input7"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input7": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleDictionary:
                                            {
                                                LABEL:"SimpleDictionary",
                                                "TYPE": "Dictionary",
                                                "VALUETYPE": {
                                                    "TYPE": "String"
                                                }
                                            }
                                    }
                                });
                                this.typedObj.simpleDictionary=
                                    {
                                        aa:"AAA",
                                        bb:"ZZZ"
                                    };

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )
    runTest("Input de Array (Tipos simples)","Input de Array por defecto, cuando los elementos son tipos simples.<br>",

        '<div data-sivWidget="Test.Input8" data-widgetParams="" data-widgetCode="Test.Input8">'+
        '<div class="type">'+
        '<div class="label">Array:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleArray"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input8"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input8": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleArray:{
                                            "LABEL":"SimpleArray",
                                            "TYPE":"Array",
                                            "ELEMENTS":{
                                                "LABEL": "Field 1",
                                                "TYPE": "String"
                                            }
                                        }
                                    }
                                });
                                this.typedObj.simpleArray=
                                    [
                                        "AAA", "ZZZ"
                                    ];

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Input de Array (Tipos compuestos)","Input de Array por defecto, cuando los elementos son tipos complejos.<br>",

        '<div data-sivWidget="Test.Input9" data-widgetParams="" data-widgetCode="Test.Input9">'+
        '<div class="type">'+
        '<div class="label">Array:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleArray"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input9"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input9": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleArray:{
                                            "LABEL":"SimpleArray",
                                            "TYPE":"Array",
                                            "ELEMENTS":{
                                                "TYPE": "Container",
                                                "FIELDS": {
                                                    "Field1": {
                                                        "LABEL": "Field 1",
                                                        "TYPE": "String"
                                                    },
                                                    "Field2": {
                                                        "LABEL": "Field 2",
                                                        "TYPE": "Integer"
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                                this.typedObj.simpleArray=
                                    [
                                        {"Field1":"AAA","Field2":25},{"Field1":"ZZZ","Field2":30}
                                    ];

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Input de TypeSwitcher Basado en campo Tipo","En este typeswitcher, el valor del campo viene determinado por la existencia de un campo de tipo (en este caso, TYPE)<br>",

        '<div data-sivWidget="Test.Input10" data-widgetParams="" data-widgetCode="Test.Input10">'+
        '<div class="type">'+
        '<div class="label">Type Switcher:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleTypeSwitcher"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input10"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input10": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleTypeSwitcher:
                                            {
                                                "LABEL":"TypeSwitcher",
                                                "TYPE": "TypeSwitcher",
                                                "TYPE_FIELD": "TYPE",
                                                "ALLOWED_TYPES": {
                                                    "TYPE_ONE": {
                                                        "TYPE": "String"
                                                    },
                                                    "TYPE_TWO": {
                                                        "TYPE": "Container",
                                                        "FIELDS": {
                                                            "Field1": {
                                                                "LABEL": "Field 1",
                                                                "TYPE": "String"
                                                            },
                                                            "Field2": {
                                                                "LABEL": "Field 2",
                                                                "TYPE": "Integer"
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                    }
                                });
                                this.typedObj.simpleTypeSwitcher={"TYPE":"TYPE_TWO","Field1":"AAA","Field2":77};
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Formulario de edicion de modelo remoto (I)","En este ejemplo, la plantilla y clase del formulario se declara localmente, pero se inicializa el formulario indicando qué formulario, y qué campos indice hay que cargar del servidor.<br>",

       '<div data-sivWidget="Test.Edit1" data-widgetCode="Test.Edit1">\n' +
        '        <div><div class="label">Nombre</div>\n' +
        '            <div data-sivCall="getInputFor" data-sivParams=\'{"key":"name"}\'></div>\n' +
        '        </div>\n' +
        '        <div><div class="label">Tag</div>\n' +
        '            <div data-sivCall="getInputFor" data-sivParams=\'{"key":"tag"}\'></div>\n' +
        '        </div>\n' +
        '        <div><div class="label">Site</div>\n' +
        '        <div data-sivCall="getInputFor" data-sivParams=\'{"key":"id_site"}\'></div>\n' +
        '        </div>\n' +
        '        <div><div class="label">Private</div>\n' +
        '        <div data-sivCall="getInputFor" data-sivParams=\'{"key":"isPrivate"}\'></div>\n' +
        '        </div>\n' +
        '        <div><div class="label">Path</div>\n' +
        '        <div data-sivCall="getInputFor" data-sivParams=\'{"key":"path"}\'></div>\n' +
        '        </div>\n' +
        '        <div><input type="button" data-sivEvent="click" data-sivCallback="submit" value="Guardar"></div>\n' +
        '    </div>\n' +
        '</div>\n',
        '<div data-sivView="Test.Edit1" data-sivParams=\'{"id_page":2}\'></div>',
        function(){
        Siviglia.Utils.buildClass({
            "context":"Test",
            "classes":{
                Edit1:{
                    "inherits":"Siviglia.inputs.jqwidgets.Form",
                    "methods":{
                        preInitialize:function(params)
                        {
                            var p={
                                "keys":params,
                                "model":"/model/web/Page",
                                "form":"Edit"
                            }
                            return this.Form$preInitialize(p);
                        }
                    }
                }
            }
        });
        }
    )

    runTest("Formulario de edicion de modelo remoto (II)","En este ejemplo, tanto formulario como los datos se cargan remotamente.Es por eso que no hay plantilla,ni clase, y el namespace del formulario es el que espera el servidor.<br>",

        '',
        '<div data-sivView="Siviglia.model.web.Page.forms.Edit" data-sivParams=\'{"id_page":2}\'></div>',
        function(){
        }
    )

    runTest("Test de inputs con estado","Se prueba un bto con containers con una especificacion de estado.<br>",

        '<div data-sivWidget="Test.Input11" data-widgetParams="" data-widgetCode="Test.Input11">'+
        '<div class="type">'+
        '<div class="label">Type Switcher:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"cont1"}\'></div>'+
        '</div>'+
        '</div>',
        '<div data-sivView="Test.Input11"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input11": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject(
                                    {
                                    "FIELDS": {
                                        cont1:
                                            {
                                                "LABEL":"Container",
                                                "TYPE":"Container",
                                                "FIELDS":{
                                                    "one":{"TYPE":"String","LABEL":"one"},
                                                    "two":{"TYPE":"String","LABEL":"two"},
                                                    "three":{"TYPE":"String","LABEL":"three"},
                                                    "state":{"TYPE":"State","VALUES":["E1","E2","E3"],"DEFAULT":"E1","LABEL":"State"}
                                                },
                                                'STATES' : {
                                                    'STATES' : {
                                                        'E1' : {
                                                            'FIELDS' : {'EDITABLE' : ['one','two']}
                                                        },
                                                        'E2' : {
                                                            'ALLOW_FROM':["E1"],
                                                            'FIELDS' : {'EDITABLE' : ['two','three']}
                                                        },
                                                        'E3' : {
                                                            'ALLOW_FROM':["E2"],
                                                            'FINAL':true,
                                                            'FIELDS' : {'REQUIRED' : ['three']}}
                                                    },
                                                    'FIELD' : 'state'
                                                }
                                            },

                                    }
                                });
                                this.typedObj.cont1={"TYPE":"TYPE_TWO","Field1":"AAA","Field2":77};
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                            },
                            show: function () {
                            },

                        }
                    }

                }

            })
        }
    )

    runTest("Container y valores por defecto.","Un container cuyos campos no son tocados, y solo tienen los valores por defecto de los camos, deberia seguir teniendo valor nulo.",
        '<div data-sivWidget="Test.DefCont" data-widgetParams="" data-widgetCode="Test.DefCont">'+
        '<div class="type">'+
        '<div class="label">Container:</div>'+
        '<div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"simpleContainer"}\'></div>'+
        '</div>'+
        '<input type="button" data-sivEvent="click" data-sivCallback="doSubmit" ></input>'+
        '</div>',
        '<div data-sivView="Test.DefCont"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "DefCont": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleContainer:
                                            {
                                                "LABEL":"SimpleContainer",
                                                "TYPE": "Container",
                                                "FIELDS": {
                                                    "Field1": {
                                                        "LABEL": "Container 1",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f1",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f2",
                                                                "TYPE": "String",
                                                            }
                                                        }

                                                    },
                                                    "Field2": {
                                                        "LABEL": "Container 2",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f3",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f4",
                                                                "TYPE": "String",
                                                            }
                                                        }
                                                    },
                                                    "Field3": {
                                                        "LABEL": "Container 3",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f5",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f6",
                                                                "TYPE": "String",
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                    },
                                    "INPUTPARAMS":{
                                        "/simpleContainer":{
                                            "INPUT": "ByFieldContainer",
                                        }
                                    }
                                });
                                this.typedObj.setValue({simpleContainer:{Field1:{f1:"aaa"}}})

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            doSubmit:function()
                            {
                                this.typedObj.save();
                                console.dir(this.typedObj.getPlainValue());
                            }

                        }
                    }

                }

            })
        }
    );

    runTest("Listado derivado de BaseGrid","Test de Widget de Listado,directamente dereivado de BaseGrid con datasource remoto,filtros, y subwidgets",
        '<div data-sivWidget="Test.ListViewerForm" data-widgetCode="Test.ListViewerForm">\n' +
        '        <div class="input">\n' +
        '            <div class="label">Id</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"id_page"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">Tag</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"tag"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">id_site</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"id_site"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">name</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"name"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">endDateTime</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"endDateTime"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">LineItemType</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"lineItemType"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">Status</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"status"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">isArchived</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"isArchived"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">isMissingCreatives</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"isMissingCreatives"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">userConsentEligibility</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"userConsentEligibility"}\'></div>\n' +
        '        </div>\n' +
        '        <div class="input">\n' +
        '            <div class="label">remoteId</div>\n' +
        '            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams=\'{"key":"remoteId"}\'></div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '\n' +
        '    <div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer">\n' +
        '    </div>\n' +
        '\n' +
        '    <div data-sivWidget="Test.ButtonList" data-widgetCode="Test.ButtonList">\n' +
        '        <div>\n' +
        '            <input type="button" value="Borrar" data-sivEvent="click" data-sivCallback="onClicked">\n' +
        '        </div>\n' +
        '    </div>',
        '<div data-sivView="Test.DefCont"></div>',
        function(){
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "DefCont": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {

                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                /* STRING **************************/
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                        simpleContainer:
                                            {
                                                "LABEL":"SimpleContainer",
                                                "TYPE": "Container",
                                                "FIELDS": {
                                                    "Field1": {
                                                        "LABEL": "Container 1",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f1",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f2",
                                                                "TYPE": "String",
                                                            }
                                                        }

                                                    },
                                                    "Field2": {
                                                        "LABEL": "Container 2",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f3",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f4",
                                                                "TYPE": "String",
                                                            }
                                                        }
                                                    },
                                                    "Field3": {
                                                        "LABEL": "Container 3",
                                                        "TYPE":"Container",
                                                        "FIELDS": {
                                                            "f1":{
                                                                "LABEL":"f5",
                                                                "TYPE": "String",
                                                                "DEFAULT":"ssss"
                                                            },
                                                            "f2":{
                                                                "LABEL":"f6",
                                                                "TYPE": "String",
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                    },
                                    "INPUTPARAMS":{
                                        "/simpleContainer":{
                                            "INPUT": "ByFieldContainer",
                                        }
                                    }
                                });
                                this.typedObj.setValue({simpleContainer:{Field1:{f1:"aaa"}}})

                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            doSubmit:function()
                            {
                                this.typedObj.save();
                                console.dir(this.typedObj.getPlainValue());
                            }

                        }
                    }

                }

            })
        }
    )




</script>
<script>
    checkTests();
</script>


</body>
</html>
