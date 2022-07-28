<html>
<!--
    Igual que testDatasource.html, pero se incluye integracion con JqxGrid.
-->
<head>
<script src="/node_modules/jquery/dist/jquery.js"></script>
<script src="../../../Siviglia.js"></script>
<script src="../../../SivigliaTypes.js"></script>
<script src="../../../SivigliaStore.js"></script>
<script src="../../../Model.js"></script>
<script src="../../../../jqwidgets/jqx-all.js"></script>
<script src="../../../../jqwidgets/globalization/globalize.js"></script>
<link rel="stylesheet" href="../../../jQuery/css/jqx.base.css">
<link rel="stylesheet" href="../../../jQuery/css/jqx.adtopy-dev.css">
<link rel="stylesheet" href="../../../jQuery/css/JqxWidgets.css">
<link rel="stylesheet" href="../../../../../reflection/css/style.css">

<link rel="stylesheet"
	href="../../../../jqwidgets/styles/jqx.adtopy-dev.css">
<!-- <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.light.css"> -->

<style>
.jqx-listbox-filter-input {
    width:291px !important;
    height:21px !important;
}
</style>


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
    var stopOnBreakPoint = false;
</script>
<script>
    var parser = new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>

<div style="display:none">
	<div data-sivWidget="SmartConfig.Selector" 
		 data-widgetCode="SmartConfig.Selector" 
		 data-sivParams='{"bto":"/*configSelector"}'>
		 	<div>Dominio</div>
			<div data-sivCall="getInputFor" 
				 data-sivParams='{"key":"id"}'>
		    </div>
			<div data-sivView="Siviglia.model.ads.SmartConfig.forms.Edit" 
				 data-widgetCode="Siviglia.model.ads.Demo.SmartConfig.Edit" 
				 data-sivParams='{"id":"/*configSelector/id"}'>
	        </div>
    </div>

    <div data-sivWidget="Siviglia.inputs.jqwidgets.ListBox" data-widgetParams=""
         data-widgetCode="Siviglia.inputs.jqwidgets.ListBox">
        <div data-sivId="inputNode"></div>
    </div>


</div>

<div class="widget">
	<div class="widget-content">
		<div data-sivView="SmartConfig.Selector"></div>
	</div>
</div>



<script>

	Siviglia.Utils.buildClass({
		context: 'Siviglia.inputs.jqwidgets',
		classes: {
			ListBox: {
				inherits: "ComboBox",
				methods: {
					getJqxWidgetName: function () {
                        return "jqxListBox";
                    },
                    getDefaultInputOptions: function () {
                        var self = this;
                        this.dataSource = {
                            localdata: [],
                            datatype: "array"
                        };
                        this.dataAdapter = new $.jqx.dataAdapter(self.dataSource);
                        return {
                            source: this.dataAdapter,
                            autoBind: false,
                            displayMember: this.getLabelField(),
                            valueMember: this.getValueField(),
							width: 300,
                            height: 250,
                            filterable: true,
                            filterPlaceHolder: "Buscar...",
                            searchMode: "containsignorecase",
                        };
                    },
                    getValue: function () {
                        // Si uso "val" para localizar el valor, me devuelve el valor del índice
                        // seleccionado en la lista, de modo que si ésta está filtrada devolverá
                        // un dominio incorrecto.  
                        var v = this.inputNode.jqxListBox('getSelectedItems')[0].value;
                        return v;
                        
                    },
                    _setInputValue: function (toSet) {
                        if (this.changing)
                            return;
                        this.changing = true;
                        if(toSet==null)
                        {

                            this.inputNode.jqxListBox('selectItem',-1);
                            this.inputNode.jqxListBox('val','');
                            this.type.setValue(null);

                        }
                        else {
                            var item = this.inputNode.jqxListBox('getItemByValue', toSet);
                            if (item) {
                                this.inputNode.jqxListBox('selectItem', item);
                                this.inputNode.jqxListBox('val', item.label);
                            }
                        }
                        this.changing = false;
                    },
				},
			},
	    }
    });
	
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
                                    "id": {
                                        "LABEL":"Select domain",
                                        "TYPE":"String",
                                        "ROLE": "List",
                                        "SOURCE":{
                                            "TYPE":"DataSource",
                                            "MODEL":"/model/ads/SmartConfig",
                                            "DATASOURCE":"DomainList",
                                            "LABEL":"domain",
                                            "VALUE":"domain"
                                        }
                                    },
                                },
                                "INPUTPARAMS": {
                                	"/":{
                                		"INPUT": "FlexContainer"
					},
					"/id":{
                                		"INPUT": "ListBox"
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
