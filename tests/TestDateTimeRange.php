<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Title</title>
<link rel='stylesheet prefetch'
	href='http://statics.adtopy.com/node_modules/font-awesome/css/font-awesome.css' />
<link rel='stylesheet prefetch'
	href='https://fonts.googleapis.com/css?family=Roboto' />
<script src='https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js'></script>
<script src="/node_modules/jquery/dist/jquery.js"></script>
<script src="../Siviglia.js"></script>
<script src="../SivigliaStore.js"></script>

<script src="../SivigliaTypes.js"></script>
<script src="../Model.js"></script>


<script src="../../jqwidgets/jqx-all.js"></script>
<script src="../../jqwidgets/globalization/globalize.js"></script>
<link rel="stylesheet" href="/reflection/css/style.css">
<link rel="stylesheet" href="../jQuery/css/JqxWidgets.css">
<link rel="stylesheet" href="../jQuery/css/jqx.base.css">
<link rel="stylesheet" href="../jQuery/css/jqx.adtopy-dev.css">

<style type="text/css">
#svgChart {
	width: 1000px;
	height: 500px
}
</style>
</head>
<style type="text/css">
</style>
<body style="background-color: #EEE; background-image: none;">
	<?php include_once("../jQuery/JqxWidgets.html"); ?>
	<div style="display: none;">
		<div data-sivWidget="Test.Input" data-widgetParams=""
			data-widgetCode="Test.Input">
			<div class="type">
				<div class="label">Inicio</div>
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"start"}'>
				</div>
			</div>
			<div class="label">Fin</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"end"}'>
				</div>
			</div>
			<div class="label">Cada</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"interval_value"}'>
				</div>
			</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"interval_type"}'>
				</div>
			</div>
			<div class="">
				<div data-sivView="Test.Output"
					data-sivParams='{"start":"/*start","end":"/*end","interval_value":"/*interval_value","interval_type":"/*interval_type"}'>
				</div>
			</div>
		</div>
		<div data-sivWidget="Test.Output" data-widgetCode="Test.Output">
			<div class="type">
				<div class="label">Momentos</div>
				<div>
					<div data-sivLoop="/*list" data-contextIndex="current">
						<div data-sivValue="[%/@current%]"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div data-sivView="Test.Input"></div>
	<script>
	var Siviglia = Siviglia || {};
	Siviglia.config = {
	    baseUrl : 'http://reflection.adtopy.com/',
	    staticsUrl : 'http://statics.adtopy.com/',
	    metadataUrl : 'http://metadata.adtopy.com/',
	    locale : 'es-ES',
	    // Si el mapper es XXX, debe haber:
	    // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
	    // 2) Un Mapper en Siviglia.Model.XXXMapper
	    // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
	    mapper : 'Siviglia'
	};
	Siviglia.Model.initialize(Siviglia.config);
    </script>
	<script src="../SivigliaTypesRelativeDateTime.js"></script>
	<script>
            Siviglia.Utils.buildClass({
                context:'Test',
                classes:{
                    "Input": {
                        inherits: "Siviglia.inputs.jqwidgets.Form",
                        methods: {
                            preInitialize: function (params) {
                                this.factory = Siviglia.types.TypeFactory;
                                this.self = this;
                                this.typeCol = [];
                                var locale = Siviglia.config.locale;
                                if (locale===undefined || Siviglia.types.DateTimeRelative.PERIODS[locale]===undefined)
                                    var locale="default";
                                this.typedObj = new Siviglia.model.BaseTypedObject({
                                    "FIELDS": {
                                		start: {
                                            LABEL:"Fecha inicio",
                                            TYPE: "DateTime",
                                            DEFAULT: "NOW",
                                        },
                                        end: {
                                            LABEL:"Fecha fin",
                                            TYPE: "DateTime",
                                            DEFAULT: "NOW",
                                        },
//                                         interval: {
//                                             LABEL:"Periodos",
//                                             TYPE: "DateTimeInterval",
//                                         },
                                        interval_value: {
                                            LABEL:"Valor",
                                            TYPE: "Integer",
                                            MIN: 1,
                                            DEFAULT: 1,
                                        },
                                        interval_type: {
                                            LABEL:"Tipo",
                                            TYPE: "Enum",
                                            VALUES: Siviglia.types.DateTimeInterval.PERIODS[locale],
                                            DEFAULT: 2,
                                        },
                                    }
                                });
                                this.calculate();
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                        		this.form.typedObj.__fields.start.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.end.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.interval_value.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.interval_type.addListener("CHANGE", this, "calculate");
                            },
                            calculate: function () {
                        		this.start = this.typedObj.start;
                                this.end = this.typedObj.end;
                                this.interval_value = this.typedObj.interval_value;
                                this.interval_type = this.typedObj.interval_type;
                            }
                        }
                    },
                    Output: {
                		inherits: "Siviglia.UI.Expando.View",
                		methods: {
		 				preInitialize: function (params) {
							    this.list = [];
							    var range = new Siviglia.types.DateTimeRange("", {});
                                range.__fields.startDate = params.start;
                                range.__fields.endDate = params.end;
                                var field = Siviglia.types.DateTimeInterval.PERIODS["default"][params.interval_type];
                                range.__fields.interval.__fields[field].setValue(params.interval_value);
                                list = range.getValue();
                                for(var val in list)
                                    this.list.push(list[val].getValue());
                            },
                            initialize: function(params) {
                                
                    		}
                        }
                    }
                }
            });
	</script>
	<script>
	var parser = new Siviglia.UI.HTMLParser();
	parser.parse($(document.body));
    </script>

</body>
</html>
