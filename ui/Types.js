Siviglia.Utils.buildClass(
  {
    context: 'Siviglia.types.jqwidgets',
    classes: {
      Factory: {
        methods: {
          getWidget: function (name, parent, container, widget, params) {
            var type = container["*" + name];
            var d = $.Deferred();
            if (!Siviglia.isset(params))
              params = {};
            //if(Siviglia.isset(params.controller))
            //    type.setController(params.controller);
            var dv = $('<div></div>');
            var p = {};
            if (typeof parent !== "undefined") {
              var viewParams = parent.getFieldParams(type.__getFieldPath());
              if (viewParams) {
                for (var k in viewParams)
                  p[k] = viewParams[k];
              }
            }
            p.type = type;
            p.parent = parent;
            p.key = name;
            p.data = container;
            var stack = new Siviglia.Path.ContextStack();
            var f = function () {
              var obj = Siviglia.Utils.stringToContextAndObject(widget);
              var view = new obj.context[obj.object](widget, p,
                {},
                dv,
                parent.__context);
              view.__build().then(function () {
                d.resolve(view)
              });
            };
            var widgetFactory = new Siviglia.UI.Expando.WidgetFactory();
            if (!widgetFactory.hasInstance(widget))
              SMCPromise.when(widgetFactory.getInstance(widget)).then(f);
            else
              f();
            return d;

          }
        }
      },


      BaseTypeView: {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
        },
        methods: {
          preInitialize: function (params) {
            this.params = params;
            this.data = params.data;
            this.name = params.key;
            this.curValue = this.data[this.name];
            this.self = this;
          },
          initialize: function (params) {
          }
        }
      },
      String: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Enum: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Integer: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Decimal: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Text: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      AutoIncrement: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Boolean: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Relationship: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Date: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      DateTime: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Money: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Password: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      State: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Name: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Street: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      City: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Phone: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      UUID: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      },
      Dictionary:
        {
          inherits: "Siviglia.types.jqwidgets.BaseTypeView"
        },
      Container:
        {
          inherits: "Siviglia.types.jqwidgets.BaseTypeView"
        },
      Array:
        {
          inherits: "Siviglia.types.jqwidgets.BaseTypeView"
        },
      TypeSwitcher: {
        inherits: "Siviglia.types.jqwidgets.BaseTypeView"
      }
    }
  }
)