<html>
<!--
    Igual que testDatasource.html, pero se incluye integracion con JqxGrid.
-->
<head>
<script src="/node_modules/jquery/dist/jquery.js"></script>
<script src="../../Siviglia.js"></script>
<script src="../../SivigliaTypes.js"></script>
<script src="../../SivigliaStore.js"></script>
<script src="../../Model.js"></script> 
<script src="../../../jqwidgets/jqx-all.js"></script>
<script src="../../../jqwidgets/globalization/globalize.js"></script>
<link rel="stylesheet" href="../../../jqwidgets/styles/jqx.base.css">
<link rel="stylesheet" href="../../jQuery/JqxWidgets.css">
<link rel="stylesheet" href="../../../../reflection/css/style.css">

<link rel="stylesheet"
	href="../../../jqwidgets/styles/jqx.adtopy-dev.css">
<!-- <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.light.css"> -->

</head>
<body>
<?php include_once(__DIR__."/../../jQuery/JqxWidgets.html"); ?>
<script>
    var Siviglia = Siviglia || {};
    Siviglia.config = {
        baseUrl: 'http://editor.adtopy.com/',
        staticsUrl: 'http://statics.adtopy.com/',
        metadataUrl:'http://metadata.adtopy.com/',
        locale: 'es-ES',
        // Si el mapper es XXX, debe haber:
        // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
        // 2) Un Mapper en Siviglia.Model.XXXMapper
        // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
        mapper:'Siviglia'

    };
    Siviglia.Model.initialize(Siviglia.config);
    var stopOnBreakPoint = true;
</script>
<script>
    var parser = new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>

<div style="display:none">
	<div data-sivWidget="SmartConfig.Selector" 
		 data-widgetCode="SmartConfig.Selector" 
		 data-sivParams='{"bto":"/*configSelector"}'>
			<div data-sivCall="getInputFor" 
				 data-sivParams='{"key":"domain"}'>
		    </div>
			<div data-sivView="Siviglia.model.ads.Demo.forms.Edit" 
				 data-widgetCode="Siviglia.model.ads.Demo.forms.Edit" 
				 data-sivParams='{"id":"/*configSelector/domain"}'>
	        </div>
    </div>
</div>


<div class="widget">
	<div class="widget-content">
		<div data-sivView="SmartConfig.Selector"></div>
	</div>
</div>
	<script>
    Siviglia.Utils.buildClass({
        "context":"SmartConfig",
        "classes":{
            Selector:{
				"inherits": "Siviglia.inputs.jqwidgets.Form",
                "methods":{
                    preInitialize:function(params)
                    {
                    	if (stopOnBreakPoint) debugger;
                    	this.factory = Siviglia.types.TypeFactory;
                        this.self = this;
                        this.typeCol = [];
                        this.configSelector=new Siviglia.model.BaseTypedObject(
                            {
                                "FIELDS":{
                                    "domain": {
                                        "LABEL":"Select domain",
                                        "TYPE":"String",
                                        "ROLE": "List",
                                        "SOURCE":{
                                            "TYPE":"DataSource",
                                            "MODEL":"/model/ads/Demo",
                                            "DATASOURCE":"FullList",
                                            "LABEL":"domain",
                                            "VALUE":"id"
                                        }
                                    },
                                },
                                "INPUTPARAMS": {
                                	"/":{
                                		"INPUT": "FlexContainer"
									},
                                },
                            }
                        );
                        return this.Form$preInitialize({bto:this.configSelector});
                    },
                    initialize:function(params){}
                }
            },
        }
    });

</script>
	<script>
    var parser=new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>
</body>
</html>
