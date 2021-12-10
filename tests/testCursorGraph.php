<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cursor Graph</title>
    <!-- para quitar error consola GET http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css net::ERR_ABORTED 404 (Not Found) -->
    <!-- <link rel='stylesheet prefetch' href='http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css'> -->
    <!-- <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'> -->
    <script src='/packages/d3/d3.js'></script>
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
    //DEVELOP_MODE=(-1);  // Latest test
    DEVELOP_MODE=(-1);
  } else {
    DEVELOP_MODE = urlParams.get("test");
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
        if(code.substr(pos, code.substr(pos).indexOf("<")).trim() === '' ) char = '';
      }

      result += char;
    }

    return result;
  }

  function checkTests(restart) {
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

  runTest("Pintado de cursores","Prueba de diseño para ver como pintar cursores mediante diagramas de fuerza. <br>",
    '<div data-sivWidget="Test.DataGridForm" data-widgetCode="Test.DataGridForm">'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"id"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"parent"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"type"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"status"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"start"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"end"}\'></div>'+
        '</div>'+
        '<div class="widField">'+
            '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"rowsProcessed"}\'></div>'+
        '</div>'+
    '</div>'+

    '<div data-sivWidget="Test.CursorNodeView" data-widgetParams="" data-widgetCode="Test.CursorNodeView">'+
        '<div id="cursorContainer" data-sivValue="class|cursorState_[%*status_cursor%]">'+
            '<div>'+
                '<span data-sivValue="class|cursor [%*paquete_modelo%]-cursors [%*cursorNameShort%]"></span>' +
                '<span data-sivValue="/*cursorNameShort"></span>'+
                '<div data-sivValue="[[%*rowsProcessed%]]"></div>'+
                '<div data-sivValue="class|iconStatusCursor_[%*status_cursor%]::title|[%*status_text%]"></div>'+
            '</div>'+

            '<div class="extra_info">'+
                '<div data-sivValue="/*id_cursor"></div>'+
                '<div data-sivValue="/*fecha_start"></div>'+
                '<div data-sivValue="/*fileName"></div>'+
                '<div data-sivIf="[%*errored%] == true">'+
                    '<div class="cursor error_message" data-sivValue="/*error_message"></div>'+
                '</div>' +
            '</div>'+
        '</div>'+
    '</div>'+

    '<div data-sivWidget="Test.CursorsGraph" data-widgetCode="Test.CursorsGraph">' +
        '<svg data-sivId="svgNode" style="width: 100%; height: 100%"></svg>' +
    '</div>'+

    '<div data-sivWidget="Test.DataGrid" data-widgetCode="Test.DataGrid">'+
        '<div data-sivId="filterNode"></div>'+
        '<div data-sivId="grid"></div>'+
    '</div>' +

    '<div data-sivWidget="Test.ViewController" data-widgetCode="Test.ViewController">' +
        '<div data-sivView="Test.DataGrid" data-sivParams="{}"></div>' +
        '<div data-sivId="graphicArea" style="width: 100%; height:100%"></div>' +
    '</div>',


    '<div data-sivView="Test.ViewController" data-sivParams="{}"></div>',
    function(){
      Siviglia.Utils.buildClass({
        context:'Test',
        classes:{
          "CursorNodeView":{
            inherits:"Siviglia.UI.Expando.View",
            destruct:function() {
              this.item["*status"].removeListeners(this);
            },
            methods: {
              preInitialize:function(params) {
                // Obtención de variables para mostrar el contenido del cursor en params.item
                if (params.item.cursorDefinition && params.item.cursorDefinition.fileName != undefined)
                  this.fileName = params.item.cursorDefinition.fileName.split('\\').pop().split('/').pop();
                else
                  this.fileName = "---";
                this.item = params.item;
                this.id_cursor = params.item.id;

                // Se especifica el tipo solo si el nombre es vacío.
                this.cursorNameShort = (params.item.name == null) ? params.item.type.split("\\").pop() : params.item.name;
                this.status_cursor = params.item.status;
                this.rowsProcessed = params.item.rowsProcessed;
                this.fecha_start = params.item.start;
                this.error_message = params.item.error == null ? '' : params.item.error;
                this.status_text="";
                Siviglia.Path.eventize(params.item, "status");
                params.item["*status"].addListener("CHANGE", this, "onChangeStatus");

                // Siviglia.Path.eventize(params.item, "error");
                // params.item["*error"].addListener("CHANGE", this, "onErrorCursor");

                this.errored = (this.error_message !== '');

                // Dependiendo de la ruta del path del cursor, montar un path asociado a ese cursor
                // para asi definir los iconos asociados a los paquetes-modelos-lib o default
                this.path_cursor = params.item.type.split('\\');
                this.path_first = this.path_cursor.shift();
                switch (this.path_first) {
                  case "lib":
                    this.paquete_modelo = "default";
                    break;
                  case "model":
                    this.paquete_modelo = this.path_cursor[1] + "_" + this.path_cursor[2]; // paquete_modelo. Ej: ads_dfp
                    break;
                  default:
                    this.paquete_modelo = "default";
                    break;
                }

                // Cargamos los valores enum de la definition model\sys\objects\Cursor\Definition.php
                var cursorModelDefinition = Siviglia.Model.loader.getModelDefinition("/model/sys/Cursor");
                this.statusFieldLabel = cursorModelDefinition.FIELDS.status.VALUES;

              },
              onChangeStatus:function()
              {
                // Si el listener ha recibido un evento de cambio, entonces se modifica el texto del estado,
                // según el valor del item.status y actualizamos el nombre 'status_text'
                this.status_text = this.statusFieldLabel[this.item.status];

                // actualizamos el estado del cursor para mostrar el icono asociado al estado
                this.status_cursor = this.item.status;

                // actualizamos las rows procesadas
                this.rowsProcessed = this.item.rowsProcessed;
              },
              onErrorCursor:function()
              {
                // mensaje de error capturado
                this.error_message = this.item.error;
              },
              initialize:function(params){
                this.onChangeStatus();
              }
            }
          },
          "CursorsGraph":{
            inherits:"Siviglia.visual.Force",
            destruct:function() {},
            methods:{
              initialize:function(params) {
                this.cursorBuffer={};
                this.cursorNodes=[];
                this.cursorLinks=[];
                this.Force$initialize(params);
                this.svg.append("svg:defs").selectAll("marker")
                  .data(["end"])      // Different link/path types can be defined here
                  .enter().append("svg:marker")
                  .attr("id","end")// This section adds in the arrows
                  .attr("viewBox", "0 -5 10 10")
                  .attr("refX", 15)
                  .attr("refY", 0.5)
                  .attr("markerWidth", 13)
                  .attr("markerHeight", 13)
                  .attr("orient", "auto")
                  .append("svg:path")
                  .attr('fill', '#999')
                  .attr("d", "M0,-5L10,0L0,5");
              },
              onData: function(cursorData) {
                var currentCursor=this.cursorBuffer[cursorData.id];
                var parent=cursorData.parent;
                var container=cursorData.container;
                var id=cursorData.id;

                if (typeof currentCursor!=="undefined" && currentCursor!==null) {
                  // Se updatean los posibles links..Se supone que nunca se va a cambiar un link,
                  // solo se añaden...Es por eso que no se busca un link antiguo y se quita..
                  if (currentCursor.parent!==parent)
                    this.cursorLinks.push({source:parent,target:id,type:"parent"});
                  if (currentCursor.container!==container)
                    this.cursorLinks.push({source:container,target:id,type:"container"});

                  for(var k in cursorData)
                    currentCursor[k]=cursorData[k];
                }
                else {
                  this.cursorBuffer[cursorData.id] = cursorData;
                  this.cursorNodes.push(cursorData);
                  if(parent!==null)
                    this.cursorLinks.push({source:parent,target:id,type:"parent"})
                  if(container!==null)
                    this.cursorLinks.push({source:container,target:id,type:"container"})
                }

                this.update();
              },
              getNodesAndLinks:function() {
                return {nodes:this.cursorNodes, links:this.cursorLinks};
              },
              updateLinks:function(links) {
                this.Force$updateLinks(links);
                this.graphLinks.attr("marker-end", "url(#end)");
                //this.graphLinks.attr('marker-end', function(d,i){ return 'url(#end)' })
              }
            }
          },
          "ViewController":{
            inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
            destruct: function() {
              /*if(this.modelView)
                                this.modelView.destruct();
                            if(this.currentItemView)
                                this.currentItemView.destruct();*/
              if (this.wampService) {
                this.wampService.call("com.adtopy.removeBusListener",[this.__identifier]);
              }
              this.cursorsGraph.destruct();
            },
            methods:{
              preInitialize: function(params) {
                this.modelView=null;
                this.currentItemView=null;
                this.editing=false;
                this.shown="hidden";
                this.selectedIcon="";
                this.selectedName="";
                this.selectedModel="";
                this.selectedSubModel="";
                this.selectedResourceType="";
                this.selectedClass="";
                this.selectedFile="";
              },
              initialize: function(params) {
                var stack = new Siviglia.Path.ContextStack();
                this.cursorsGraph=null;
                var cursorsGraphWidget=new Test.CursorsGraph(
                  "Test.CursorsGraph",
                  {
                    parent:this,
                    svgWidth:600,
                    svgHeight:400,
                    nodeWidget:'Test.CursorNodeView',
                    nodeWidth:300,
                    nodeHeight:300,
                    allowMultipleSelection:false,
                    rowIdField:'id',
                    distanceLinks: 1 //parece no tener efecto visual
                  },
                  {},
                  $("<div></div>"),
                  stack,
                );
                cursorsGraphWidget.__build().then(function(instance){
                  this.cursorsGraph=instance;
                  this.graphicArea.append(instance.rootNode);
                }.bind(this))


                this.addListener("ON_CURSOR_SELECTED",this,"onCursorSelected");

                // Simulacion de recepcion de datos en un periodo de tiempo
                /*var events=[
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/simple.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1a3449","parent":null,"container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileWriterCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/intermediate.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b3425","parent":"609850c1a3449","container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":1,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":2,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":0,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":3,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":null},
                                    {"name":null,"type":"lib\\data\\Cursor\\CSVFileReaderCursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"fileName":"C:\\xampp7\\htdocs\\adtopy\\lib\\tests\\data/res/second.csv"},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1b6b3d","parent":"609850c1b3425","container":null,"end":"2021-05-09 23:14:41"},
                                    {"name":null,"type":"lib\\data\\Cursor\\Cursor","rowsProcessed":13,"start":"2021-05-09 23:14:41","error":null,"cursorDefinition":{"callback":[]},"status":5,"phaseStart":"2021-05-09 23:14:41","id":"609850c1ba803","parent":"609850c1b6b3d","container":null,"end":"2021-05-09 23:14:41"}
                                ];
                                var curEvent=0;
                                var cInt;
                                cInt=setInterval(function(){
                                    if(curEvent == events.length) {
                                        clearInterval(cInt);
                                        return;
                                    }
                                    this.onCursor(events[curEvent]);
                                    curEvent=curEvent+1;
                                }.bind(this),1000);*/

                // Se pone un listener sobre cualquier cambio en reflection
                this.wampService=Siviglia.Service.get("wampServer");
                if (this.wampService) {
                  this.__identifier=Siviglia.Model.getAppId();
                  this.wampService.call("com.adtopy.replaceBusListener",[
                    {channel:'General',path:'/model/sys/Cursor/*',roles:0xFFF,appId:this.__identifier,userId:top.Siviglia.config.user.TOKEN}]);

                  this.wampService.subscribe('busevent',function(data){
                    var channel=data[0];
                    var params=data[1];
                    var appData=data[2];
                    if(appData.appId===this.__identifier)
                    {
                      this.onCursor(params.data);
                    }

                  }.bind(this))
                }
              },
              onCursorSelected:function(eventName, cursorData) {
                this.onCursor(cursorData)
              },
              onCursor:function(cursorInfo)
              {
                if(this.cursorsGraph)
                  this.cursorsGraph.onData(cursorInfo)
              },
              onItemSelected:function(evName,params)
              {
                this.showItemData(params.selection[0].d);

                // Se prepara el nombre del widget de edicion.
                // Si el nombre del recurso era "model", se carga Siviglia.Reflection.Model.
              },
              onSelectionEmpty:function()
              {
                if(this.currentItemView)
                {
                  this.componentViewContainer.html("");
                }
                this.editing=false;
                this.shown="hidden";
                //this.modelView.unselect(this.lastItemSelected.d);

              },
              closeComponentView:function()
              {
                this.onSelectionEmpty();
              },
              onBackgroundClicked:function()
              {
                this.onSelectionEmpty();
              },
              showItemData:function(d)
              {
                this.shown="shown";

                var f=new Adtopy.reflection.ResourceMeta();
                var meta=f.getResourceMeta(d);
                this.selectedIcon=meta.icon;
                this.selectedName=typeof d.name==="undefined"?d.class:d.name;
                this.selectedModel=typeof d.model==="undefined"?"":d.model;
                this.selectedSubModel=typeof d.submodel==="undefined" || d.submodel===null?"":d.submodel;
                this.selectedResourceType=d.resource;
                this.selectedClass=typeof d.class==="undefined"?"":d.class;
                this.selectedFile=typeof d.file==="undefined"?"":d.file;

              },
            }
          },
          "DataGrid": {
            "inherits": "Siviglia.lists.jqwidgets.BaseGrid",
            "methods": {
              preInitialize:function(params) {
                this.BaseGrid$preInitialize({
                  "filters":"Test.DataGridForm",
                  "ds":{
                    "model":"/model/sys/Cursor",
                    "name":"FullList",
                    "settings":{
                      pageSize:10
                    }
                  },
                  "columns": {
                    "id":            {"Type": "Field", "Field":"id", "Label":"Id", "gridOpts":{"width":"10%"}},
                    "parent":        {"Type": "Field", "Field":"parent", "Label":"Parent", "gridOpts":{"width":"10%"}},
                    "container":     {"Type": "Field", "Field":"container", "Label":"Container", "gridOpts":{"width":"10%"}},
                    "name":          {"Type": "Field", "Field":"name", "Label":"Name", "gridOpts":{"width":"10%"}},
                    "Type":          {"Type": "Field", "Field":"type", "Label":"Type", "gridOpts":{"width":"20%"}},
                    "status":        {"Type": "Field", "Field":"status", "Label":"Status", "gridOpts":{"width":"5%"}},
                    "start":         {"Type": "Field", "Field":"start", "Label":"Start", "gridOpts":{"width":"5%"}},
                    "end":           {"Type": "Field", "Field":"end", "Label":"End", "gridOpts":{"width":"10%"}},
                    "rowsProcessed": {"Type": "Field", "Field":"rowsProcessed", "Label":"Filas Proc.", "gridOpts":{"width":"5%"}},
                    "error":         {"Type": "Field", "Field":"error", "Label":"Error text", "gridOpts":{"width":"10%"}},
                    "cursorDefinition": {"Type": "Field", "Field":"cursorDefinition", "Label":"Definition", "gridOpts":{"width":"5%"}},
                  },
                  "gridOpts": { width:"100%" }
                });
              },
              initialize: function (params) {
                this.BaseGrid$initialize(params);

                this.grid.on("cellclick",function(eventData){
                  var gridRowData=eventData.args.row.bounddata;
                  this.__parentView.fireEvent("ON_CURSOR_SELECTED", gridRowData);
                }.bind(this));
              },
            }
          },
          "DataGridForm": {
            "inherits": "Siviglia.lists.jqwidgets.BaseFilterForm",
            "methods": { }
          },
        },
      })
    }
  )
</script>
<script>
  checkTests();
</script>


</body>
</html>
