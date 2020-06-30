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
				initialize : function(params) {
					if (params.regex) {
						 // selecciono regex del accordion
						if (params.plugin) {
							// selecciono tab del plugin
						}
					}
				    return this.Form$initialize(params);
				},
				clearErrors : function() {
					//
				},
				clearError : function(path, input) {
					return this.Form$clearError(path, input);
				},
				getInputFor: function(node, params) {
					debugger;
					return this.Form$getInputFor(node, params);
				},
			}
		},
	}
});