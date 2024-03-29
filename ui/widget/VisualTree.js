Siviglia.Utils.buildClass({
  context: "Siviglia.visual",
  classes: {
    Tree: {
      inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
      methods: {
        preInitialize: function (params) {
          // Los parametros minimos deben de ser:
          // Ancho y alto del svg : svgWidth,svgHeight.
          // * Nombre del widget a pintar en cada nodo : nodeWidget
          // * Ancho y alto del widget  : widgetWidth, widgetHeight
          // * Espaciado en x y en y de los nodos: spacingWidth y spacingHeight
          // * Datos. : data
          // * Id unico en los datos, si existe (si no, se autoasigna):rowIdField
          // * Nº de niveles inicialmente expandidos. :initialExpandedLevels
          // * Propiedad que contiene los hijos de en cada nivel : childProperty, por defecto, "children"
          // * (Extension en el futuro: d3 permite datos tabulares con una propiedad "parent", usando d3.stratify
          // * Permitir seleccion multiple: allowMultipleSelection

          // * Spacing en horizontal y en vertical entre los nodos. :spacingWidth, spacingHeight

          // Las clases derivadas pueden sobreescribir funciones para click sobre elementos,
          this.svgWidth = params.svgWidth;
          this.svgHeight = params.svgHeight;
          this.nodeWidget = params.nodeWidget;
          this.nodeWidth = params.nodeWidth;
          this.nodeHeight = params.nodeHeight;
          this.data = params.data;
          this.spacingWidth = params.spacingWidth;
          //this.spacingWidth = 500;
          this.spacingHeight = params.spacingHeight
          //this.spacingHeight = 800;
          this.allowMultipleSelection = Siviglia.issetOr(params.allowMultipleSelection, false);
          this.rowIdField = Siviglia.issetOr(params.rowIdField, null);
          this.initialExpandedLevels = Siviglia.issetOr(params.initialExpandedLevels, 1);
          this.childProperty = Siviglia.issetOr(params.childProperty, "children");
          this.index = 0; // Un contador para el id por defecto,__id
          this.selection = [];
          this.EventManager();

        },
        initialize: function (params) {
          var m = this;
          // Se crean objetos iniciales, como el zoom, y el layout tree
          this.tree = d3.tree().nodeSize([this.nodeWidth + this.spacingWidth, this.nodeHeight + this.spacingHeight]);

          // Se prepara el zoom:

          var zoomBehaviours = d3
          .zoom()
          .scaleExtent([0.05, 3])

          //.translateExtent([[0, 0], [this.svgWidth , this.svgHeight]])
          .on("zoom", function (event, d) {
            m.svg.attr("transform",
              event.transform.toString());
          });

          // Se inicializa la posicino del nodo raiz.

          this.root = d3.hierarchy(this.data, function (d) {
            return d.children;
          });
          this.root.data.x0 = this.svgWidth / 2;
          this.root.data.y0 = 20;
          var collapse = function (d) {
            if (typeof (d.children) !== "undefined") {
              d._children = d.children;
              d.children = null;
            }
          }
          this.root.children.forEach(function (d) {
            if (typeof (d.children) !== "undefined") {
              d.descendants().forEach(collapse);
            }
          })

          // Se crea el svg base, del mismo tamanio que el div principal del widget.
          var ow = this.svgWidth;
          var oh = this.svgHeight;
          this.svg = d3.select(this.svgNode[0])
          //.attr("width", ow)
          .attr("width", "100%")  // para que el ancho del arbol svg se adapte 100% al ancho de la ventana
          .attr("height", oh)
          .call(zoomBehaviours)
          .append("g")
          .attr("transform", "translate(" + ow / 2 + "," + oh / 5 + ")");


          // Cargamos aqui el widget necesario para pintar los nodos. Si lo tenemos ya,
          // llamamos directamente a expandTree. Si no, se llama a expandTree cuando el widget ya
          // se haya cargado.
          // ExpandTree va a llamar automaticamente a update

          var f = function () {
            this.expandTree(this.data, this.initialExpandedLevels);
          }.bind(this);
          var factory = new Siviglia.UI.Expando.WidgetFactory();
          if (factory.hasInstance(this.nodeWidget))
            f();
          else
            factory.getInstance(this.nodeWidget, this.__context).then(f);
        },
        update: function (source) {
          var treeData = this.tree(this.root);
          var nodes = treeData.descendants().reverse();
          var links = treeData.links();
          var m = this;
          /*
          var levelWidth = [1];
          var childCount = function (level, n) {

              if (n.children && n.children.length > 0) {
                  if (levelWidth.length <= level + 1) levelWidth.push(0);

                  levelWidth[level + 1] += n.children.length;
                  n.children.forEach(function (d) {
                      childCount(level + 1, d);
                  });
              }
          };

          childCount(0, root);
          var newHeight = d3.max(levelWidth) * 25; // 25 pixels per line*/


          // Se establece la altura en funcion de la profundidad en el arbol.
          nodes.forEach(function (d) {
            d.y = d.depth * (m.nodeHeight + m.spacingHeight);
          });

          // Se asignan los ids para cada nodo, dependiendo de si se ha especificado
          // un campo id, o se usa (genera) el id automatico.
          var node = this.svg.selectAll("g.node").data(nodes,
            this.rowIdField ?
              function (d) {
                return d[m.rowIdField]
              } :
              function (d) {
                return d.__id || (d.__id = ++m.index);
              }
          );
          // Los hijos que hayan entrado en este update, se colocan en la misma posicion que el nodo inicial.
          var nodeEnter = node.enter()
          .append("g")
          .attr("class", "node")
          .attr("transform", function (d) {
            return "translate(" + source.x0 + "," + source.y0 + ")";
          });


          var wclass = Siviglia.Utils.stringToContextAndObject(this.nodeWidget);

          nodeEnter.append(function (d) {
            var gEl = document.createElementNS('http://www.w3.org/2000/svg', "g");
            var gEl2 = document.createElementNS('http://www.w3.org/2000/svg', "g");
            var instance = new wclass.context[wclass.object](m.nodeWidget, {
              tree: m,
              data: d.data,
              treeData: d,
              svg: m.svg
            }, {}, $(gEl2), m.__context);
            instance.__build().then(function () {
              gEl.append(instance.rootNode[0]);
            });
            return gEl;
          });
          var nodeUpdate = nodeEnter.merge(node);
          // Los nodos que entran, se mueven a su posicion
          nodeUpdate.transition()
          .duration(250)
          .attr("transform", function (d) {
            return "translate(" + d.x + "," + d.y + ")";
          })
          // Y los que salen, se mueven a la posicion del padre
          var nodeExit = node.exit().transition()
          .duration(250)
          .attr("transform", function (d) {
            return "translate(" + source.x + "," + source.y + ")";
          })
          .remove();

          // Se updatean los links:
          var link = this.svg.selectAll(".link")
          .data(links, this.rowIdField ?
            function (d) {
              return d.target[m.rowIdField]
            } :
            function (d) {
              return d.target.__id;
            });

          var pathFunc = function (d) {
            var nodeBottom = d.source.y + m.nodeHeight;
            return "M" + (d.source.x + m.nodeWidth / 2) + "," +
              (nodeBottom) +
              "V" + (nodeBottom + (d.target.y - nodeBottom) / 2) +
              "H" + (d.target.x + m.nodeWidth / 2) +
              "V" + (d.target.y)
          };

          // Enter any new links at the parent's previous position.
          var linkEnter = link.enter().insert("path", "g")
          .attr("class", "link")
          .attr("x", m.nodeWidth / 2)
          .attr("y", m.nodeHeight / 2)
          .attr("d", function (d) {
            var o = {x: source.x0, y: source.y0}
            return "M" + (source.x0 + m.nodeWidth / 2) + "," +
              (d.source.y + m.nodeHeight) +
              "V" + (d.source.y + m.nodeHeight) +
              "H" + source.x0 +
              "V" + (d.source.y + m.nodeHeight)

          });
          var linkUpdate = linkEnter.merge(link);


          linkUpdate.transition()
          .duration(250)
          .attr("d", pathFunc)


          // Transition exiting nodes to the parent's new position.
          link.exit().transition()
          .duration(250)
          .attr("d", function (d) {
            var o = {x: source.x0, y: source.y0}
            return "M" + (source.x0 + m.nodeWidth / 2) + "," +
              (d.source.y + m.nodeHeight) +
              "V" + (d.source.y + m.nodeHeight) +
              "H" + source.x0//+
            //"V" + (d.source.y+m.nodeHeight)
          })
          .remove();

          // Stash the old positions for transition.
          nodes.forEach(function (d) {
            d.x0 = d.x;
            d.y0 = d.y;
          });
        },
        // Expandir / colapsar se hace "ocultando" o "mostrando" la propiedad
        // que contiene los hijos.
        expandTree: function (node, nLevels, collapseLast) {

          this.__recurseExpand(node,
            Siviglia.issetOr(nLevels, 1),
            Siviglia.issetOr(collapseLast, false));
          this.update(node);
        },
        __recurseExpand: function (node, levels, collapseLast) {
          levels--;
          var p = this.childProperty;
          if (node["_" + p]) {
            node[p] = node["_" + p];
            node["_" + p] = null;
          }
          if (!Siviglia.empty(node[p])) {

            for (var k = 0; k < node[p].length; k++) {
              if (levels > 0)
                this.__recurseExpand(node[p][k], levels, collapseLast);
              if (levels == 0 && collapseLast == true)
                this.collapseTree(node[p][k], false);
            }

          }
        },
        collapseTree: function (node, doUpdate) {
          doUpdate = Siviglia.issetOr(doUpdate, true);
          var p = this.childProperty;
          if (typeof node[p] == "undefined")
            return;
          node["_" + p] = node[p];
          node[p] = null;
          if (!Siviglia.empty(node.__widget)) {
            node.__widget.destruct();
            node.__widget = null;
          }
          if (doUpdate)
            this.update(node);
        },
        getIdField: function () {
          return this.rowIdField == null ? "__id" : this.rowIdField;
        },
        select: function (node, datum) {

          var idField = this.getIdField();
          var id = datum[idField];
          for (var k = 0; k < this.selection.length; k++) {
            if (this.selection[k].d[idField] == id)
              return;
          }
          if (!this.allowMultipleSelection && this.selection.length > 0) {
            this.selection[0].node.toggleSelection();
            this.selection = [];
          }
          this.selection.push({d: datum, node: node});
          this.fireEvent("SELECTION_CHANGE", {selection: this.selection});
        },
        unselect: function (datum) {
          var idField = this.getIdField();
          var id = datum[idField];
          for (var k = 0; k < this.selection.length; k++) {
            if (this.selection[k].d[idField] == id) {
              this.selection[k].node.deselect();
              this.selection.splice(k, 1);

              if (this.selection.length > 0)
                this.fireEvent("SELECTION_CHANGE", {selection: this.selection});
              else
                this.fireEvent("SELECTION_EMPTY", {});
              return;
            }
          }
        }
      }
    },
    Force: {
      inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
      destruct: function () {
        if (this.subWidgets !== null) {
          for (var k in this.subWidgets)
            this.subWidgets[k].destruct();
          this.rootNode.innerHTML = "";
        }
      },
      methods: {
        preInitialize: function (params) {
          // Esta clase no va a recibir datos.
          // Lo que espera es que haya un metodo que devuelva nodos y links.
          // El metodo getNodesAndLinks debe devolver un objeto {nodes:[], links:[]}
          // Luego, es necesario proveer de un metodo drawNode y drawLink
          // Ancho y alto del svg : svgWidth,svgHeight.
          // * Nombre del widget a pintar en cada nodo : nodeWidget
          // * Ancho y alto del widget  : widgetWidth, widgetHeight
          // * Id unico en los datos, si existe (si no, se autoasigna):rowIdField
          // * Permitir seleccion multiple: allowMultipleSelection

          // Las clases derivadas pueden sobreescribir funciones para click sobre elementos,
          this.svgWidth = params.svgWidth;
          this.subWidgets = {};
          this.svgHeight = params.svgHeight;
          this.nodeWidget = params.nodeWidget;
          var parts = Siviglia.Utils.stringToContextAndObject(this.nodeWidget)
          this.nodeWidgetContext = parts.context;
          this.nodeWidgetObject = parts.object;
          this.nodeWidth = params.nodeWidth;
          this.nodeHeight = params.nodeHeight;
          this.distanceLink = params.distanceLinks;

          this.allowMultipleSelection = Siviglia.issetOr(params.allowMultipleSelection, false);
          this.rowIdField = Siviglia.issetOr(params.rowIdField, null);
          this.index = 0; // Un contador para el id por defecto,__id
          this.selection = [];
          this.EventManager();
        },
        initialize: function (params) {
          var m = this;
          this.nodes = [];
          this.links = [];
          this.nodesById = {};
          // Se crea el svg base, del mismo tamanio que el div principal del widget.


          var ow = this.svgWidth;
          var oh = this.svgHeight;
          //zoomBehaviours.translate([ow / 2, 20]);
          this.svg = d3.select(this.svgNode[0])
          //.attr("width", ow)
          .attr("width", ow)  // para que el ancho del arbol svg se adapte 100% al ancho de la ventana
          .attr("height", oh);
          this.mainG = this.svg
          .append("g");

          var zoom_handler = d3
          .zoom()
          //.scaleExtent([0.05, 3])
          .on("zoom", function (event, d) {
            this.mainG.attr("transform", event.transform)
          }.bind(this));
          zoom_handler(this.svg);
          // Se da el punto de referencia a partir del cual se tiene que hacer zoom
          // Se prepara el zoom:


          var m = this;
          this.simulation = d3.forceSimulation()
          .force("link", d3.forceLink().id(function (d) {
            return d[this.rowIdField];
          }.bind(this)))
          //.force("link", d3.forceLink().distance(function(d) { return d[this.distanceLink]; }.bind(this)))
          //.force("link", d3.forceLink().distance(10))
          .force("charge", d3.forceManyBody().strength(-7000))
          .force("center", d3.forceCenter(ow / 2, oh / 2))
          .force("collision", d3.forceCollide().strength(500))
          .on("tick", function () {
            m.tick()
          }.bind(m));

          this.linkG = this.mainG.append("g");
          this.linkG.attr("class", "links");


          this.nodeG = this.mainG.append("g");
          this.nodeG.attr("class", "nodes");


          // Cargamos aqui el widget necesario para pintar los nodos. Si lo tenemos ya,
          // llamamos directamente a expandTree. Si no, se llama a expandTree cuando el widget ya
          // se haya cargado.
          // ExpandTree va a llamar automaticamente a update

          var f = function () {
            this.update();
          }.bind(this);
          var factory = new Siviglia.UI.Expando.WidgetFactory();
          if (factory.hasInstance(this.nodeWidget))
            f();
          else
            factory.getInstance(this.nodeWidget, this.__context).then(f);
        },
        update: function () {
          // Redefine and restart simulation
          var graph = this.getNodesAndLinks();

          this.updateLinks(graph.links ?? []);
          this.updateNodes(graph.nodes ?? [])

          this.simulation.nodes(this.nodes);
          this.simulation.force("link")
          .links(this.links);
          this.simulation.alpha(1);
          this.simulation.restart();

        },
        tick: function () {

          this.graphLinks
          .attr("x1", function (d) {
            return d.source.x;
          })
          .attr("y1", function (d) {
            return d.source.y;
          })
          .attr("x2", function (d) {
            return d.target.x;
          })
          .attr("y2", function (d) {
            return d.target.y;
          });

          this.graphNodes.attr("transform", function (d) {
            return "translate(" + d.x + "," + d.y + ")";
          });

        },
        updateLinks: function (links) {
          this.links = links;
          this.graphLinks = this.linkG.selectAll(".link").data(this.links);
          var linkEnter = this.graphLinks
          .enter()
          .append("line")
          .attr("class", "link")
          //.distance(30)
          //.attr("y", 500)
          //.attr("x", 200)

          this.graphLinks = linkEnter.merge(this.graphLinks)
        },
        updateNodes: function (nodes) {
          this.nodes = nodes;
          this.graphNodes = this.nodeG.selectAll(".node").data(this.nodes);
          var m = this;
          var nodeEnter = this.graphNodes
          .enter()
          .append("g")
          .attr("class", "node")
          .attr('id', function (d) {
            return "g-force-" + d[this.rowIdField]
          }.bind(this))
          .append("svg:foreignObject")
          .attr("y", -50)
          .attr("x", -80)
          .attr('width', 200)
          .attr('height', 200)
          .attr("text-anchor", function (d) {
            return d.children || d._children ? "end" : "start";
          })
          .append('xhtml:div')
          .attr('id', function (d) {
            return "force-" + d[this.rowIdField]
          }.bind(this))
          .each(function (d) {
            this.createSubWidget(d);
          }.bind(this))
          .call(
            d3.drag()
            .on("start", function (ev, d) {
              m.dragstarted(ev, d)
            }.bind(m))
            .on("drag", m.dragged)
            .on("end", m.dragended)
          )
          
          this.graphNodes = nodeEnter.merge(this.graphNodes);
        },
        createSubWidget: function (d) {
          var id = d[this.rowIdField];
          var nodeId = "#force-" + id;
          var ss = $("<div></div>");
          var newInstance = new this.nodeWidgetContext[this.nodeWidgetObject](
            this.nodeWidget, {"item": d}, {}, ss, this.__context
          );
          newInstance.__build().then(function (i) {
            n = ss;
            $(nodeId).append(i.rootNode);
            this.subWidgets[id] = i;
          }.bind(this));

        },
        getIdField: function () {
          return this.rowIdField == null ? "__id" : this.rowIdField;
        },
        select: function (node, datum) {

          var idField = this.getIdField();
          var id = datum[idField];
          for (var k = 0; k < this.selection.length; k++) {
            if (this.selection[k].d[idField] == id)
              return;
          }
          if (!this.allowMultipleSelection && this.selection.length > 0) {
            this.selection[0].node.toggleSelection();
            this.selection = [];
          }
          this.selection.push({d: datum, node: node});
          this.fireEvent("SELECTION_CHANGE", {selection: this.selection});
        },
        unselect: function (datum) {
          var idField = this.getIdField();
          var id = datum[idField];
          for (var k = 0; k < this.selection.length; k++) {
            if (this.selection[k].d[idField] == id) {
              this.selection[k].node.deselect();
              this.selection.splice(k, 1);

              if (this.selection.length > 0)
                this.fireEvent("SELECTION_CHANGE", {selection: this.selection});
              else
                this.fireEvent("SELECTION_EMPTY", {});
              return;
            }
          }
        },
        dragstarted: function (ev, d) {
          ev.sourceEvent.stopPropagation();
          if (!ev.active) this.simulation.alphaTarget(0.3).restart();
          //if (!d3.event.active) graphLayout.alphaTarget(0.3).restart();
          d.fx = d.x;
          d.fy = d.y;
        },
        dragged: function (ev, d) {
          d.fx = ev.x;
          d.fy = ev.y;
          console.log(ev.x + "," + ev.y);
        },
        dragended: function (ev, d) {
          //if (!ev.active) graphLayout.alphaTarget(0);
          d.fx = null;
          d.fy = null;
        }
      }
    }
  }
});