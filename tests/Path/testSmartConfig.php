<html>
<head>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../../Siviglia.js"></script>
    <script src="../../SivigliaTypes.js"></script>
    <script src="../../SivigliaStore.js"></script>
    <script src="../../Model.js"></script>
    <script src="../../../jqwidgets/jqx-all.js"></script>
    <script src="../../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="../../jQuery/JqxWidgets.css">
    <link rel="stylesheet" href="../../../../reflection/css/style.css">
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.adtopy-dev.css">
    
    <script>
        var Siviglia = Siviglia || {};
        Siviglia.config = {
            baseUrl: 'http://editor.adtopy.com/',
            staticsUrl: 'http://statics.adtopy.com/',
            metadataUrl:'http://metadata.adtopy.com/',
            jsFramework: 'jquery',
            locale: 'es-ES',
            mapper: 'Siviglia',
            datasourcePrefix: 'datasource/'
            //jsFramework:'dojo'
        };
        Siviglia.Model.initialize(Siviglia.config);
    </script>

</head>
<body>
<?php include_once(__DIR__."/../../jQuery/JqxWidgets.html"); ?>

<div style="display:none">
	<div data-sivWidget="SmartConfig.Selector" data-widgetParams="" data-widgetCode="SmartConfig.Selector">
		<div class="type">
            <div class="label">Dominio</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"domain"}'></div>
        </div>
        <div class="type">
            <div class="label">Regex</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"regex"}'></div>
        </div>
    </div>
</div>

<div style="display:none">
	<div data-sivWidget="SmartConfig.Editor" data-widgetParams="" data-widgetCode="SmartConfig.Editor">
        <div class="type">
            <div class="label">Config</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"config"}'></div>
        </div>        
    </div>
</div>

<div class="widget">
    <div class="widget-content">
    	<div data-sivView="SmartConfig.Selector" data-sivParams='{"bto":"/*ConfigSelector"}'></div>
<!-- 		<div data-sivView="SmartConfig.Selector"></div> -->
    	<div data-sivView="SmartConfig.Editor" data-sivParams='{"domain":"/*ConfigSelector/domain","regex":"/*ConfigSelector/regex"}'></div>
    </div>
</div>

<script>
    Siviglia.Utils.buildClass({
        "context":"SmartConfig",
        "classes":{
        	Selector:
            {
        		inherits: "Siviglia.inputs.jqwidgets.Form",
//         		inherits: "Siviglia.UI.Expando.View",
        		methods: {
                    preInitialize: function (params) {
                        this.factory = Siviglia.types.TypeFactory;
                        this.self = this;
                        this.typeCol = [];
                        this.typedObj = new Siviglia.model.BaseTypedObject({
                        	"FIELDS": {
                            	domain: {
                					"LABEL": "Domain",
                					"TYPE": "String",
                                    "SOURCE": {
                                        "TYPE": "DataSource",
                                        "MODEL": "/model/ads/SmartConfig",
                                        "DATASOURCE": "DomainList",
                                        "LABEL": "domain",
                                        "VALUE": "domain"
                                    }
            					},
            					regex: {
                					"LABEL": "Regex",
                					"TYPE": "String",
                					"SOURCE": {
                                        "TYPE": "DataSource",
                                        "MODEL": "/model/ads/SmartConfig",
                                        "DATASOURCE": "RegexList",
									    "PARAMS": {
                                            "domain": "[%#../domain%]"
                                        },
                                        
                                        "LABEL": "regex",
                                        "VALUE": "regex"
                                    },
            					},
                        	},
//                         	"GROUPS": {
//                             	"Selector": {"LABEL": "Selector", "FIELDS": ["domainSelector","regexSelector",]},
//                                 "Editor": {"LABEL": "Editor", "FIELDS": ["configEditor"]},
//                             },
//                             "INPUTPARAMS":{
//                                 "/": {
//                                     "INPUT": "TabsContainer",
//                                 }
//                             }
                        });
                        return this.Form$preInitialize({bto:this.typedObj});
                    },
                    initialize: function (params) {
                        //
                    },
                    setupBto:function()
                    {
                        this.Form$setupBto();
                    },
                    show: function () {
                        console.dir(this.typedObj.getValue());
                    },
                    getInputFor:function(node,params)
                    {
                        return this.__containerWidget.getInputFor(node, params);
                    }
        		}
            },
            Editor: 
            {
            	inherits: "Siviglia.inputs.jqwidgets.Form",
        		methods: {
                    preInitialize: function (params) {
//                          console.log(params.domain);
//                     	 this.factory = Siviglia.types.TypeFactory;
//                          this.self = this;
//                          this.typeCol = [];
//                          this.typedObj = new Siviglia.model.BaseTypedObject({
//                              "FIELDS": {
//                                  config: {
//                                      "LABEL": "Config",
// //                                       "TYPE": "model/ads/SmartConfig/types/SmartConfig",
// 									 "TYPE": "String",
//                                      "SOURCE": {
//                                          "TYPE": "DataSource",
//                                          "LABEL": "Config",
//                                          "MODEL": "/model/ads/SmartConfig",
//                                          "DATASOURCE": "SingleConfig",
//                                          "PARAMS": {
// // 											 "domain": "elcorreoweb",
// 											 "domain": params.domain,
// 											 "regex": ".*",
// 											 "plugin": "Exelate",
//                                          },
//                                      },
//                                  }
//                              }
//                          });

						this.ds=null;
                        if(!params)
                        {
                            this.destruct();
                            return;
                        }
                            console.log(params);
                            this.jqgrid = null;

//                             var a = new Siviglia.Path.PathResolver(this.__context.contextRoots, params.domain);
//                             console.log(a.getValue());

//                             this.addListener("CHANGE", this, "update");
                            
                            this.ds = new Siviglia.Model.DataSource("/model/ads/SmartConfig", "SingleConfig", params);
                            this.ds.freeze();
                            this.ds.addListener("CHANGE", this, "update");

                            this.parameters = this.ds["*params"].toBaseTypedObject();
//                             this.parameters.__definition.INPUTPARAMS={
//                                 "/":{
//                                     "INPUT": "FlexContainer"
//                                 }
//                             }



//                          return this.Form$preInitialize({bto:this.typedObj});
                    },
                    update: function () {
                        console.log("UPDATE");
                        this.typedObj = null;
//                         this.configEditor.domain="HOLA";
//                         this.configEditor.regex="ADIOS";
                    },
                    initialize: function (params) {
                        console.log("INIT");
                    	console.log(params);
                    	if(this.ds)
                        	this.ds.unfreeze();
                    },
                    show: function () {
                        console.dir(this.typedObj.getValue());
                    },
                    getInputFor: function (node, params) {
                        console.log("GETINPUT: "+params);
                        return this.__containerWidget.getInputFor(node, params);
                    },
                    destruct: function () {
                        if (this.ds)
                        	this.ds.destruct();
                    },
            	},
        	}
        }
    });
</script>
<script>
    var parser=new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>
</body>
</html>
