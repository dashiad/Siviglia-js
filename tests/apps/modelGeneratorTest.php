<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Model manager</title>
    <script src='http://statics.adtopy.com/packages/d3/d3.js'></script>
    <script src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/autobahn-browser/autobahn.min.js"></script>

    <script src="http://statics.adtopy.com/packages/Siviglia/Siviglia.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/SivigliaStore.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/SivigliaTypes.js"></script>
    <script src="http://statics.adtopy.com/packages/Siviglia/Model.js"></script>

    <script src="http://statics.adtopy.com/packages/jqwidgets/jqx-all.js"></script>
    <script src="http://statics.adtopy.com/packages/jqwidgets/globalization/globalize.js"></script>

    <script src="http://statics.adtopy.com/reflection/js/WampServer.js"></script>


    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/css/style.css">
    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/backend/css/style.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/jqx.adtopy-dev.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/jqwidgets/styles/jqx.base.css">


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
<div style="display:none">
    <div data-sivWidget="model-manager" data-widgetCode="Testing.ModelManager">
        <div data-sivLoop="/*packageList" data-contextIndex="package">
            <div data-sivValue="/@package/name" data-sivEvent="click" data-sivCallback="loadModels"
                 data-sivparams='{"package":"/@package/name"}'>
            </div>
        </div>

        <div data-sivLoop="/*modelList" data-contextIndex="model">
            <div data-sivValue="/@model/name"></div>
            <button data-sivEvent="click" data-sivCallback="generateAction"
                    data-sivParams='{"model":"/@model/name"}'>
                Regenerar acciones
            </button>
            <button data-sivEvent="click" data-sivCallback="generateDataSource"
                    data-sivParams='{"model":"/@model/name"}'>
                Regenerar dataSources
            </button>
            <button data-sivEvent="click" data-sivCallback="generateModel"
                    data-sivParams='{"model":"/@model/name"}'>
                Regenerar modelo
            </button>
        </div>
    </div>
</div>


<div data-sivView="model-manager"></div>
<script>
  Siviglia.Utils.buildClass({
    "context": "Testing",
    "classes": {
      ModelManager: {
        "inherits": "Siviglia.inputs.jqwidgets.Form",
        "methods": {
          preInitialize: function (params) {
            this.vars = {}
            this.pkg = null
            this.model = null
            this.packageList = [];
            this.modelList = [];
          },
          initialize: function (params) {
            this.packageDS = new Siviglia.Model.DataSource("/model/reflection/Model", "PackageList", {});
            return this.packageDS.unfreeze().then(function () {
              this.packageList = this.packageDS.data;
            }.bind(this))
          },
          loadModels: function (node, params) {
            this.pkg = params.package;
            this.modelDS = new Siviglia.Model.DataSource("/model/reflection/Model", "FullList", {package: this.pkg});
            this.modelDS.freeze();
            this.modelDS.unfreeze().then(function () {
              this.modelList = this.modelDS.data
            }.bind(this))
          },
          generateAction: function (node, params) {
            this.model = params.model
            this.sendForm('Action')
          },
          generateDataSource: function (node, params) {
            this.model = params.model
            this.sendForm('DataSource')
          },
          generateModel: function (node, params) {
            this.model = params.model
            this.sendForm('Model')
          },
          sendForm: function (formType) {
            this.formFactory = new Siviglia.Model.FormFactory()
            this.formFactory.getForm('/model/reflection/' + formType, 'generateDefaults', {}).then(function (form) {
              form.model = '/model/' + this.pkg + '/' + this.model
              form.submit()
            }.bind(this))
          }
        }
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
