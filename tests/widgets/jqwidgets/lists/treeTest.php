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


<!-- Implementación de widget base -->
    <div data-sivWidget="tree-test" data-widgetCode="Test.TreeTest">
        <div data-sivView="Siviglia.Widgets.Lists.Tree" data-sivParams='{"renderEngine":"*renderEngine","config":"*config"}'></div>
    </div>
</div>

<div data-sivView="tree-test"></div>


<script>
  Siviglia.Utils.buildClass({
    context: 'Test',
    classes: {
      TreeTest: {
        inherits: 'Siviglia.UI.Expando.View',
        methods: {
          preInitialize: function () {
            this.renderEngine = 'jQWidgets'
            this.config = [
              {
                id: 1,
                value: 1,
                suffix: [
                  {
                    type: 'image',
                    src: 'http://statics.adtopy.com/packages/Siviglia/tests/assets/home.png'
                  }
                ],
                content: [
                  {
                    type: 'text',
                    content: 'soy el primero ',
                    color: 'red'
                  },
                ],
              },
              {
                id: 2,
                value: 1,
                suffix: [
                  {
                    type: 'image',
                    src: 'http://statics.adtopy.com/packages/Siviglia/tests/assets/broadcast.png'
                  }
                ],
                content: [
                  {
                    type: 'text',
                    content: 'soy el segundo ',
                    color: 'blue'
                  },
                ],
                children: [
                  {
                    id: 3,
                    value: 1,
                    suffix: [
                      {
                        type: 'image',
                        src: 'http://statics.adtopy.com/packages/Siviglia/tests/assets/image.png'
                      }
                    ],
                    content: [
                      {
                        type: 'text',
                        content: 'soy el primer hijo ',
                        color: 'purple'
                      },
                    ],
                  },
                  {
                    content: 'holas'
                  }
                ]
              },
            ]
          }
        }
      }
    }
  })

  Siviglia.Utils.buildClass({
    context: 'Siviglia.Widgets.Lists',
    classes: {
      Tree: {
        inherits: 'Siviglia.UI.Expando.View,Siviglia.Dom.EventManager',
        methods: {
          preInitialize: function (params) {
            this.config = params.config
            this.renderEngine = params.renderEngine
          },
          initialize: function (params) {
            this.implementation = new Siviglia.RenderEngineInterface[this.renderEngine].Lists.Tree({node: this.treeContainer, config: this.config})
            this.implementation.build()
            this.implementation.addListener('SELECTED', this, 'onSelected')
          },
          onSelected: function (event, params) {
            console.log(params)
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
            $(this.node).on('click', function(){
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
              branchConfig.push(this.createLeaveConfig(element))
            }

            return branchConfig
          },
          createLeaveConfig: function (config) {
            var itemConfig = {}
            var prefix = ''
            var suffix = ''

            if (typeof config.content === 'string')
              itemConfig.label = config.content
            else {
              if (config.prefix)
                prefix = this.createItemContent(config.prefix)
              if (config.suffix)
                suffix += this.createItemContent(config.suffix)
              var content = this.createItemContent(config.content)

              itemConfig.label = prefix + content + suffix
              itemConfig.id = config.id
              itemConfig.value = config.value
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
