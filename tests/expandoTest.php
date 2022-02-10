<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WTests (Siviglia-js)</title>
    <!-- para quitar error consola GET http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css net::ERR_ABORTED 404 (Not Found) -->
    <!-- <link rel='stylesheet prefetch' href='http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css'> -->
    <!-- <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'> -->
    <script src='/packages/d3/d3.min.js'></script>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../Siviglia.js"></script>
    <script src="../SivigliaStore.js"></script>

    <script src="../SivigliaTypes.js"></script>
    <script src="../Model.js"></script>


    <script src="../../jqwidgets/jqx-all.js"></script>
    <script src="../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="/reflection/css/style.css">
    <link rel="stylesheet" href="../jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" href="../jQuery/css/jqx.base.css">
    <link rel="stylesheet" href="../jQuery/css/jqx.adtopy-dev.css">
    <link rel="stylesheet" href="/backend/css/style.css">

    <!-- <link rel="stylesheet" href="../../jqwidgets/styles/jqx.base.css"> -->
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
            height:500px;
            overflow-y:scroll;
        }
        .testDef {
            width:700px;
            float:left;
            height:500px;
            overflow:auto
        }
        .testView {
            width:100px;
            float:left;
            height:500px;
            overflow:auto
        }
        .testCode {
            height:500px;
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
        path.link {
            fill: #ccc;
            stroke: #333;
            stroke-width: 1.5px;
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
<body style="background-color:#EEE; background-image:none;">
<?php include_once("../jQuery/JqxWidgets.html"); ?>
<?php include_once("../jQuery/JqxLists.html"); ?>
<?php include_once("../jQuery/Visual.html");?>


<script>
  var urlParams = new URLSearchParams(window.location.search);
  var DEVELOP_MODE;
  if (!urlParams.has("test")) {
    //DEVELOP_MODE=47;    // Specific test number
    //DEVELOP_MODE=0;     // All tests
    DEVELOP_MODE=(-1);  // Latest test
  } else {
    DEVELOP_MODE = parseInt(urlParams.get("test"));
  }
  var TEST_DESTROY=true;
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
  function countManagers() {
    var s = 0;
    for (var k in Siviglia.Dom.existingManagers)
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
  Siviglia.debug=true;
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
        // divCont.style.height="250px";
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
        if(TEST_DESTROY===true && DEVELOP_MODE!==0)
        {
          var onClicked=function(){
            var nListeners=countListeners();
            var nManagers=countManagers();
            var nDiv=document.createElement("div");
            nDiv.innerHTML="Listeners:<b>"+nListeners+"</b> Managers:<b>"+nManagers+"</b>";
            parser.destruct();
            nListeners=countListeners();
            nManagers=countManagers();

            nDiv.innerHTML+="<br>Despues:<br>Listeners:<b>"+nListeners+"</b> Managers:<b>"+nManagers+"</b>";
            document.body.prependChild(nDiv);
            if(nManagers > 0)
            {
              for(var k in Siviglia.Dom.existingManagers)
              {
                console.log("MANAGERS:");
                console.dir(Siviglia.Dom.existingManagers[k]);
              }
            }
            if(nListeners>0)
            {
              for(var k in Siviglia.Dom.existingListeners)
              {
                console.log("LISTENERS:");
                console.dir(Siviglia.Dom.existingListeners[k]);
              }
            }
          }
          var button=document.createElement("button");
          button.onclick=onClicked;
          button.innerHTML="Destroy";
          document.body.prependChild(button);

        }
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

  runTest("sivValue: definición",
    "Por defecto, sivValue establece el atributo innerHTML del nodo HTML donde esté.<br>" +
    "También es posible establecer otras parejas atributo-valor, separando cada pareja mediante el símbolo \"<b>::</b>\", y el nombre y el valor de cada atributo por \"<b>|</b>\".",
    '<style>.simpleClass {font-weight:bold;color:green}</style>' +
    '<div data-sivWidget="sivValue-definition" data-widgetCode="Test.SivValueExamples">' +
    '<span data-sivValue="Hello world!"></span>' +
    '<br></br>' +
    '<span data-sivValue="class|[%*assignedClass%]::title|El titulo es:[%*title%]">Simple content</span>' +
    '</div>',
    '<div data-sivView="sivValue-definition"></div>',
    function(){
      Siviglia.Utils.buildClass({
        context:'Test',
        classes:{
          'SivValueExamples':{
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.assignedClass="simpleClass";
                this.title="Titulo asignado desde el widget";
              },
              initialize: function (params) {}
            }
          }
        }
      })
    }
  )
  runTest("sivValue: valor como parametrizableString",
    "El valor de sivValue es una parametrizableString, por lo que no sólo es posible usarlo para referenciar a 1 variable: puede referenciar más de una, texto o expresiones complejas.",
    '<div data-sivWidget="sivValue-ps" data-widgetCode="Test.SivValuePS">' +
    '<span data-sivValue="Esto <h2>[%*message1%]</h2> una <b><i>[%*message2%]</i></b>"></span>' +
    '</div>',
    '<div data-sivView="sivValue-ps"></div>',
    function () {
      Siviglia.Utils.buildClass({
        context: 'Test',
        classes: {
          'SivValuePS': {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.message1 = "es"
                this.message2 = "prueba"
              },
              initialize: function (params) {}
            }
          }
        }
      })
    }
  )
  runTest("sivLoop: definición",
    "Crea una plantilla para cada elemento de un objeto iterable.<br>" +
    "Se emplea como base para generar las plantillas de cada iteración al conjunto de nodo HTML que sean hijos del nodo HTML en el que se define el atributo sipLoop.<br>" +
    "En cada iteracion el elemento correspondiente del objeto iterable es accesible mediante el valor de <b>contextIndex</b> con el prefijo \"<b>@</b>\".<br>" +
    "Ademas de esta variable de contexto, que apunta a los valores, tambien define una que apunta a la key, la cual es accesible con el valor de <b>contextIndex</b>, el plefijo \"<b>@</b>\" y el sufijo \"<b>-index</b>\".",
    '<div data-sivWidget="sivLoop-definition" data-widgetCode="Test.SivLoopDefinition">' +
    '<div data-sivLoop="*simpleArray" data-contextIndex="currentArrayElement">' +
    '<div data-sivValue="Para esta iteración => key del elemento: [%@currentArrayElement-index%] - valor del elemento: [%@currentArrayElement%]"></div>' +
    '</div>' +
    '</div>',
    '<div data-sivView="sivLoop-definition"></div>',
    function () {
      Siviglia.Utils.buildClass({
        context: 'Test',
        classes: {
          'SivLoopDefinition': {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.simpleArray = ["array element 1", "array element 2", "array element 3"];
              },
              initialize: function (params) {}
            }
          }
        }
      })
    }
  )
  runTest("sivLoop: iteraciones anidadas",
    "Es posible anidar varios sivLoops cuando el elemento extraido del objeto iterable es a su vez otro objeto iterable.<br>" +
    "Para ello solo hay que crear un nuevo sivLoop dentro del primero utilizando la referencia al elemento de la iteración como fuente.<br>",
    '<div data-sivWidget="nested-sivLoops" data-widgetCode="Test.NestedSivLoops">' +
    '<div data-sivLoop="*simpleArray" data-contextIndex="loop1-element">' +
    '<div data-sivValue="ext=> key: [%@loop1-element-index%]-valor: [%@loop1-element%]""></div>' +
    '<div style="border:1px solid black">' +
    '<div data-sivLoop="@loop1-element" data-contextIndex="loop2-element">' +
    '<div data-sivValue="int=> key: [%@loop2-element-index%] - valor: [%@loop2-element%]""></div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>',
    '<div data-sivView="nested-sivLoops"></div>',
    function () {
      Siviglia.Utils.buildClass({
        context: 'Test',
        classes: {
          'NestedSivLoops': {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.simpleArray = [
                  {a: "item1", b: "item2", c: "item3"},
                  {a: "item4", b: "item5", c: "item6"},
                  {a: "item7", b: "item8", c: "item9"},
                ];
              },
              initialize: function (params) {}
            }
          }
        }
      })
    }
  )
  runTest("sivCall: definición",
    "El atributo sivCall realiza una llamada al metodo especificado mediante su valor, que recibe como parametro el nodo HTML donde se declara.<br>" +
    "Es posible enviar parámetros adicionales mediante el atributo <b>sivParams</b>. El valor de este atributo es un objeto JSON que en el que se pueden emplear referencias a variables de clase y de contexto.<br>" +
    "En este ejemplo se establece el contenido de los nodos usando sivCall en vez de sivValue.<br>" +
    "(Nota: para especificar el JSON dentro del atributo html, se utilizan comillas simples para el valor del atributo y dobles para las clave y valores del JSON, de forma que no hay que escapear las comillas dobles)",
    '<div data-sivWidget="sivCall-definition" data-widgetCode="Test.SivCallDefinition">' +
    '<div data-sivLoop="*dictionariesArray" data-contextIndex="dictionary">' +
    '<div style="border:1px solid black">' +
    '<div data-sivLoop="@dictionary" data-contextIndex="value">' +
    '<div data-sivCall="setContent" data-sivParams=\'{"indexKey":"@value-index","valueKey":"/@value"}\'></div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>',
    '<div data-sivView="sivCall-definition"></div>',
    function () {
      Siviglia.Utils.buildClass({
        context: 'Test',
        classes: {
          'SivCallDefinition': {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.dictionariesArray = [
                  {a: "item1", b: "item2", c: "item3"},
                  {a: "item4", b: "item5", c: "item6"},
                  {a: "item7", b: "item8", c: "item9"},
                ];
              },
              initialize: function (params) {},
              setContent: function (node, params) {
                node.html(params.indexKey + " : " + params.valueKey);
              }
            }
          }
        }
      })
    }
  )
  runTest("SivEvent","SivEvent, junto a sivcallback y sivParams, se utiliza para asignar un gestor de eventos.<br>"+
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
    '<div data-sivIf="[%*flipper%] == 1"><div style="background-color:blue;color:white">Valor Uno</div></div>'+
    '<div data-sivIf="[%*flipper%] == 0"><div style="background-color:green;color:white">Valor Cero</div></div>'+
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
  runTest("SivIf 2","Prueba de funcionamiento de la regeneración del contenido de sivIf."+
    "Se prueba cómo sivIf regenera los contenidos a medida que cambia.Especificamente, qué ocurre con los sivId definidos dentro de un sivIf.<br>"
    ,
    '<div data-sivWidget="Test.SivIf" data-widgetCode="Test.SivIf">'+
    '<div data-sivIf="[%/*flipper%] == 1"><div data-sivId="target"></div><div style="background-color:blue;color:white">Valor Uno</div></div>'+
    '<div data-sivIf="[%/*flipper%] == 0"><div data-sivId="target"></div><div style="background-color:green;color:white">Valor Cero</div></div>'+
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
                setInterval(function(){
                  this.flipper=(this.flipper+1)%2;
                  if(typeof this.flipper!=="undefined")
                    this.target.html("**"+this.flipper+"**");
                }.bind(this),1000);
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
              preInitialize:function(params) {
                this.receivedParam = params.sentParam;
                console.dir(params);
              },
              initialize:function(params){}
            }
          },
          'ByCode':{
            inherits: "Siviglia.UI.Expando.View",
            destruct:function() {
              if (this.innerWidget) this.innerWidget.destruct();
            },
            methods:{
              preInitialize:function(params) {
                this.innerWidget=null;
              },
              initialize:function(params) {
                this.innerWidget = new Test.Sample(
                  "Test.Sample",
                  {sentParam:"Texto Enviado"},{},
                  $("<div></div>"),
                  new Siviglia.Path.ContextStack()
                );

                this.innerWidget.__build().then(function(instance) {
                  this.here.append(this.innerWidget.rootNode);
                }.bind(this));
              }
            }
          }
        }
      })
    }
  )
  runTest("Layouts alternativos",
    "En este ejemplo se especifican varios layouts, todos apuntando al mismo data-widgetCode.<br>"+
    "Cuando se instancia una vista de un widget es posible especificar un layout alternativo con el atributo data-sivLayout.<br>"+
    "Esta característica es uno de los pilares del sistema de widgets. Significa una separación entre la gestión del código de un widget, y el html que lo renderiza.<br>"+
    "El separar las clases de los layouts, permite que haya varias formas de mostrar un mismo elemento conceptual.<br>"+
    "En este ejemplo, se utiliza un mismo widget, simulando un menu, que se muestra usando varios layouts diferentes.",
    '<div data-sivWidget="Test.AltLayout" data-widgetCode="Test.AltLayout">'+
    '<ul>'+
    '<div data-sivLoop="*options" data-contextIndex="current">'+
    '<li><a data-sivValue="href|[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a></li>'+
    '</div>'+
    '</ul>'+
    '</div>'+
    '<div data-sivWidget="Test.AltLayout2" data-widgetCode="Test.AltLayout">'+
    '<div>'+
    '<div data-sivLoop="*options" data-contextIndex="current">'+
    '<div style="float:left;border-radius:8px;background-color:green;color:white;padding:4px;margin-right:3px"><a data-sivValue="href|[%@current/link%]"><span data-sivValue="[%@current/text%]"></span></a>' +
    '</div>'+
    '</div>' +
    '</div>' +
    '<div style="clear:both"></div>' +
    '</div>',
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
  runTest("Vistas Nombradas","Usando data-viewName, es posible mapear widgets en el widget padre.<br>"+
    "El valor de esta propiedad es una parametrizable string.<br>"+
    "data-sivName es la forma de invocar el código de un widget hijo en el código del widget padre.<br>" +
    "No debe ser empleado en preInitialize porque el ese punto aún no existe la relación.",
    '<div data-sivWidget="Test.Map1" data-widgetCode="Test.Map1">a<div data-sivId="target"></div>b</div>'+
    '<div data-sivWidget="Test.Map2" data-widgetCode="Test.Map2">z<div data-sivId="target"></div>q</div>'+
    // '<div data-sivWidget="Test.MapTest" data-widgetParams="" data-widgetCode="Test.MapTest">'+
    '<div data-sivWidget="Test.MapTest" data-widgetCode="Test.MapTest">'+
    '   <div data-sivView="Test.Map1" data-viewName="map1"></div>'+
    '   <div data-sivLoop="*instances" data-contextIndex="current">'+
    '       <div data-sivView="Test.Map2" data-viewName="map2-[%@current-index%]"></div>'+
    '   </div>'+
    '</div>',
    '<div data-sivView="Test.MapTest"></div>',
    function(){
      Siviglia.Utils.buildClass({
        context:'Test',
        classes:{
          "MapTest": {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.instances={};
                for (var k=0;k<2;k++) {
                  this.instances["prueba-"+k]="hola";
                }

              },
              initialize: function (params) {
                this.waitComplete().then(function(){
                  this.map1.showLabel(" SOY MAP 1");
                  for (var k=0;k<2;k++) {
                    this["map2-prueba-"+k].showLabel("SOY MAP2, INSTANCIA "+k);
                  }
                }.bind(this));
              },
            }
          },
          "Map1":{
            inherits:"Siviglia.UI.Expando.View",
            methods:{
              preInitialize: function() {},
              initialize: function() {},
              showLabel: function(label) {
                this.target.html(label)
              },
            }
          },
          "Map2":{
            inherits:"Test.Map1"
          }
        }
      })
    }
  )
  runTest("Promise Expando","El expando Promise, ejecuta sus contenidos cuando una promesa se resuelve",
    // '<div data-sivWidget="Test.PromiseTest" data-widgetParams="" data-widgetCode="Test.PromiseTest">'+
    '<div data-sivWidget="Test.PromiseTest" data-widgetCode="Test.PromiseTest">'+
    // '   <div data-sivPromise="*myPromise" data-contextIndex="current">'+
    '   <div data-sivPromise="*myPromise">'+
    '       Hola!'+
    '   </div>'+
    '</div>',
    '<div data-sivView="Test.PromiseTest"></div>',
    function(){
      Siviglia.Utils.buildClass({
        context:'Test',
        classes:{
          "PromiseTest": {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.myPromise=SMCPromise();
                setTimeout(function(){
                  this.myPromise.resolve()
                }.bind(this),3000)
              },
              initialize: function (params) {
              }
            }
          }
        }

      })
    }
  )
  runTest("SivLoop",
    "Cuando el objeto sobre el que se itera cambia, se renderiza de nuevo la plantilla",
    '<div data-sivWidget="Test.SivLoop" data-widgetCode="Test.SivLoop">' +
        '<div data-sivLoop="/*simpleArray" data-contextIndex="current">' +
            '<div>' +
                '<div data-sivLoop="@current" data-contextIndex="current2">' +
                    '<span data-sivValue="[%/@current2%]"></span>' +
                '</div>' +
            '</div>' +
        '</div>' +
    '</div>',
    '<div data-sivView="Test.SivLoop"></div>',
    function () {
      Siviglia.Utils.buildClass({
        context: 'Test',
        classes: {
          'SivLoop': {
            inherits: "Siviglia.UI.Expando.View",
            methods: {
              preInitialize: function (params) {
                this.simpleArray = [["cad1"], ["cad2"], ["cad3"]];
                top.sss = this.simpleArray;
              },
              initialize: function (params) {
                setTimeout(function () {
                  this.simpleArray[0].push("adios");
                }.bind(this), 1000);
                setTimeout(function () {
                  this.simpleArray.push(["nuevo"]);
                }.bind(this), 2000);
                setTimeout(function () {
                  this.simpleArray[2].push("uno mas");
                }.bind(this), 3000);
                setTimeout(function () {
                  this.simpleArray.splice(1, 2);
                }.bind(this), 4000);

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
