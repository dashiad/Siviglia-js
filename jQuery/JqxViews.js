Siviglia.Utils.buildClass({
  "context": "Siviglia.views.jqwidgets",

  classes: {
    "View": {
      inherits: "Siviglia.UI.Expando.View",
      methods: {
        preInitialize: function (params) {
          if (typeof params.data !== "undefined") {
            this.data = params.data;
          } else {
            var p = $.Deferred();

            this.ds = new Siviglia.Model.DataSource(params.model, params.datasource, params.params);
            this.ds.freeze();
            var gtime = new Date().getTime();
            this.ds.settings.__start = 0;
            this.self = this;
            this.ds.addListener("CHANGE", this, "refreshGrid");
            this.ds.unfreeze().then(function () {
              this.data = this.ds.getValue().data[0];
              p.resolve()
            }.bind(this)); // LLama automaticamente a refresh
            return p;
          }
        },
        doRefresh: function (evName, params) {
          this.data = this.ds.getValue().data[0];
        },
        getTypeView: function (node, params) {
          var factory = new Siviglia.types.jqwidgets.Factory();
          factory.getWidget(params.key, this, this.data, params.widget).then(function (instance) {
            node.append(instance.rootNode);
          })
        },
        getFieldParams: function (path) {
          return {};
        }
      }
    }
  }
});