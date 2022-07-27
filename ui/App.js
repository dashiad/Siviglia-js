Siviglia.Utils.buildClass({
  "context": "Siviglia.site.widgets.JS",

  classes: {
    "App": {
      "inherits": "Siviglia.UI.Expando.View",
      destruct: function () {
        if (this.currentWidget)
          this.currentWidget.destruct();
      },
      methods: {
        preInitialize: function (params) {
          this.menu = Siviglia.issetOr(params.menu, null);
          this.getSelf = function () {
            return this
          }.bind(this);
          this.window = params.window;
          this.currentWidget = null;
          this.icon = typeof params.icon
        },
        initialize: function (params) {
        },
        onSelected: function (item) {

          if (item.widget) {
            this.loadWidgetByName(item.widget, {})
          }
        },
        loadWidgetByName: function (name, params) {
          var p = $.Deferred();
          if (this.currentWidget)
            this.currentWidget.destruct();
          var factory = new Siviglia.UI.Expando.WidgetFactory();
          var m = this;
          factory.get(name, this.context).then(function (i) {
            var target = Siviglia.Utils.stringToContextAndObject(name);
            var f = target.context[target.object];
            m.currentWidget = new f(
              name,
              params,
              {},
              $("<div></div>"),
              m.__context
            );
            m.currentWidget.__build().then(function (instance) {
              m.widgetContainer.append(m.currentWidget.rootNode);
              p.resolve();
            })
            m.currentWidget.addListener("REQUEST_CLOSE", this, closeWindow);
          });
          return p;
        },
        closeWindow: function () {

        }
      }

    },
    "SubApp": {
      "inherits": "Siviglia.UI.Expando.View",
      destruct: function () {
        if (this.windowNode !== null)
          this.windowNode.remove();
      },
      methods: {
        preInitialize: function (params) {
          this.windowNode = null;
          this.titleNode = null;
          this.contentNode = null;
        },
        initialize: function (params) {
          this.waitComplete().then(function () {
            for (var k = 0; k < this.__subViews.length; k++) {
              this.__subViews[k].view.addListener("REQUEST_CLOSE", this, "onClose");
            }
          }.bind(this));
        },
        initializeWindow: function (params) {
          this.windowNode = params.windowNode;
          this.titleNode = params.titleNode;
          this.contentNode = params.contentNode;
          this.windowNode.on("close", function () {
            this.destruct()
          }.bind(this));


        },
        getWindowPreferences: function () {
          return {windowWidth: 640, windowHeight: 480, windowTitle: "Anonymous App"};
        },
        onClose: function () {

          if (this.windowNode)
            this.windowNode.jqxWindow("close");
        },
        getTitleNode: function () {
          return this.titleNode;
        },
        getContentNode: function () {
          return this.contentNode;
        },
        getWindowNode: function () {
          return this.windowNode;
        }
      }
    },
    "Menu": {
      "inherits": "Siviglia.UI.Expando.View",
      destruct: function () {
        this.jqxMenu.jqxMenu('destroy');
      },
      methods: {
        preInitialize: function (params) {
          this.menu = Siviglia.issetOr(params.menu, null)
          this.getParent = params.getParent;
          this.nestLevel = 0;
          this.initializeMenuStructure(this.menu);
        },
        initialize: function (params) {
          this.jqxMenu.jqxMenu({width: "600px"});
        },
        initializeMenuStructure: function (menu) {
          for (var k = 0; k < menu.length; k++) {
            if (!Siviglia.isset(menu[k].menu))
              menu[k].menu = null;
            else
              this.initializeMenuStructure(menu[k].menu);
          }
        }
      }
    },
    SubMenu: {
      "inherits": "Siviglia.UI.Expando.View",
      methods: {
        preInitialize: function (params) {
          this.getParent = params.getParent;
          this.nestLevel = params.nestLevel + 1;
          this.menu = Siviglia.issetOr(params.menu, null);
        },
        initialize: function (params) {
          this.mainUL.css({width: (Math.max(150, 200 - (this.nestLevel * 10))) + "px"})
        },
        onClicked: function (node, params) {

          if (params.item.menu !== null)
            return;
          this.getParent().onSelected(params.item);
        }

      }

    }

  }
});