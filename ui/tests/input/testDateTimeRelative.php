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
<script src="../../../Siviglia.js"></script>
<script src="../../../SivigliaStore.js"></script>

<script src="../../../SivigliaTypes.js"></script>
<script src="../../../Model.js"></script>


<script src="../../../../jqwidgets/jqx-all.js"></script>
<script src="../../../../jqwidgets/globalization/globalize.js"></script>
<link rel="stylesheet" href="/reflection/css/style.css">
<link rel="stylesheet" href="../../../jQuery/css/JqxWidgets.css">
<link rel="stylesheet" href="../../../jQuery/css/jqx.base.css">
<link rel="stylesheet" href="../../../jQuery/css/jqx.adtopy-dev.css">

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
				<div class="label">Fecha base</div>
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"baseDate"}'>
				</div>
			</div>
			<div class="label">Diferencia desde la fecha base</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"periods"}'>
				</div>
			</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"periodType"}'>
				</div>
			</div>
			<div class="type">
				<div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"
					data-sivParams='{"controller":"/*self","parent":"/*type","form":"/*form","key":"sign"}'>
				</div>
			</div>
			<div class="">
				<div data-sivView="Test.Output"
					data-sivParams='{"date":"/*baseDate","sign":"/*sign","periods":"/*periods","type":"/*periodType"}'>
				</div>
			</div>
		</div>
		<div data-sivWidget="Test.Output" data-widgetCode="Test.Output">
			<div class="type">
				<div class="label">Fecha calculada</div>
				<div>
					<span data-sivValue="[%/*date%]"></span>
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
	<script src="../../../SivigliaTypesRelativeDateTime.js"></script>
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
                                		baseDate: {
                                            LABEL:"Fecha",
                                            TYPE: "DateTime",
                                            DEFAULT: "NOW",
                                        },
                                        sign: {
                                            LABEL:"Sentido",
                                            TYPE: "Enum",
                                            VALUES: ["antes", "después"],
                                            DEFAULT: 1
                                        },
                                        periods: {
                                            LABEL:"Periodos",
                                            TYPE: "Integer",
                                            MIN: 0,
                                            DEFAULT: 1
                                        },
                                        periodType: {
                                            LABEL:"Tipo",
                                            TYPE: "Enum",
                                            VALUES: Siviglia.types.DateTimeRelative.PERIODS[locale],
                                            DEFAULT: 2
                                        }
                                    }
                                });
                                this.calculate();
                                return this.Form$preInitialize({bto:this.typedObj});
                            },
                            initialize: function (params) {
                        		this.form.typedObj.__fields.baseDate.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.sign.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.periods.addListener("CHANGE", this, "calculate");
                        		this.form.typedObj.__fields.periodType.addListener("CHANGE", this, "calculate");	
                            },
                            calculate: function () {
                        		this.baseDate = this.typedObj.baseDate;
                                this.sign = this.typedObj.sign;
                                this.periods = this.typedObj.periods;
                                this.periodType = this.typedObj.periodType;                            },
                        }
                    },
                    Output: {
                		inherits: "Siviglia.UI.Expando.View",
                		methods: {
							preInitialize: function (params) {
								if (!(params.date===null || params.date===undefined)) {							
                                    this.date = params.date;
                    				this.sign = params.sign;
                    				this.periods = params.periods;
                    				this.type = params.type;
								} else {
									this.date = "NA";
								}
                            },
                            initialize:function(params){
                        		var relDate = new Siviglia.model.BaseTypedObject({
                                "FIELDS": {
                                	date: {
                                    	TYPE: "DateTimeRelative",
                                    	LABEL: "Fecha relativa"
                                	}
                                }
                            });
                            relDate.date = params.date;
                            this.date = relDate.__fields.date.getDate(this.sign, this.periods, this.type);
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
