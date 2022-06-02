<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>tree test</title>
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
<?php include_once("../../../../jQuery/JqxWidgets.html"); ?>
<?php include_once("../../../../jQuery/JqxLists.html"); ?>
<?php include_once("../../../../jQuery/Visual.html"); ?>
<?php include_once("../../../../jQuery/JqxViews.html"); ?>
<?php include_once("../../../../jQuery/JqxTypes.html"); ?>
<?php include_once("../../../../jQuery/JqxApp.html"); ?>
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
    <!-- Widget base -->
    <div data-sivWidget="Siviglia.Widgets.Lists.Tree" data-widgetCode="Siviglia.Widgets.Lists.Tree">
        <div data-sivId="containerNode"></div>
    </div>


    <!-- Widget derivado -->
    <div data-sivWidget="Siviglia.Widgets.Lists.ReflectionTree" data-widgetCode="Siviglia.Widgets.Lists.ReflectionTree">
        <div data-sivId="containerNode"></div>
    </div>
</div>

<div data-sivView="Siviglia.Widgets.Lists.ReflectionTree"></div>


<script>
  var theData = [{
    "resource": "Package",
    "name": "ads",
    "children": [{
      "resource": "Folder",
      "name": "Models",
      "children": [{
        "package": "ads",
        "namespace": "\\model\\ads\\Comscore",
        "model": "\\model\\ads\\Comscore",
        "item": "Comscore",
        "resourcePath": "",
        "internalPath": "\/Comscore.php",
        "modelPath": "\/model\/ads\/objects\/Comscore",
        "path": "\/model\/ads\/objects\/Comscore\/Comscore.php",
        "class": "\\model\\ads\\Comscore",
        "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/\/Comscore.php",
        "resource": "Model",
        "label": "Model",
        "extension": "php",
        "resourceType": "class",
        "subPath": "\/",
        "name": "Comscore",
        "children": [{
          "resource": "Folder",
          "name": "Datasources",
          "children": [{
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "DemographicProfile",
            "resourcePath": "",
            "internalPath": "\/datasources\/DemographicProfile.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/DemographicProfile.php",
            "class": "\\model\\ads\\Comscore\\datasources\\DemographicProfile",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/DemographicProfile.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "DemographicProfile"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "DemographicReport",
            "resourcePath": "",
            "internalPath": "\/datasources\/DemographicReport.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/DemographicReport.php",
            "class": "\\model\\ads\\Comscore\\datasources\\DemographicReport",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/DemographicReport.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "DemographicReport"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "FrequencyReport",
            "resourcePath": "",
            "internalPath": "\/datasources\/FrequencyReport.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/FrequencyReport.php",
            "class": "\\model\\ads\\Comscore\\datasources\\FrequencyReport",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/FrequencyReport.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "FrequencyReport"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "KeyMeasures",
            "resourcePath": "",
            "internalPath": "\/datasources\/KeyMeasures.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/KeyMeasures.php",
            "class": "\\model\\ads\\Comscore\\datasources\\KeyMeasures",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/KeyMeasures.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "KeyMeasures"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "SearchMedia",
            "resourcePath": "",
            "internalPath": "\/datasources\/SearchMedia.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/SearchMedia.php",
            "class": "\\model\\ads\\Comscore\\datasources\\SearchMedia",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/SearchMedia.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "SearchMedia"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "TestService",
            "resourcePath": "",
            "internalPath": "\/datasources\/TestService.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/datasources\/TestService.php",
            "class": "\\model\\ads\\Comscore\\datasources\\TestService",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/datasources\/TestService.php",
            "resource": "Datasource",
            "label": "Datasources",
            "extension": "php",
            "resourceType": "class",
            "enrutable": true,
            "router": "datasource",
            "subPath": "\/datasources",
            "name": "TestService"
          }]
        }, {
          "resource": "Folder",
          "name": "Types",
          "children": [{
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "BaseType",
            "resourcePath": "",
            "internalPath": "\/types\/BaseType.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/types\/BaseType.php",
            "class": "\\model\\ads\\Comscore\\types\\BaseType",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/types\/BaseType.php",
            "resource": "Type",
            "label": "Types",
            "extension": "php",
            "resourceType": "class",
            "subPath": "\/types",
            "name": "BaseType"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "Boolean",
            "resourcePath": "",
            "internalPath": "\/types\/Boolean.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/types\/Boolean.php",
            "class": "\\model\\ads\\Comscore\\types\\Boolean",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/types\/Boolean.php",
            "resource": "Type",
            "label": "Types",
            "extension": "php",
            "resourceType": "class",
            "subPath": "\/types",
            "name": "Boolean"
          }, {
            "package": "ads",
            "namespace": "\\model\\ads\\Comscore",
            "model": "\\model\\ads\\Comscore",
            "item": "Month",
            "resourcePath": "",
            "internalPath": "\/types\/Month.php",
            "modelPath": "\/model\/ads\/objects\/Comscore",
            "path": "\/model\/ads\/objects\/Comscore\/types\/Month.php",
            "class": "\\model\\ads\\Comscore\\types\\Month",
            "file": "\/mnt\/c\/dev\/adtopy\/\/model\/ads\/objects\/Comscore\/types\/Month.php",
            "resource": "Type",
            "label": "Types",
            "extension": "php",
            "resourceType": "class",
            "subPath": "\/types",
            "name": "Month"
          }]
        }]
      },]
    }]
  }]
  Siviglia.Utils.buildClass({
    context: 'Siviglia.RenderEngineInterface.jQWidgets.Lists',
    classes: {
      Tree: {
        inherits: 'Siviglia.Dom.EventManager',
        construct: function (params) {
          this.config = params.config
          this.node = params.node
          this.data = params.data
          this.dataIndexField = params.dataIndexField
          this.jqxConfig = this.createJqxConfig()
        },
        methods: {
          render: function () {
            this.node.jqxTree({source: this.jqxConfig})
          },
          createJqxConfig: function () {
            return this.createBranchConfig(this.data)
          },
          createBranchConfig: function (data) {
            var branchConfig = []

            // no puede emplearse for (var element of data)
            // devuelve excepción porque data es Symbol
            for (var elementIndex=0; elementIndex<data.length; elementIndex++)
              branchConfig.push(this.createItemConfig(this.config[data[elementIndex][this.dataIndexField]], data[elementIndex]))

            return branchConfig
          },
          createItemConfig: function (config, data) {
            var itemConfig = {}

            // no puede emplearse if (Siviglia.isString(data))
            // devuelve excepción porque data es Symbol
            if (typeof(data) === 'string')
              itemConfig.label = data
            else if (config.field)
              itemConfig.label = data[config.field]
            else {
              var content = ''
              var prefix = ''
              var suffix = ''
              for (var contentConfig of config.content)
                content += this.createItemElement(contentConfig, data) + ' '
              if (config.prefix) {
                for (var prefixConfig of config.prefix)
                  prefix += this.createItemElement(prefixConfig, data) + ' '
              }
              if (config.suffix) {
                for (var suffixConfig of config.suffix)
                  suffix += this.createItemElement(suffixConfig, data) + ' '
              }

              itemConfig.label = prefix + content + suffix
              itemConfig.id = Siviglia.issetOr(config.id, null)
              itemConfig.value = Siviglia.issetOr(config.value, null)
            }
            if (data.children)
              itemConfig.items = this.createBranchConfig(data.children)

            return itemConfig
          },
          createItemElement: function (config, data) {
            var content = config.field ? data[config.field] : config.content
            var element
            switch (config.type) {
              case 'text':
                element = `<span id=\'${Siviglia.createID()}\' ${config.color ? 'style=\'color:' + config.color + '\'' : ''}>${content}</span>`
                break
              case 'image':
                if (!config.height) config.height = '16px'
                if (!config.width) config.width = '16px'
                element = `<img id=\'${Siviglia.createID()}\' src=\'${content}\' style=\'height:${config.height};width:${config.width}\'>`
                break
              case 'button':
                element = `<button>${content}</button>`
                break
              case 'icon':
                element = `<span id=\'${Siviglia.createID()}\' class=\'${content}\'></span>`
            }
            return element
          },
        }
      },
    }
  })
  Siviglia.Utils.buildClass({
    context: 'Siviglia.Widgets.Lists',
    classes: {
      Tree: {
        inherits: 'Siviglia.UI.Expando.View,Siviglia.Dom.EventManager',
        methods: {
          preInitialize: function (params) {
            this.renderEngine = params.renderEngine
            this.config = params.config
            this.dataIndexField = params.dataIndexField
            this.data

            this.dataSource = new Siviglia.Model.DataSource(
              params.dataSource.model,
              params.dataSource.name,
              Siviglia.issetOr(params.dataSource.params, {})
            )
            this.dataSource.freeze()
            this.dataSource.addListener('CHANGE', this, 'refreshTree')
            return this.dataSource.unfreeze().then(function () {
              this.data = this.dataSource.getRawData()
            }.bind(this))
          },
          initialize: function () {
            this.render()
          },
          render: function () {
            this.implementation = new Siviglia.RenderEngineInterface[this.renderEngine].Lists.Tree({
              node: this.containerNode,
              config: this.config,
              data: this.data,
              dataIndexField: this.dataIndexField,
            })
            this.implementation.render()
          },
        }
      },
      ReflectionTree: {
        inherits: 'Siviglia.Widgets.Lists.Tree',
        methods: {
          preInitialize: function () {
            this.definition = {
              renderEngine: 'jQWidgets',
              dataSource: {
                model: '/model/reflection/ReflectorFactory',
                name: 'fullTree',
                // params: {},
              },
              dataIndexField: 'resource',
              /*
              * Para configurar una hoja se puede hacer de 2 formas:
              * - pasar un objeto:
              *     {field: 'nombre del campo'}
              *     indica qué campo de los datos toma se emplea para generar el contenido
              * - pasar un objeto:
              *     {
              *      content: [
              *                 {
              *                type: [text, image, button],
              *                field/content: [campo del que tomar valor / valor del campo],
              *                resto de parámetros del tipo:
              *                     text -> color
              *                     image -> height, width
              *                     button -> ninguno
              *                 },
              *                 ...
              *               ]
              *      prefix: igual que content
              *      suffix: igual que content
              *     }
              * */
              config: {
                Package: {field: 'name'},
                Folder: {field: 'name'},
                Model: {
                  content: [
                    {type: 'text', field: 'item', color: 'blue'}
                  ],
                  prefix: [{
                    type: 'image',
                    content: 'http://statics.adtopy.com/packages/Siviglia/tests/assets/home.png'
                  }],
                  suffix: [
                    {type: 'icon', content: 'Dictionary-removeButton'},
                    {type: 'icon', content: 'NewItem-addButton'},
                  ]
                },
                Datasource: {field: 'item'},
                Type: {field: 'item'},
                ModelConfig: {field: 'name'},
                Action: {field: 'name'},
                HtmlView: {field: 'item'},
                HtmlForm: {field: 'item'},
                JsForm: {field: 'item'},
                Cache: {field: 'item'},
                JsApp: {field: 'item'},
                JsModel: {field: 'item'},
                JsView: {field: 'item'},
                Worker: {field: 'name'},
              }
            }
            /* Como el dataSource fullTree tiene una respuesta diferente a la habitual, no se puede emplear y hay que
            * ajustar la respuesta
            * this.Tree$preInitialize(this.definition)
            * Se toma el código de Tree$preInitialize*/
            this.renderEngine = this.definition.renderEngine
            this.config = this.definition.config
            this.dataIndexField = this.definition.dataIndexField
            this.data

            this.dataSource = new Siviglia.Model.DataSource(
              this.definition.dataSource.model,
              this.definition.dataSource.name,
              Siviglia.issetOr(this.definition.dataSource.params, {})
            )
            this.dataSource.freeze()
            this.dataSource.addListener('CHANGE', this, 'refreshTree')
            return this.dataSource.unfreeze().then(function () {
              /*la siguiente línea tendría que ser: this.data = this.dataSource.getRawData()
              * se deja de esta forma para que funcione ReflectionTree*/
              this.data = this.dataSource.getRawData()[0].root.children
            }.bind(this))
          },
          initialize: function () {
            this.Tree$initialize()
          },
        }
      }
    }
  })
</script>


<script>
  var parser = new Siviglia.UI.HTMLParser();
  parser.parse($(document.body));
</script>
</body>
</html>
