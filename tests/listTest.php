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
    <script src="/node_modules/autobahn-browser/autobahn.min.js"></script>
    <script src="/reflection/js/WampServer.js"></script>
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
    user:{
      USER_ID:"1",
      TOKEN:"1"
    },
    wampServer:{
      "URL":"ws://statics.adtopy.com",
      "PORT":"8999",
      "REALM":"adtopy",
      /*"user":{
        "USER_ID": "1",
        "TOKEN":'1',
      },*/
    },
    // Si el mapper es XXX, debe haber:
    // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
    // 2) Un Mapper en Siviglia.Model.XXXMapper
    // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
    mapper:'Siviglia'
  };
  Siviglia.Model.initialize(Siviglia.config);
</script>

<script>
  var wConfig=Siviglia.config.wampServer;
  var wampServer = new Siviglia.comm.WampServer(
    wConfig.URL,
    wConfig.PORT,
    wConfig.REALM,
    top.Siviglia.config.user.TOKEN
  );
  Siviglia.Service.add("wampServer", wampServer);
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

  runTest("Listado derivado de BaseGrid","Test de Widget de Listado,directamente dereivado de BaseGrid con datasource remoto,filtros, y subwidgets",
    '<div data-sivWidget="Test.ListViewerForm" data-widgetCode="Test.ListViewerForm">' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"id_page", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"id_site", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"namespace", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"tag", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"name", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"date_add", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"date_modified", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"id_type", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"isPrivate", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"path", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
    '</div>' +
    '<div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer"></div>' +
    '<div data-sivWidget="Test.ListButton" data-widgetCode="Test.ListButton">' +
    '   <input style="width:120px;margin:0px" type="button" value="Show Id" data-sivEvent="click" data-sivCallback="onClicked">' +
    '</div>',
    ' <div data-sivView="Test.ListViewer" data-sivLayout="Siviglia.lists.jqwidgets.BaseGrid"></div>',
    function () {
      Siviglia.Utils.buildClass({
        "context": "Test",
        "classes": {
          ListViewer: {
            "inherits": "Siviglia.lists.jqwidgets.BaseGrid",
            "methods": {
              preInitialize: function (params) {
                this.BaseGrid$preInitialize({
                    "filters": "Test.ListViewerForm",
                    "ds": {
                      "model": "/model/web/Page",
                      "name": "FullList",
                      "settings": {
                        pageSize: 20
                      }
                    },
                    "columns": {
                      "id": {"Type": "Field", "Field": "id_page", "Label": "id", gridOpts: {width: "80px"}},
                      "Id-name": {"Label": "Pstring", "Type": "PString", "str": '<a href="#" onclick="javascript:alert([%*id_page%]);">[%*name%]</a>', gridOpts: {width: '10%'}},
                      "Wid": {"Label": "Wid", "Type": "Widget", "Widget": "Test.ListButton", gridOpts: {width: '10%'}},
                      "name": {"Type": "Field", "Field": "name", "Label": "name", gridOpts: {width: '10%'}},
                      //"namespace":{"Type":"Field","Field":"namespace","Label":"Namespace",gridOpts:{width:'10%'}},
                      "tag": {"Type": "Field", "Field": "tag", "Label": "Tag", gridOpts: {width: '10%'}},
                      "id_site": {"Type": "Field", "Field": "id_site", "Label": "id_site", gridOpts: {width: '10%'}},
                      "date_add": {"Type": "Field", "Field": "date_add", "Label": "Add date", gridOpts: {width: "30px", height: "100px"}},
                      "date_modified": {"Type": "Field", "Field": "date_modified", "Label": "Last Modified", gridOpts: {width: "50px"}},
                      "id_type": {"Type": "Field", "Field": "id_type", "Label": "Type id"},
                      "isPrivate": {"Type": "Field", "Field": "isPrivate", "Label": "Is Private"},
                      "path": {"Type": "Field", "Field": "path", "Label": "Path", gridOpts: {width: "40px"}},
                      "title": {"Type": "Field", "Field": "title", "Label": "Title"}
                    },
                    "gridOpts": {
                      width: "100%",
                      //rowsheight:100
                    }
                  }
                );
              }
            }
          },
          ListButton: {
            "inherits": "Siviglia.UI.Expando.View",
            "methods": {
              preInitialize: function (params) {
                this.data = params.row;
              },
              initialize: function (params) {},
              onClicked: function (node, params) {
                alert(this.data.id_page);
              }
            }
          },
          ListViewerForm: {
            "inherits": "Siviglia.lists.jqwidgets.BaseFilterForm",
          }
        }
      });
    }
  )
</script>
<script>
  checkTests();
</script>


</body>
</html>
