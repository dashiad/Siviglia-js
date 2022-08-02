<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test file</title>
    <script src='http://statics.adtopy.com/node_modules/d3/dist/d3.js'></script>
    <script src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/autobahn-browser/autobahn.min.js"></script>

    <script src="http://statics.adtopy.com/packages/Siviglia/Siviglia.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/SivigliaStore.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/SivigliaTypes.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/Model.js"></script>

    <script src="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/jqx-all.js"></script>
    <script src="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/globalization/globalize.js"></script>

    <script src="http://statics.adtopy.com/reflection/js/WampServer.js"></script>


    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/css/style.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/jqx.adtopy-dev.css">

    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/styles/jqx.base.css">
</head>
<body style="background-color:#EEE; background-image:none;">
<?php //include_once("../jQuery/JqxWidgets.html"); ?>
<?php //include_once("../jQuery/JqxLists.html"); ?>
<?php //include_once("../jQuery/Visual.html"); ?>
<?php //include_once("../jQuery/JqxViews.html"); ?>
<?php //include_once("../jQuery/JqxTypes.html"); ?>
<?php //include_once("../jQuery/JqxApp.html"); ?>
<script>
  var Siviglia = Siviglia || {};
  Siviglia.debug = true;

  Siviglia.config = {
    baseUrl: 'http://reflection.adtopy.com/',
    staticsUrl: 'http://statics.adtopy.com/',
    metadataUrl: 'http://metadata.adtopy.com/',
    user: {
      USER_ID: "1",
      TOKEN: "1"
    },
    wampServer: {
      "URL": "ws://statics.adtopy.com",
      "PORT": "8999",
      "REALM": "adtopy",
    },
    // Si el mapper es XXX, debe haber:
    // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
    // 2) Un Mapper en Siviglia.Model.XXXMapper
    // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
    mapper: 'Siviglia'
  };
  Siviglia.Model.initialize(Siviglia.config);

  var wConfig = Siviglia.config.wampServer;
  var wampServer = new Siviglia.comm.WampServer(
    wConfig.URL,
    wConfig.PORT,
    wConfig.REALM,
    Siviglia.config.user.TOKEN
  );
  Siviglia.Service.add("wampServer", wampServer);
</script>

<div style="display:none">
    <div data-sivWidget="input-definition" data-widgetParams="" data-widgetCode="Test.InputDefinition">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"fieldName","parent":"*type","form":"*form","controller":"*self"}'>
        </div>
    </div>
</div>

<div data-sivView="input-definition"></div>


<script>
  var dependencyList = [
    "packages/Siviglia/jQuery/JqxWidgets",
    'packages/Siviglia/jQuery/JqxTypes',
    "packages/Siviglia/jQuery/JqxViews",
    "packages/Siviglia/jQuery/JqxLists",
    "packages/Siviglia/jQuery/JqxWidgets",
  ]
  Siviglia.require(dependencyList, true, false).then(function () {
    Siviglia.Utils.buildClass({
      context: "Siviglia.Widget.Surface",
      classes: {
        Card: {
          inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          methods: {
            preInitialize: function (params) {},
            initialize: function (params) {},
            update: function (source) {
            },
          },
        },
      },
    })

    $(document).ready(function(){
      var parser = new Siviglia.UI.HTMLParser();
      parser.parse($(document.body));
    })
  })
</script>
<script>

</script>
</body>
</html>
