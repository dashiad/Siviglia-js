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
    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/backend/css/style.css">

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
        <div data-sivId="treeContainer"></div>
    </div>


    <!-- Widget derivado -->
    <div data-sivWidget="Siviglia.Widgets.Lists.ReflectionTree" data-widgetCode="Siviglia.Widgets.Lists.ReflectionTree">
        <div data-sivId="treeContainer"></div>
    </div>
</div>

<div data-sivView="Siviglia.Widgets.Lists.ReflectionTree"></div>


<script>
  Siviglia.Utils.buildClass({
    context: 'Siviglia.Widgets.Lists',
    classes: {
      Tree: {
        inherits: 'Siviglia.UI.Expando.View,Siviglia.Dom.EventManager',
        methods: {
          preInitialize: function (params) {
            this.renderEngine = params.renderEngine
            this.treeData

            this.dataSource = new Siviglia.Model.DataSource(
              params.dataSource.model,
              params.dataSource.name,
              Siviglia.issetOr(params.dataSource.params, {})
            )
            this.dataSource.freeze()
            this.dataSource.addListener('CHANGE', this, 'refreshTree')
          },
          initialize: function () {
            this.dataSource.unfreeze().then(function () {
              this.treeData = this.dataSource.getRawData()[0].root.children
              this.createTreeConfig()
              this.buildTree()
            }.bind(this))
          },
          buildTree: function () {
            this.implementation = new Siviglia.RenderEngineInterface[this.renderEngine].Lists.Tree({
              node: this.treeContainer,
              config: this.treeConfig,
            })
            this.implementation.build()
          },
        }
      },
      ReflectionTree: {
        inherits: 'Siviglia.Widgets.Lists.Tree',
        methods: {
          preInitialize: function () {
            this.treeDefinition = {
              renderEngine: 'jQWidgets',
              dataSource: {
                model: '/model/reflection/ReflectorFactory',
                name: 'fullTree',
                // params: {},
              },
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
              leaves: {
                Package: {field: 'name'},
                Folder: {field: 'name'},
                Model: {
                  content: [
                    {type: 'text', field: 'item', color: 'blue'}
                  ],
                  prefix: [{type: 'image', content: 'http://statics.adtopy.com/packages/Siviglia/tests/assets/home.png'}],
                  suffix: [
                    {type: 'button', content: 'Add'}
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
            this.Tree$preInitialize(this.treeDefinition)
          },
          initialize: function () {
            this.Tree$initialize()
          },
          createTreeConfig: function () {
            this.treeConfig = this.createBranch(this.treeData)
          },
          createBranch: function (data) {
            var branch = []
            for (var leafIndex = 0; leafIndex < data.length; leafIndex++) {
              branch.push(this.createLeaf(data[leafIndex]))
            }
            return branch
          },
          createLeaf: function (data) {
            // var leafID = Siviglia.issetOr(config.idField, null)
            // var leafValue = Siviglia.issetOr(config.valueField, null)
            var leafContent = this.createLeafContent(data)
            var leaf = {label: leafContent}
            if (data.children) {
              leaf.children = this.createBranch(data.children)
            }
            return leaf
          },
          createLeafContent: function (data) {
            var leafDefinition = this.treeDefinition.leaves[data.resource]
            if (leafDefinition.field)
              return data[leafDefinition.field]

            var content = ''
            var prefix = ''
            var suffix = ''
            for (var contentDefinition of leafDefinition.content) {
              content += this.createLeafElement(contentDefinition, data) + ' '
            }

            if (leafDefinition.prefix) {
              for (var prefixDefinition of leafDefinition.prefix) {
                prefix += this.createLeafElement(prefixDefinition, data) + ' '
              }
            }

            if (leafDefinition.suffix) {
              for (var suffixDefinition of leafDefinition.suffix) {
                suffix += this.createLeafElement(suffixDefinition, data) + ' '
              }
            }

            return prefix + ' ' + content + ' ' + suffix
          },
          createLeafElement: function (config, data) {
            var content = config.field ? data[config.field] : config.content
            var element
            switch (config.type) {
              case 'text':
                element = `<span ${config.color ? 'style=\'color:' + config.color + '\'' : ''}>${content}</span>`
                break
              case 'image':
                if (!config.height) config.height = '16px'
                if (!config.width) config.width = '16px'
                element = `<img src=\'${content}\' style=\'height:${config.height};width:${config.width}\'>`
                break
              case 'button':
                element = `<button>${content}</button>`
                break
            }
            return element
          }
        }
      }
    }
  })

  Siviglia.Utils.buildClass({
    context: 'Siviglia.RenderEngineInterface.jQWidgets.Lists',
    classes: {
      Tree: {
        inherits: 'Siviglia.Dom.EventManager',
        construct: function (params) {
          this.config = params.config
          this.node = params.node
          this.jqxConfig = this.createJqxConfig(this.config)
        },
        methods: {
          build: function () {
            this.node.jqxTree({source: this.jqxConfig})
            $(this.node).on('click', function () {
              var item = $(this.node).jqxTree('getSelectedItem');
              this.fireEvent('SELECTED', {element: item})
            }.bind(this))
          },
          createJqxConfig: function (config) {
            return this.createBranchConfig(config)
          },
          createBranchConfig: function (config) {
            var branchConfig = []

            for (const element of config) {
              branchConfig.push(this.createLeafConfig(element))
            }

            return branchConfig
          },
          createLeafConfig: function (config) {
            var itemConfig = {}
            var prefix = ''
            var suffix = ''

            if (typeof config.label === 'string')
              itemConfig.label = config.label
            else {
              if (config.label.prefix)
                prefix = this.createItemContent(config.label.prefix)
              if (config.label.suffix)
                suffix += this.createItemContent(config.label.suffix)
              var content = this.createItemContent(config.label.content)

              itemConfig.label = prefix + content + suffix
              itemConfig.id = Siviglia.issetOr(config.id, null)
              itemConfig.value = Siviglia.issetOr(config.value, null)
            }
            if (config.children)
              itemConfig.items = this.createBranchConfig(config.children)

            return itemConfig
          },
          createItemContent: function (config) {
            var content = ''
            for (const element of config) {
              switch (element.type) {
                case 'text':
                  content += `<span ${element.color ? 'style=\'color:' + element.color + '\'' : ''}>${element.content}</span>`
                  break
                case 'image':
                  if (!element.height) element.height = '16px'
                  if (!element.width) element.width = '16px'
                  content += `<img src=\'${element.src}\' style=\'height:${element.height};width:${element.width}\'>`
                  break
                case 'button':
                  content += `<button>${element.label}</button>`
                  break
              }
            }
            return content
          },
        }
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
