Siviglia.Utils.buildClass({
	"context" : "Siviglia.model.ads.SmartConfig.forms",
	"classes" : {
		Edit : {
			"inherits" : "Siviglia.inputs.jqwidgets.Form",
			"methods" : {
				preInitialize : function(params) {
//					this.??.destruct();
					var p = {
						"keys" : params,
						"model" : "/model/ads/SmartConfig",
						"form" : "Edit",
					};
					return this.Form$preInitialize(p);
				},
				initialize : function(params) {
					debugger;
					//
//					if (params.regex) {
////						this.rootNode
//						// selecciono regex del accordion
//						if (params.plugin) {
//							// selecciono tab del plugin
//						}
//					}
					
				},
				clearError : function(path, input) {
					//
				},
				getInputFor: function(node, params) {
					console.log(params);
					return this.Form$getInputFor(node, params);
				},
			}
		},
	}
});