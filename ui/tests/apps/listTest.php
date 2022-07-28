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

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/jqx.adtopy-dev.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/styles/jqx.base.css">
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


<div data-sivWidget="Test.ListViewerForm" data-widgetCode="Test.ListViewerForm">
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'id_page','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'id_site','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'namespace','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'tag','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'name','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'date_add','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'date_modified','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'id_type','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'isPrivate','controller':'*self','parent':'*type','form':'*form'}">
    </div>
    <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
         data-sivParams="{'key':'path','controller':'*self','parent':'*type','form':'*form'}">
    </div>
</div>
<div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer"></div>
<div data-sivWidget="Test.ListButton" data-widgetCode="Test.ListButton">
    <input style="width:120px;margin:0px" type="button" value="Show Id" data-sivEvent="click" data-sivCallback="onClicked">
</div>

<div data-sivView="Test.ListViewer" data-sivLayout="Siviglia.lists.jqwidgets.BaseGrid"></div>

<script>
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
                  "Id-name": {
                    "Label": "Pstring",
                    "Type": "PString",
                    "str": '<a href="#" onclick="javascript:alert([%*id_page%]);">[%*name%]</a>',
                    gridOpts: {width: '10%'}
                  },
                  "Wid": {"Label": "Wid", "Type": "Widget", "Widget": "Test.ListButton", gridOpts: {width: '10%'}},
                  "name": {"Type": "Field", "Field": "name", "Label": "name", gridOpts: {width: '10%'}},
                  //"namespace":{"Type":"Field","Field":"namespace","Label":"Namespace",gridOpts:{width:'10%'}},
                  "tag": {"Type": "Field", "Field": "tag", "Label": "Tag", gridOpts: {width: '10%'}},
                  "id_site": {"Type": "Field", "Field": "id_site", "Label": "id_site", gridOpts: {width: '10%'}},
                  "date_add": {
                    "Type": "Field",
                    "Field": "date_add",
                    "Label": "Add date",
                    gridOpts: {width: "30px", height: "100px"}
                  },
                  "date_modified": {
                    "Type": "Field",
                    "Field": "date_modified",
                    "Label": "Last Modified",
                    gridOpts: {width: "50px"}
                  },
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
          initialize: function (params) {
          },
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
</script>


<script>
  var parser = new Siviglia.UI.HTMLParser();
  parser.parse($(document.body));
</script>
</body>
</html>