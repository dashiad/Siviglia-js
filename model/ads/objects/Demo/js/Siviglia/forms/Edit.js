Siviglia.Utils.buildClass({
	"context" : "Siviglia.model.ads.Demo.forms",
	"classes" : {
		Edit : {
			"inherits" : "Siviglia.inputs.jqwidgets.Form",
			"methods" : {
				preInitialize : function(params) {
					var p = {
						"keys" : params,
						"model" : "/model/ads/Demo",
						"form" : "Edit",
					};
					return this.Form$preInitialize(p);
				},
			}
		},
	}
});