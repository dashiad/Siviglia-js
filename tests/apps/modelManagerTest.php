<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List from dataSource</title>
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
</head>
<body>
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

<style>
    body {
        color: #FFFFFF;
    }

    .sidebar {
        height: 100%; /* Full-height: remove this if you want "auto" height */
        width: 250px; /* Set the width of the sidebar */
        position: fixed; /* Fixed Sidebar (stay in place on scroll) */
        top: 0; /* Stay at the top */
        left: 0;
        background-color: #191c24;
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 20px;
    }

    .sidebar .listItem {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        /*font-size: 25px;*/
        color: #6c7293;
        display: block;
    }

    .sidebar .listItem:hover {
        color: #f1f1f1;
    }

    .page-body {
        margin-left: 250px; /* Same as the width of the sidebar */
        padding: 1.875rem 1.75rem;
    }

    .card {
        background-color: #191c24;
        border-radius: 0.25rem;
        margin-bottom: 1.25rem;
        padding: 1.875rem 1.75rem;
    }

    .card-body {
        color: #6c7293;
    }

    .title-3 {
        color: #FFFFFF;
    }
</style>

<!-- Declaración de widgets -->
<div style="display:none">
    <div data-sivWidget="Siviglia.Apps.jqwidgets.ModelCard" data-widgetCode="Siviglia.Apps.jqwidgets.ModelCard">
        <div class="card">
            <div data-sivValue="[%*modelName%]"></div>
            <div class="card-body">
                <h5 class="title-3">Actions</h5>
                <div data-sivView="Siviglia.widgets.jqwidgets.RecursiveDSList"
                     data-sivParams='{"innerListParams":"*actionListParams"}'></div>
                <h5 class="title-3">DataSources</h5>
                <div data-sivView="Siviglia.widgets.jqwidgets.RecursiveDSList"
                     data-sivParams='{"innerListParams":"*dataSourceListParams"}'></div>
                <h5 class="title-3">Forms</h5>
                <div data-sivView="Siviglia.widgets.jqwidgets.RecursiveDSList"
                     data-sivParams='{"innerListParams":"*formListParams"}'></div>
                <h5 class="title-3">Pages</h5>
                ?
                <h5 class="title-3">Views</h5>
                ?
                <h5 class="title-3">Forms html/js</h5>
                ?
                <h5 class="title-3">Apps html/js</h5>
                ?
            </div>
        </div>
    </div>

    <div data-sivWidget="Siviglia.Apps.jqwidgets.MenuList" data-widgetCode="Siviglia.Apps.jqwidgets.MenuList">
        <div data-sivView="Siviglia.widgets.jqwidgets.RecursiveDSList"
             data-sivParams='{"innerListParams":"*innerListParams"}'></div>
    </div>

    <div data-sivWidget="Siviglia.Apps.jqwidgets.ModelManager" data-widgetCode="Siviglia.Apps.jqwidgets.ModelManager">
        <div class="sidebar">
            <div data-sivView="Siviglia.Apps.jqwidgets.MenuList"></div>
        </div>
        <div class="page-body">
            <div data-sivView="Siviglia.Apps.jqwidgets.ModelCard"></div>
        </div>
    </div>
</div>


<!-- Fin declaración de widgets -->

<div data-sivView="Siviglia.Apps.jqwidgets.ModelManager"></div>

<script>
  Siviglia.Utils.buildClass({
    context: 'Siviglia.Apps.jqwidgets',
    classes: {
      ModelManager: {
        inherits: 'Siviglia.UI.Expando.View',
        methods: {
          preInitialize: function () {
            this.var = null
          },
          initialize: function (){}
        },
      },
      ModelCard: {
        inherits: 'Siviglia.UI.Expando.View',
        methods: {
          preInitialize: function () {
            this.modelName = 'Action(test)'
            this.actionListParams = {
              model: '/model/reflection/Action',
              dataSource: 'fullList',
              keys: {model: '/model/reflection/Action'},
              label: 'name',
              value: 'name',
            }
            this.dataSourceListParams = {
              model: '/model/reflection/DataSource',
              dataSource: 'fullList',
              keys: {model: '/model/reflection/Action'},
              label: 'name',
              value: 'name',
            }
            this.formListParams = {
              model: '/model/reflection/Html/Form',
              dataSource: 'fullList',
              keys: {model: '/model/reflection/Action'},
              label: 'name',
              value: 'name',
            }
          },
        },
      },
      MenuList: {
        inherits: 'Siviglia.UI.Expando.View',
        methods: {
          preInitialize: function () {
            this.innerListParams = {
              model: '/model/reflection/Model',
              dataSource: 'PackageList',
              // keys: {},
              label: 'name',
              value: 'name',
              listParam: 'keys',
              keysParam: 'package',
              innerListParams: {
                model: '/model/reflection/Model',
                dataSource: 'FullList',
                keys: {},
                label: 'name',
                value: 'smallName',
              }
            }
            this.listValue = 'hosl'
          }
        },
      },
    }
  })
</script>


<script>
  var parser = new Siviglia.UI.HTMLParser();
  parser.parse($(document.body));
</script>
</body>
</html>
