Siviglia.Utils.buildClass({
	"context" : "Siviglia.model.ads.SmartConfig.forms",
	"classes" : {
		Edit : {
			"inherits" : "Siviglia.inputs.jqwidgets.Form",
			"methods" : {
				preInitialize : function(params) {
					var p = {
						"keys" : params,
						"model" : "/model/ads/SmartConfig",
						"form" : "Edit",
					};
					return this.Form$preInitialize(p);
				},
			}
		},
	}
});

Siviglia.Utils.buildClass({
	context : 'Siviglia.inputs.jqwidgets',
	classes : {
		ActionList : {
			inherits : "Array",
		}
	}
});