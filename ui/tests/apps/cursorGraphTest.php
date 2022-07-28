<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cursor graphic</title>
    <script src='http://statics.adtopy.com/node_modules/d3/dist/d3.js'></script>
    <script src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js"></script>
    <script src="http://statics.adtopy.com/node_modules/autobahn-browser/autobahn.min.js"></script>

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


<div data-sivWidget="Test.FilterForm" data-widgetCode="Test.FilterForm">
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"id","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"parent","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"type","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"status","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"start","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"end","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
    <div class="widField">
        <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
             data-sivParams='{"key":"rowsProcessed","controller":"*self","parent":"*type","form":"*form"}'></div>
    </div>
</div>

<div data-sivWidget="Test.CursorNode" data-widgetParams="" data-widgetCode="Test.CursorNode">
    <div id="cursorContainer" data-sivValue="class|cursorState_[%*status%]">
        <div>
            <span data-sivValue="class|cursor [%*packageAndModel%]-cursors [%*name%]"></span>
            <span data-sivValue="/*name"></span>
            <div data-sivValue="[[%*rowsProcessed%]]"></div>
            <div data-sivValue="class|iconStatusCursor_[%*status%]::title|[%*statusLabel%]"></div>
        </div>
        <div class="extra_info">
            <div data-sivValue="/*id"></div>
            <div data-sivValue="/*startDate"></div>
            <div data-sivValue="/*fileName"></div>
            <div data-sivIf="[%*errored%] == true">
                <div class="cursor errorLabel" data-sivValue="/*errorLabel"></div>
            </div>
        </div>
    </div>
</div>

<div data-sivWidget="Test.CursorsGraph" data-widgetCode="Test.CursorsGraph">
    <svg data-sivId="svgNode" style="width: 100%; height: 100%"></svg>
</div>

<div data-sivWidget="Test.CursorGrid" data-widgetCode="Test.CursorGrid">
    <div data-sivId="filterNode"></div>
    <div data-sivId="grid"></div>
</div>

<div data-sivWidget="Test.AppController" data-widgetCode="Test.AppController">
    <div data-sivView="Test.CursorGrid" data-sivParams="{}"></div>
    <div data-sivId="graphicArea" style="width: 100%; height:100%"></div>
</div>

<div data-sivView="Test.AppController" data-sivParams="{}"></div>

<script>
  Siviglia.Utils.buildClass({
    context: 'Test',
    classes: {
      "CursorNode": {
        inherits: "Siviglia.UI.Expando.View",
        destruct: function () {
          this.item["*status"].removeListeners(this);
        },
        methods: {
          preInitialize: function (params) {
            this.item = params.item;
            if (params.item.cursorDefinition !== null && typeof params.item.cursorDefinition.fileName !== 'undefined')
              this.fileName = params.item.cursorDefinition.fileName.split('\\').pop().split('/').pop();
            else
              this.fileName = '---';
            this.id = params.item.id;
            this.name = (params.item.name == null) ? params.item.type.split("\\").pop() : params.item.name;
            this.status = params.item.status;
            this.rowsProcessed = params.item.rowsProcessed;
            this.startDate = params.item.start;
            this.errored = params.item.error == null;
            this.errorLabel = params.item.error == null ? '' : params.item.error;
            this.statusLabel = '';

            Siviglia.Path.eventize(params.item, 'status');
            params.item['*status'].addListener('CHANGE', this, 'onStatusChange');

            // Siviglia.Path.eventize(params.item, 'error');
            // params.item['*error'].addListener('CHANGE', this, 'onErrorChange');

            // Dependiendo de la ruta del path del cursor, montar un path asociado a ese cursor
            // para asi definir los iconos asociados a los paquetes-modelos-lib o default
            this.path = params.item.type.split('\\');
            var firstPathElement = this.path[0];
            switch (firstPathElement) {
              case "lib":
                this.packageAndModel = "default";
                break;
              case "model":
                this.packageAndModel = this.path[2] + "_" + this.path[3]; // packageAndModel. Ej: ads_dfp
                break;
              default:
                this.packageAndModel = "default";
                break;
            }

            var cursorModelDefinition = Siviglia.Model.loader.getModelDefinition("/model/sys/Cursor");
            this.statusTypes = cursorModelDefinition.FIELDS.status.VALUES;
          },
          initialize: function (params) {
            this.updateStatus();
          },
          updateStatus: function () {
            this.statusLabel = this.statusTypes[this.item.status];
            this.status = this.item.status;
            this.rowsProcessed = this.item.rowsProcessed;
          },
          onStatusChange: function () {
            this.updateStatus();
          },
          updateError: function () {
            this.errorLabel = this.item.error;
          },
          onErrorChange: function () {
            this.updateError();
          },
        }
      },
      "CursorsGraph": {
        inherits: "Siviglia.visual.Force,Siviglia.Dom.EventManager",
        destruct: function () {
        },
        methods: {
          initialize: function (params) {
            this.cursorBuffer = {};
            this.cursorNodes = [];
            this.cursorLinks = [];
            this.Force$initialize(params);
            this.svg.append("svg:defs").selectAll("marker")
              .data(["end"])      // Different link/path types can be defined here
              .enter().append("svg:marker")
              .attr("id", "end")// This section adds in the arrows
              .attr("viewBox", "0 -5 10 10")
              .attr("refX", 15)
              .attr("refY", 0.5)
              .attr("markerWidth", 13)
              .attr("markerHeight", 13)
              .attr("orient", "auto")
              .append("svg:path")
              .attr('fill', '#999')
              .attr("d", "M0,-5L10,0L0,5");

            this.addListener('CURSOR_SENT', this, 'cursorReceiver');
          },
          cursorReceiver: function (eventName, cursor) {
            this.addCursorToGraph(cursor)
          },
          addCursorToGraph: function (cursor) {
            var cursorFromBuffer = this.cursorBuffer[cursor.id];
            var parent = cursor.parent;
            var container = cursor.container;
            var id = cursor.id;

            if (typeof cursorFromBuffer !== "undefined" && cursorFromBuffer !== null) {
              // Se updatean los posibles links..Se supone que nunca se va a cambiar un link,
              // solo se añaden...Es por eso que no se busca un link antiguo y se quita..
              if (cursorFromBuffer.parent !== parent)
                this.cursorLinks.push({source: parent, target: id, type: "parent"});
              if (cursorFromBuffer.container !== container)
                this.cursorLinks.push({source: container, target: id, type: "container"});

              for (var k in cursor)
                cursorFromBuffer[k] = cursor[k];
            } else {
              this.cursorBuffer[id] = cursor;
              this.cursorNodes.push(cursor);
              if (parent !== null)
                this.cursorLinks.push({source: parent, target: id, type: "parent"})
              if (container !== null)
                this.cursorLinks.push({source: container, target: id, type: "container"})
            }

            this.update();
          },
          getNodesAndLinks: function () {
            return {nodes: this.cursorNodes, links: this.cursorLinks};
          },
          updateLinks: function (links) {
            this.Force$updateLinks(links);
            this.graphLinks.attr("marker-end", "url(#end)");
            //this.graphLinks.attr('marker-end', function(d,i){ return 'url(#end)' })
          },
          getRenderedCursorIDs: function () {
            var cursorIDList = []
            for (var cursorID in this.cursorBuffer) {
              cursorIDList.push(cursorID)
            }
            return cursorIDList
          },
        }
      },
      "AppController": {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
          if (this.wampService) {
            this.wampService.call("com.adtopy.removeBusListener", [this.__identifier]);
          }
          this.cursorsGraph.destruct();
        },
        methods: {
          preInitialize: function (params) {
            this.modelView = null;
            this.currentItemView = null;
            this.editing = false;
            this.shown = "hidden";
            this.selectedIcon = "";
            this.selectedName = "";
            this.selectedModel = "";
            this.selectedSubModel = "";
            this.selectedResourceType = "";
            this.selectedClass = "";
            this.selectedFile = "";

            this.descendantDS = new Siviglia.Model.DataSource("/model/sys/Cursor", "ChildList", {});
            this.descendantDS.freeze()
          },
          initialize: function (params) {
            var stack = new Siviglia.Path.ContextStack();
            this.cursorsGraph = null;
            var cursorsGraphFactory = new Test.CursorsGraph(
              "Test.CursorsGraph",
              {
                parent: this,
                svgWidth: 600,
                svgHeight: 400,
                nodeWidget: 'Test.CursorNode',
                nodeWidth: 300,
                nodeHeight: 300,
                allowMultipleSelection: false,
                rowIdField: 'id',
                distanceLinks: 1 //parece no tener efecto visual
              },
              {},
              $("<div></div>"),
              stack,
            );
            cursorsGraphFactory.__build().then(function (instance) {
              this.cursorsGraph = instance;
              this.graphicArea.append(instance.rootNode);
            }.bind(this))

            this.addListener("ROW_CLICKED", this, "onRowSelected");
            this.addListener('GRID_READY', this, 'onGridReady')

            this.connectToBus();
          },
          onRowSelected: function (eventName, cursor) {
            this.descendantDS.params.id = cursor.id
            this.descendantDS.unfreeze()
              .then(
                function () {
                  this.sendCursorToGraph(cursor);
                  this.sendCursorDescendantsToGraph(this.descendantDS.getRawData())
                }.bind(this)
              )
          },
          sendCursorToGraph: function (cursor) {
            if (this.cursorsGraph)
              // Se envian 2 veces los datos para crear en la primera el cursor y
              // en la segunda sus relaciones, ya que estas solo se pueden definir
              // sobre elementos existentes.
              // ToDo: explorar la creacion de cursores y ver si se pueden crear a la vez los cursores y sus relaciones
              var preCursor = cursor;
            this.cursorsGraph.fireEvent('CURSOR_SENT', preCursor);
            this.cursorsGraph.fireEvent('CURSOR_SENT', cursor);
          },
          sendCursorDescendantsToGraph: function (descendants) {
            if (typeof descendants !== 'undefined' && descendants !== null) {
              for (var descendant of descendants) {
                this.sendCursorToGraph(descendant)
                if (descendant.children !== null)
                  this.sendCursorDescendantsToGraph(descendant.children)
              }
            }
          },
          connectToBus: function () {
            // Se pone un listener sobre cualquier cambio en reflection
            this.wampService = Siviglia.Service.get("wampServer");

            if (this.wampService) {
              this.__identifier = Siviglia.Model.getAppId();
              this.wampService.call(
                "com.adtopy.replaceBusListener",
                [{
                  channel: 'General',
                  path: '/model/sys/Cursor/*',
                  roles: 0xFFF,
                  // ids:[],
                  appId: this.__identifier,
                  userId: top.Siviglia.config.user.USER_ID
                }]
              );

              this.wampService.subscribe(
                'busevent',
                function (data) {
                  console.log('Received info through bus', data)
                  var channel = data[0];
                  var params = data[1];
                  var appData = data[2];
                  if (appData.appId === this.__identifier)
                    this.onCursorReceivedFromBus(params.data)
                }.bind(this)
              )
            }
          },
          onCursorReceivedFromBus: function (cursor) {
            var cursorIDs = this.getCursorIDsFromGraph()
            if (cursorIDs.includes(cursor.id) || cursorIDs.includes(cursor.parent) || cursorIDs.includes(cursor.container))
              this.sendCursorToGraph(cursor)
          },
          onItemSelected: function (evName, params) {
            this.showItemData(params.selection[0].d);

            // Se prepara el nombdget de edicion.
            // Si el nombre del recurso era "model", se carga Siviglia.Reflection.Model.
          },
          onSelectionEmpty: function () {
            if (this.currentItemView) {
              this.componentViewContainer.html("");
            }
            this.editing = false;
            this.shown = "hidden";
            //this.modelView.unselect(this.lastItemSelected.d);

          },
          onGridReady: function (event, grid) {
            debugger
            console.log('refreshing from controller')
            setInterval(console.log('ping'), 1000)
            setInterval(grid.BaseGrid$refreshGrid(), 3000)
          },
          closeComponentView: function () {
            this.onSelectionEmpty();
          },
          onBackgroundClicked: function () {
            this.onSelectionEmpty();
          },
          showItemData: function (d) {
            this.shown = "shown";

            var f = new Adtopy.reflection.ResourceMeta();
            var meta = f.getResourceMeta(d);
            this.selectedIcon = meta.icon;
            this.selectedName = typeof d.name === "undefined" ? d.class : d.name;
            this.selectedModel = typeof d.model === "undefined" ? "" : d.model;
            this.selectedSubModel = typeof d.submodel === "undefined" || d.submodel === null ? "" : d.submodel;
            this.selectedResourceType = d.resource;
            this.selectedClass = typeof d.class === "undefined" ? "" : d.class;
            this.selectedFile = typeof d.file === "undefined" ? "" : d.file;

          },
          getCursorIDsFromGraph: function () {
            return this.cursorsGraph.getRenderedCursorIDs()
          }
        }
      },
      "CursorGrid": {
        "inherits": "Siviglia.lists.jqwidgets.BaseGrid",
        "methods": {
          preInitialize: function (params) {
            this.BaseGrid$preInitialize({
              "filters": "Test.FilterForm",
              "ds": {
                "model": "/model/sys/Cursor",
                "name": "FullList",
                'params': {isDataProcess: '1'},
                "settings": {
                  pageSize: 10
                }
              },
              "columns": {
                "id": {"Type": "Field", "Field": "id", "Label": "Id", "gridOpts": {"width": "50%"}},
                "parent": {"Type": "Field", "Field": "parent", "Label": "Parent", "gridOpts": {"width": "10%"}},
                "container": {
                  "Type": "Field",
                  "Field": "container",
                  "Label": "Container",
                  "gridOpts": {"width": "10%"}
                },
                "name": {"Type": "Field", "Field": "name", "Label": "Name", "gridOpts": {"width": "10%"}},
                "Type": {"Type": "Field", "Field": "type", "Label": "Type", "gridOpts": {"width": "20%"}},
                // 'isDataProcess': {"Type": "Field", "Field":"isDataProcess", "Label":"root", "gridOpts":{"width":"20%"}},
                // "status":        {"Type": "Field", "Field":"status", "Label":"Status", "gridOpts":{"width":"5%"}},
                // "start":         {"Type": "Field", "Field":"start", "Label":"Start", "gridOpts":{"width":"5%"}},
                // "end":           {"Type": "Field", "Field":"end", "Label":"End", "gridOpts":{"width":"10%"}},
                // "rowsProcessed": {"Type": "Field", "Field":"rowsProcessed", "Label":"Filas Proc.", "gridOpts":{"width":"5%"}},
                // "error":         {"Type": "Field", "Field":"error", "Label":"Error text", "gridOpts":{"width":"10%"}},
                // "cursorDefinition": {"Type": "Field", "Field":"cursorDefinition", "Label":"Definition", "gridOpts":{"width":"5%"}},
              },
              "gridOpts": {width: "100%"}
            });
          },
          initialize: function (params) {
            this.BaseGrid$initialize(params);

            this.grid.on("cellclick", function (eventData) {
              var gridRowData = eventData.args.row.bounddata;
              this.__parentView.fireEvent("ROW_CLICKED", gridRowData);
            }.bind(this));

            this.grid.on("initialized", function () {
              setInterval(function () {
                this.refreshGrid()
              }.bind(this), 15000)
            }.bind(this));
          },
          refreshGrid: function () {
            this.ds.unfreeze().then(() => {
              this.BaseGrid$refreshGrid()
            })
          },
        }
      },
      "FilterForm": {
        "inherits": "Siviglia.lists.jqwidgets.BaseFilterForm",
        "methods": {}
      },
    },
  })
</script>


<script>
  var parser = new Siviglia.UI.HTMLParser();
  parser.parse($(document.body));
</script>
</body>
</html>