<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Model manager</title>
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
    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/backend/css/style.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/jqx.adtopy-dev.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/styles/jqx.base.css">


    <!-- dependencias para la visualizacion de test-->
    <link rel="stylesheet" type="text/css" href="testStyles.css">

    <script src="http://statics.adtopy.com/packages/Siviglia/tests/highlight/highlight.pack.js"></script>
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/tests/highlight/styles/ir-black.css">
</head>
<body style="background-color:#EEE; background-image:none;">
<?php include_once("../../jQuery/JqxWidgets.html"); ?>
<?php include_once("../../jQuery/JqxLists.html"); ?>
<?php include_once("../../jQuery/Visual.html"); ?>
<?php include_once("../../jQuery/JqxViews.html"); ?>
<?php include_once("../../jQuery/JqxTypes.html"); ?>
<?php include_once("../../jQuery/JqxApp.html"); ?>
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


<div data-sivWidget="Test.CursorTree_GridForm" data-widgetCode="Test.CursorTree_GridForm">
    <div class="widListForm Siviglia_sys_Cursor_lists_Test_CursorTree_GridForm">
        <div class="widListFormFieldSet">
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'id'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'parent'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'type'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'status'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'start'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'end'}"></div>
            </div>
            <div class="widField">
                <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
                     data-sivParams="{'controller':'/*self','parent':'/*type','form':'/*form','key':'rowsProcessed'}"></div>
            </div>
        </div>
    </div>
</div>

<div data-sivWidget="Test.CursorTree_Grid" data-widgetCode="Test.CursorTree_Grid">
    <div>

        <div data-sivId="filterNode"></div>
        <div data-sivId="grid"></div>
    </div>
</div>
<div data-sivWidget="Test.CursorTree_Controller" data-widgetCode="Test.CursorTree_Controller">
    <div data-sivView="Test.CursorTree_Grid" data-viewName="grid"></div>
    <div data-sivId="cursorGraphNode"></div>

</div>

<div data-sivView="Test.CursorTree_Controller"></div>

<script>
  Siviglia.Utils.buildClass({
    context: 'Test',
    classes: {
      // datasource para el Grid de los cursores: model\sys\objects\Cursor\js\Siviglia\lists\FullList.js
      "CursorTree_Controller": {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
          this.reset();
        },
        methods: {
          preInitialize: function (params) {
            this.currentGraph = null;
          },
          initialize: function (params) {


            this.addListener("ON_CURSOR_SELECTED", this, "onCursorChanged");
          },
          reset: function () {
            if (this.currentGraph !== null)
              this.currentGraph.destruct();
            this.cursorGraphNode.html("");
          },
          onCursorChanged: function (evName, param) {
            var cursorId = param.cursor;
            alert("CAMBIADO A CURSOR:" + cursorId);

          }
        }
      },
      "CursorTree_Grid": {
        "inherits": "Siviglia.lists.jqwidgets.BaseGrid",
        "methods":
          {
            preInitialize: function (params) {
              this.BaseGrid$preInitialize({
                "filters": "Test.CursorTree_GridForm",
                "ds": {
                  "model": "/model/sys/Cursor",
                  "name": "FullList",
                  "settings": {
                    pageSize: 20
                  }
                },
                "columns": {
                  "id": {"Type": "Field", "Field": "id", "Label": "Id Cursor", "gridOpts": {"width": "10%"}},
                  "parent": {"Type": "Field", "Field": "parent", "Label": "Parent", "gridOpts": {"width": "10%"}},
                  "Type": {"Type": "Field", "Field": "type", "Label": "Type Cursor", "gridOpts": {"width": "20%"}},
                  "status": {
                    "Type": "Field",
                    "Field": "status",
                    "Label": "Status Cursor",
                    "gridOpts": {"width": "10%"}
                  },
                  "start": {"Type": "Field", "Field": "start", "Label": "Start Cursor", "gridOpts": {"width": "15%"}},
                  "end": {"Type": "Field", "Field": "end", "Label": "End Cursor", "gridOpts": {"width": "15%"}},
                  "rowsProcessed": {
                    "Type": "Field",
                    "Field": "rowsProcessed",
                    "Label": "Filas Procesadas",
                    "gridOpts": {"width": "20%"}
                  },
                },
                "gridOpts": {width: "100%"}
              });
            },
            initialize: function (params) {
              this.BaseGrid$initialize(params);
              this.grid.on("cellclick", function (args) {
                var cursorId = args.args.row.bounddata.id;
                this.__parentView.fireEvent("ON_CURSOR_SELECTED", {cursor: cursorId});

              }.bind(this));
            }
          }
      },
      "CursorTree_GridForm":
        {
          "inherits": "Siviglia.lists.jqwidgets.BaseFilterForm",
          "methods": {}
        }
    },
  })
</script>


<script>
  var parser = new Siviglia.UI.HTMLParser();
  parser.parse($(document.body));
</script>
</body>
</html>