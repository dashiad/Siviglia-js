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
					this.id = params.id;
					return this.Form$preInitialize(p);
				},
				initialize : function(params) {
					this.tooltipNode = this.rootNode[1];
					return this.Form$initialize(params);
				},
				submit: function() {
					return this.Form$submit();
				}
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