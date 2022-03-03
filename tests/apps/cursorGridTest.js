runTest("Grid de Cursores",
  "Test que muestra un Grid de los cursores de la base de datos",
  '<div data-sivWidget="Test.CursorTree_GridForm" data-widgetCode="Test.CursorTree_GridForm">' +
  '<div class="widListForm Siviglia_sys_Cursor_lists_Test_CursorTree_GridForm">' +
  '<div class="widListFormFieldSet">' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"id"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"parent"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"type"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"status"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"start"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"end"}\'></div>' +
  '</div>' +
  '<div class="widField">' +
  '<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"rowsProcessed"}\'></div>' +
  '</div>' +
  '</div>' +
  '</div>' +
  '</div>' +

  '<div data-sivWidget="Test.CursorTree_Grid" data-widgetCode="Test.CursorTree_Grid"><div>' +

  '<div data-sivId="filterNode"></div>' +
  '<div data-sivId="grid"></div>' +
  '</div></div>' +
  '<div data-sivWidget="Test.CursorTree_Controller" data-widgetCode="Test.CursorTree_Controller">' +
  '<div data-sivView="Test.CursorTree_Grid" data-viewName="grid"></div>' +
  '<div data-sivId="cursorGraphNode"></div>' +

  '</div>'
  ,

  '<div data-sivView="Test.CursorTree_Controller"></div>',
  function () {
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
  }
)

checkTests()