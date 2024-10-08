
Siviglia.Utils.buildClass(
  {
    context: 'Siviglia.lists.jqwidgets',
    classes: {
      BaseFilterForm: {
        inherits: 'Siviglia.inputs.jqwidgets.Form',
        destruct: function () {
          for (var k = 0; k < this.registeredInputs.length; k++) {
            this.registeredInputs[k].type.removeListeners(this);
          }
        },
        methods: {
          initialize: function (params) {
            this.Form$initialize(params);
            for (var k = 0; k < this.registeredInputs.length; k++) {
              this.registeredInputs[k].type.addListener("CHANGE", this, "onInputChanged")
            }

          },
          onInputChanged: function () {
            //console.dir(params);
          }
        }
      },
      BaseGrid: {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
          this.cleanUp();
          if (this.grid)
            this.grid.jqxGrid('destroy');
          if (this.contextMenu)
            this.contextMenu.jqxMenu('destroy');

        },
        methods: {
          preInitialize: function (params) {
            // Cosas a tener en cuenta:
            // 1) Modos de visualizacion: Grid / Grafica, dependiendo de
            // si los campos estan marcados como dimensiones, o como metricas.

            // 2) Paginacion versus datasource: el datasource tiene que tener
            // paginacion virtual

            // 3) Datasources como simples arrays: es extraordinariamente parecido a sources,
            // pero sin deinir campos clave->valor.

            // 4) Selecciones locales, deteccion de campos clave de modelos.
            // 5) Acciones de grupo, y acciones de item, segun los permisos que tenga el usuario actual.
            /*
                {
                    ds:{
                        model,
                        name,
                        params
                    },
                    settings:{
                       pageSize:
                    },
                    // Opciones a hacer merge con jqxGrid
                    gridOpts:{
                    }
                }

             */
            // Especificacion de columnas:
            // SIEMPRE: Label, Type, y, dependiendo del tipo, los parametros necesarios.
            // Hay varios casos en los que los elementos tienen que dibujarse a posteriori, como widgets y
            // botones. En ambos casos, se va a crear un div con un id unico, y, en el evento loadComplete, se
            // insertará el código necesario.
            // Para ello, se genera un prefijo para los ids de los divs, a los que se les aniade un contador
            this.filterPromise = $.Deferred();
            this.gridPromise = $.Deferred();
            //this.__addDependency(this.filterPromise);
            //this.__addDependency(this.gridPromise);
            this.divPrefix = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'e', 'f'][parseInt(Math.random() * 10)] + (new Date()).getTime();
            this.divCounter = 0;
            this.futureWidgetData = [];
            this.builtWidgets = {};
            this.actualColumns = [];
            this.contextualActions = [];
            this.columnSpec = [];
            this.contextMenu = null;
            this.formWidgetName = typeof params.filters === "undefined" ? null : params.filters;

            this.jqgrid = null;
            /*
                Inicializacion del datasource
            */
            this.ds = new Siviglia.Model.DataSource(params.ds.model,
              params.ds.name,
              typeof params.ds.params == "undefined" ? {} : params.ds.params);

            this.ds.freeze();
            var gtime = new Date().getTime();
            this.ds.settings.__start = 0;
            this.ds.settings.__count = typeof params.ds.settings.pageSize == "undefined" ? 20 : params.ds.settings.pageSize;
            this.ds.addListener("CHANGE", this, "refreshGrid");

            this.parameters = this.ds["*params"];
            /*
                Inicializacion de las columnas a mostrar
            */
            this.columnSpec = params.columns;

            /*
                Inicializacion del formulario de filtro
            */
            /*this.parameters.__definition.INPUTPARAMS={
                "/":{
                    "INPUT": "FlexContainer"
                }
            }*/
            /*
                Inicializacion de parametros pasados a jqxGrid
             */
            this.gridOpts = Siviglia.issetOr(params.gridOpts, {});

            var modelFields = {};
            // Iteramos para ver sobre que modelos tenemos todos los indices en la especificacion de columnas.
            for (var k in this.columnSpec) {
              var curIt = this.columnSpec[k];
              if (typeof curIt['visible'] === "undefined" || curIt['visible'] == true) {
                // Primero se crea una configuracion por defecto, que luego es sobreescrita con lo
                // que posiblemente nos enviara la clase derivada
                var colSpec = null;

                switch (curIt['Type']) {
                  case "Field": {
                    // Obtenemos la definicion del campo a traves del ds.
                    var typeDef = this.ds["*data"].__definition["ELEMENTS"].FIELDS[curIt.Field];
                    // Obtenemos una instancia del tipo de la columna

                    var ins = Siviglia.types.TypeFactory.getType({
                      fieldName: curIt.Field,
                      fieldPath: "/"
                    }, typeDef, null, null);
                    var processedDef = ins.__definition;
                    if (typeof processedDef.references !== "undefined") {
                      var model = processedDef.references["MODEL"];
                      var field = processedDef.references["FIELD"];
                      if (typeof modelFields[model] === "undefined")
                        modelFields[model] = {};
                      modelFields[model][field] = curIt.Field;
                      // Se mira si es una relacion.
                      // Si es una relacion, ademas del campo local, se guarda el campo apuntado por la relacion.
                      if (processedDef["TYPE"] == "Relationship") {
                        if (typeof modelFields[processedDef["MODEL"]] == "undefined")
                          modelFields[processedDef["MODEL"]] = {};
                        for (var j in processedDef["FIELDS"]) {
                          modelFields[processedDef["MODEL"]][processedDef["FIELDS"][j]] = curIt.Field;
                        }
                      }
                    }
                  }
                    break;
                }
              }

            }
            // En este punto, tenemos los campos que hay en las columnas, y podemos buscar que
            // modelos hay, y si los campos indices de los modelos esta presente.
            for (var curModel in modelFields) {
              var def = Siviglia.Model.loader.getModelDefinition(curModel);
              var indexes = typeof def["INDEXFIELDS"] !== "undefined" ? def["INDEXFIELDS"] : null;
              if (indexes) {
                // El modelo tiene indices.Vamos a ver si todos los indices estan incluidos en el listado
                var complete = true;
                var indexMap = {};
                for (var k = 0; k < indexes.length; k++) {
                  if (typeof modelFields[curModel][indexes[k]] === undefined) {
                    complete = false;
                    break;
                  }
                  indexMap[indexes[k]] = modelFields[curModel][indexes[k]];
                }
                if (complete) {

                  this.contextualActions.push({model: curModel, indexMap: indexMap});
                }
              }
            }
            var completePromise = SMCPromise();
            if (this.contextualActions.length == 0) {
              completePromise.resolve(null);
              return completePromise; // No habia acciones contextuales.
            }


            var targets = [];
            // Se recogen todos los modelos que queremos enviar al datasource
            for (var k = 0; k < this.contextualActions.length; k++)
              targets.push(this.contextualActions[k].model);


            /*var actionDs=new Siviglia.Model.DataSource("/model/reflection/Action", "FullList", {model:targets,allowsBatch:true});
            actionDs.refresh().then(function(result){
               /!* for(var k=0;k<result.length;k++)
                {

                }
                m.contextualActions[index].actions=result.data;
                actionDs.destruct();*!/
                completePromise.resolve(result);
            }.bind(this))
            return completePromise;*/
          },
          initialize: function (params) {
            if (this.ds)
              this.ds.unfreeze().then(function () {
                this.refreshGrid()
              }.bind(this)); // LLama automaticamente a refresh
            this.buildFilters()
          },
          buildColumns: function () {
            // Especificacion de columnas:
            // 1) ColumnType: Selector | Field | Widget | Actions
            // 2) Visible: por defecto, es true.
            // 3) Segun el tipo,
            // Primero, deshabilitamos eventos en actualColumns
            if (this.actualColumns.hasOwnProperty("__ev__"))
              this.actualColumns.__ev__.disableEvents(true);
            this.actualColumns = [];
            var renderers = {
              "Field": function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                return row[columnField];
              }
            }

            for (var k in this.columnSpec) {
              var curIt = this.columnSpec[k];
              if (typeof curIt['visible'] === "undefined" || curIt['visible'] == true) {
                // Primero se crea una configuracion por defecto, que luego es sobreescrita con lo
                // que posiblemente nos enviara la clase derivada
                var colSpec = null;

                switch (curIt['Type']) {
                  default:
                  case "Field": {
                    colSpec = {
                      text: curIt.Label,
                      datafield: curIt.Field,
                      cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                        return "" + value;
                      }
                    };
                  }
                    break;
                  case "Custom": {
                    colSpec = curIt;

                  }
                    break;

                  case "PString": {
                    colSpec = (function (curIt) {
                      return {
                        sortable: false,
                        text: curIt.Label,
                        cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                          var stack = new Siviglia.Path.ContextStack();
                          var ctx = new Siviglia.Path.BaseObjectContext(rowdata, "*", stack);
                          var ps = new Siviglia.Path.ParametrizableString(curIt.str, stack);
                          var st = ps.parse();
                          ps.destruct();
                          return st;
                        }
                      }
                    })(curIt);
                  }
                    break;
                  case "Action": {

                  }
                    break;
                  case "Widget": {
                    var m = this;
                    colSpec = (function (curIt, divCounter) {
                      return {
                        sortable: false,
                        text: curIt.Label,
                        cellsrenderer: function (row, columnField, value, defaulthtml, columnproperties, rowdata) {
                          var current = m.divPrefix + "" + m.divCounter;
                          m.futureWidgetData.push({div: '#' + current, it: curIt, row: rowdata});
                          m.divCounter++
                          return '<div id="' + current + '"></div>';
                          /*var stack = new Siviglia.Path.ContextStack();
                          var ctx = new Siviglia.Path.BaseObjectContext(rowdata, "*", stack);
                          var dest=Siviglia.Utils.stringToContextAndObject(curIt.Widget);
                          // Como no permite acceso directo al nodo, vamos a intentarlo creando un div con un id,
                          // y luego
                          this.builtWidgets[]=*/

                        }
                      }
                    })(curIt, this.divCounter);


                  }
                    break;
                }
                if (typeof curIt.gridOpts !== "undefined") {
                  for (var s in curIt.gridOpts)
                    colSpec[s] = curIt.gridOpts[s];
                }
                this.actualColumns.push(colSpec);
              }
            }
            if (this.actualColumns.hasOwnProperty("__ev__")) {
              this.actualColumns.__ev__.disableEvents(false);
              this.actualColumns.__ev__.fireEvent("CHANGE", {});
            }


          },

          cleanUp: function () {
            for (var k in this.builtWidgets) {
              this.builtWidgets[k].destruct();
            }
            this.builtWidgets = {};
          },

          getColumn: function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return "";
            /*var idx=params.idx;
            var colDef=this.actualColumns[k];
            switch(colDef[k]["type"])
            {
                case "Data":
                default:{
                    node.html()
                }break;
            }*/
          },
          refreshGrid: function (evName, params) {
            // TODO : Por algun motivo, todavia se ejecutan requests extra en los autocompletar de los grids.
            // Hay que investigar por qué.
            if (this.jqgrid === null) {
              this.jqgrid = 1;
              return this.buildGrid();
            }
            var newData = this.ds.getRawData();
            if (newData === null)
              newData = [];
            this.jqxDataSource.localdata = newData;
            this.jqxDataSource.pagenum = this.ds.settings.__start;
            this.jqxDataSource.pagesize = this.ds.settings.__count;
            this.jqxDataSource.totalrecords = this.ds.count;
            this.dataAdapter.dataBind();
          },
          getDataSource: function () {
            return this.ds;
          },
          getForm: function () {
            return this.filterWidget;
          },
          buildGrid: function (evName, params) {
            this.jqxDataSource = {
              localdata: [],
              datatype: "array",
              cache: false,
              pagenum: this.ds.settings.__start,
              pagesize: this.ds.settings.__count,
              totalrecords: this.ds.count,


              pager: function (pagenum, pagesize, oldpagenum) {
                if (pageSize != this.ds.settings.__count) {
                  this.ds.freeze();
                  this.ds.settings.__count = pageSize;
                  this.ds.settings.__start = 0;
                  this.ds.unfreeze();
                  return;
                }
                if (pageNum != this.ds.settings.__start)
                  this.ds.settings.__start = pageNum;

              },
              //totalrecords: 1000000
            };
            this.dataAdapter = new $.jqx.dataAdapter(this.jqxDataSource);
            this.jqxDataSource.localdata = this.ds.getRawData();
            if (!this.noRowsNode) {
              this.noRowsNode = $("<div class=\"noRowsNode\">No rows</div>");
              this.grid.after(this.noRowsNode);

            }
            if (this.jqxDataSource.localdata === null || this.jqxDataSource.localdata.length === 0) {
              // No hay rows

              this.noRowsNode.css({display: "block"});
              this.grid.css({display: "none"});
              return;
            } else {
              this.noRowsNode.css({display: "none"});
              this.grid.css({display: "block"});
            }

            this.dataAdapter.dataBind();
            //var definition=this.ds.__getDefinition();
            var columns = this.buildColumns();
            /*for(var k in definition.FIELDS.data.ELEMENTS.FIELDS)
            {
                columns.push({text:k,datafield:k})
            }*/
            var baseOpt = {
              pageable: true,
              autoheight: true,
              //autorowheight:true,
              sortable: true,
              altrows: true,
              enabletooltips: true,
              editable: false,
              virtualmode: true,
              selectionmode: 'checkbox'
            };
            for (var k in this.gridOpts)
              baseOpt[k] = this.gridOpts[k];
            baseOpt.source = this.dataAdapter;
            baseOpt.columns = this.actualColumns;
            baseOpt.rendergridrows = function (obj) {
              return obj.data;
            };
            // Se prepara el callback para generar widgets,botones,etc
            var m = this;
            baseOpt.rendered = function () {
              m.onRendered()
            };

            this.grid.jqxGrid(baseOpt);
            this.grid.on("pagechanged", function (event) {
              this.ds.settings.__start = event.args.pagenum * this.ds.settings.__count;

            }.bind(this));
            this.grid.on("pagesizechanged", function (event) {
              this.ds.settings.__count = event.args.pagesize;
            }.bind(this));
            this.grid.bind("sort", function (event) {
              var sortinformation = event.args.sortinformation;

              this.ds.freeze();
              if (sortinformation.sortcolumn == null) {
                this.ds.settings.__sort = null;
                this.ds.settings.__sortDir = null;
              } else {
                this.ds.settings.__sort = sortinformation.sortcolumn;
                this.ds.settings.__sortDir = (sortinformation.sortdirection.ascending == true) ? 'ASC' : 'DESC';
              }

              this.ds.unfreeze();
            }.bind(this));
            this.grid.on("bindingcomplete", function (event) {

              this.onRendered();
            }.bind(this));
            this.grid.on("rowselect", function (event) {
              this.onRendered();
            }.bind(this));
            this.grid.on("rowunselect", function (event) {
              this.onRendered();
            }.bind(this));

            this.grid.on("initialized", function () {
              console.log("initialized");
            });
            this.grid.on("rowClick", function () {
              console.log("rowClick");
            });
            this.grid.on("rowSelect", function () {
              console.log("rowSelect");
            });
            this.grid.on("rowUnselect", function () {
              console.log("rowUnselect");
            });
            this.grid.on("sort", function () {
              console.log("sort");
            });
            this.grid.on("columnClick", function () {
              console.log("columnClick");
            });
            this.grid.on("cellClick", function () {
              console.log("cellClick");
            });
            this.grid.on("pageChanged", function () {
              console.log("pageChanged");
            });
            this.grid.on("pageSizeChanged", function () {
              console.log("pageSizeChanged");
            });
            this.grid.on("bindingComplete", function () {
              console.log("bindingComplete");
            });
            this.grid.on("groupsChanged", function () {
              console.log("groupsChanged");
            });
            this.grid.on("filter", function () {
              console.log("filter");
            });
            this.grid.on("columnResized", function () {
              console.log("columnResized");
            });
            this.grid.on("cellSelect", function () {
              console.log("cellSelect");
            });
            this.grid.on("cellUnselect", function () {
              console.log("cellUnselect");
            });
            this.grid.on("cellBeginEdit", function () {
              console.log("cellBeginEdit");
            });
            this.grid.on("cellEndEdit", function () {
              console.log("cellEndEdit");
            });
            this.grid.on("cellValueChanged", function () {
              console.log("cellValueChanged");
            });
            this.grid.on("rowExpand", function () {
              console.log("rowExpand");
            });
            this.grid.on("rowCollapse", function () {
              console.log("rowCollapse");
            });
            this.grid.on("rowDoubleClick", function () {
              console.log("rowDoubleClick");
            });
            this.grid.on("cellDoubleClick", function () {
              console.log("cellDoubleClick");
            });
            this.grid.on("columnReordered", function () {
              console.log("columnReordered");
            });
            this.grid.on("pageChanging", function () {
              console.log("pageChanging");
            });
            this.gridPromise.resolve();

            // COMIENZO DE MENU CONTEXTUAL
            /*
            if(this.contextualActions.length > 1) {
                $("#jqxMenu").jqxMenu({
                    width: '120px',
                    autoOpenPopup: false,
                    height: '90px',
                    mode: 'popup',
                    theme: 'light'
                });
                $("#jqxgrid").mousedown(function (event) {
                    // get the clicked cell.
                    var cell = $("#jqxgrid").jqxGrid('getCellAtPosition', event.pageX, event.pageY);
                    //select row.
                    if (cell != null && cell.row) {
                        $("#jqxgrid").jqxGrid('selectrow', cell.row);
                    }
                    var rightClick = isRightClick(event);
                    if (rightClick) {
                        var scrollTop = $(window).scrollTop();
                        var scrollLeft = $(window).scrollLeft();
                        contextMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
                        return false;
                    }
                });
                // disable the default browser's context menu.
                $(document).bind('contextmenu', function (e) {
                    return false;
                });

                function isRightClick(event) {
                    var rightclick;
                    if (!event) var event = window.event;
                    if (event.which) rightclick = (event.which == 3);
                    else if (event.button) rightclick = (event.button == 2);
                    return rightclick;
                }
            }

             */


          },
          /*
              La siguiente funcion tiene que llamarse cada vez que se termina de repintar el grid, sea porque se
              acaba de crear, sea porque se ha cambiado de página.

              Como en los callbacks de pintado de celdas, sólo se puede devolver un texto, no un nodo, no podemos
              crear widgets en esos callbacks, ya que requieren un nodo donde incluirse.
              En vez de eso, en el callback de pintado de celdas, devolvemos simplemente un div con un id calculado,
              y almacenamos ese div, junto con los datos asociados, en el array "futureWidgets".
              Cuando se ha terminado de pintar completamente el grid, se lanza el evento "bindingComplete", lo cual
              lleva a esta funcion, donde se recuperan los divs y los datos, y se crean los widgets.


           */
          onRendered: function () {
            /* A pesar del nombre del callback, esta funcion se llama en más ocasiones, no solo en el rendered.
            El rendered solo se lanza en el primer pintado del grid.No se llama cuando se pagina.
            Ademas, cuando se selecciona o deselecciona una row usando los checkboxes, hace una cosa rara: el grid
            se repinta, restaurando el contenido original de las celdas. Ese contenido, en el caso de las columnas
            tipo "widget", es tan sólo el div con el id autogenerado. Al restaurarse el div, el widget se pierde.
            Es por eso que nos hemos quedado con una referencia al widget, en el diccionario this.buildWidgets.
            Entonces, hacemos que se llame a este callback en los eventos de cambio de seleccion (cuando se pulsa sobre
            un checkbox de seleccion). Si en ese momento, siguen existiendo los divs que teniamos asociados a widgets,
            se vuelven a meter los widgets en esos divs.
            Pero, si se hubiera llamado a este callback porque se ha paginado, esos divs ya no existirian (han llegado nuevas rows),
            por lo que hay que destruir esos widgets antiguos.
             */
            /*
                Fase 1: restauracion de widgets (caso de seleccion/deseleccion), o borrado de widgets no existentes
                (caso de paginacion)
             */
            for (var k in this.builtWidgets) {
              var root = $(k);
              if (root.length > 0)
                $(k).append(this.builtWidgets[k].rootNode);
              else {
                this.builtWidgets[k].destruct();
                delete this.builtWidgets[k];
              }
            }
            /*
                Fase 2: Si se ha repintado el grid (caso de eventos rendered y pagination), habrá nuevos widgets a
                crear en futureWidgets
             */
            var m = this;
            for (var k = 0; k < this.futureWidgetData.length; k++) {
              // Los elementos del array son objetos con tres campos:
              // div: div destino
              // curIt: definicion de la columna
              // row: datos de la fila.
              var cur = this.futureWidgetData[k];
              var obj = Siviglia.Utils.stringToContextAndObject(cur.it.Widget);

              var wid = new obj.context[obj.object](cur.it.Widget, {row: cur.row}, {}, $("<div></div>"), new Siviglia.Path.ContextStack());

              wid.__build().then(function () {
                $(cur.div).append(wid.rootNode);

                // Aqui nos quedamos con una referencia a los widgets, usando como key el div. De esta forma
                // recuperamos el widget si se produce un cambio de seleccion, lo cual repinta las columnas.

                m.builtWidgets[cur.div] = wid;
              });

            }
            /*
                Fase 3: Ya hemos creado los widgets, borramos futureWidgetData para que no se vuelvan a crear en caso
                de que se vuelva a llamar a este callback.
             */
            this.futureWidgetData = [];
          },
          buildFilters: function () {
            if (this.formWidgetName === null)
              return;

            var formNamespace = Siviglia.Utils.stringToContextAndObject(this.formWidgetName);


            this.filterWidget = new formNamespace.context[formNamespace.object](this.formWidgetName,
              {bto: this.parameters}, {}, $("<div></div>"), this.__context);
            this.filterWidget.__build().then(function () {
              this.filterNode.append(this.filterWidget.rootNode);
              this.filterWidget.waitComplete().then(function () {
                this.filterPromise.resolve();
              }.bind(this));
            }.bind(this))

          },
          downloadAsXLSX: function () {
            this.ds.downloadAsXLSX();
          },
          getGrid: function () {
            return this.grid;
          }
        }
      },
      TreeNode: {
        construct: function (params) {
          if (params === undefined)
            params = {}
          if (Siviglia.isString(params)) {
            this.content = {field: params}
            this.isSimpleNode = true
          }
          if (params.content) {
            this.content = params.content
            this.prefix = Siviglia.issetOr(params.prefix, null)
            this.suffix = Siviglia.issetOr(params.suffix, null)
            this.actions = Siviglia.issetOr(params.actions, null)
          }
        },
        methods: {

          createConfig: function () {
            var config = this.isSimpleNode ? this.createContent() : {content: this.createContent()}

            this.prefix = this.createPrefix()
            this.suffix = this.createSuffix()
            this.actions = this.createActions()

            for (var section of ['prefix', 'suffix', 'actions']) {
              if (section)
                config[section] = this[section]
            }

            return config
          },
          createContent: function () {
            return this.content
          },
          createPrefix: function () {
            return this.prefix
          },
          createSuffix: function () {
            return this.suffix
          },
          createActions: function () {
            return this.actions
          },
        }
      },
      Tree: {
        inherits: 'Siviglia.UI.Expando.View,Siviglia.Dom.EventManager',
        methods: {
          preInitialize: function (params) {
            this.renderEngine = params.renderEngine
            this.nodeDefinitions = params.nodeDefinitions
            this.dataIndexField = params.dataIndexField
            this.data //= null
            this.itemsWithAction = []

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
              nodeDefinitions: this.nodeDefinitions,
              data: this.data,
              dataIndexField: this.dataIndexField,
              itemsWithAction: this.itemsWithAction,
            })
            let rendering = new Promise(function (resolve, reject) {
              this.implementation.render()
              resolve()
            }.bind(this))
            rendering.then(function () {
              this.addActionsToElements()
              this.publishTreeEvents()
            }.bind(this))
          },
          publishTreeEvents: function () {
            this.containerNode.on("added", function (event) {
              this.fireEvent('NODE_ADDED', {event: event})
            }.bind(this));
            this.containerNode.on("checkChange", function (event) {
              this.fireEvent('CHECK_CHANGED', {event: event})
            }.bind(this));
            this.containerNode.on("collapse", function (event) {
              this.fireEvent('BRANCH_COLLAPSED', {event: event})
            }.bind(this));
            this.containerNode.on("dragStart", function (event) {
              this.fireEvent('DRAG_START', {event: event})
            }.bind(this));
            this.containerNode.on("dragEnd", function (event) {
              this.fireEvent('DRAG_END', {event: event})
            }.bind(this));
            this.containerNode.on("expand", function (event) {
              this.fireEvent('BRANCH_EXPANDED', {event: event})
            }.bind(this));
            this.containerNode.on("initialized", function (event) {
              this.fireEvent('INITIALIZED', {event: event})
            }.bind(this));
            this.containerNode.on("itemClick", function (event) {
              this.fireEvent('ITEM_CLICKED', {event: event})
            }.bind(this));
            this.containerNode.on("removed", function (event) {
              this.fireEvent('NODE_REMOVED', {event: event})
            }.bind(this));
            this.containerNode.on("select", function (event) {
              this.fireEvent('NODE_SELECTED', {event: event})
            }.bind(this));
          },
          addActionsToElements: function () {
            this.itemsWithAction.forEach(function (item) {
              var actionConfig = this.nodeDefinitions[item.type].actions[item.action]
              this.addListener(actionConfig.event, this, 'executeEventCallback')
              $('#' + item.id).click(function () {
                this.fireEvent(actionConfig.event, {callback: actionConfig.callback, data: item.data})
              }.bind(this))
            }.bind(this))
          },
          executeEventCallback: function (event, data) {
            data.callback(data.data)
          },
        }
      },
    }
  });