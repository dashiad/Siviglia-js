<html>
<!--
  Este ejemplo es muy parecido a testModelForm, la unica diferencia es que, mientras alli se pasaba el modelo
  como formulario, o sea, la definicion del modelo era la definicion del formulario, en este caso, se va a
  capturar la definicion del modelo, meter INPUTPARAMS para customizar la apariecncia del container del formulario.


-->
<head>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../../../Siviglia.js"></script>
    <script src="../../../SivigliaTypes.js"></script>
    <script src="../../../SivigliaStore.js"></script>
    <script src="../../../SivigliaTypes.js"></script>
    <script src="../../../Model.js"></script>

    <script src="../../../../jqwidgets/jqx-all.js"></script>
    <script src="../../../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="../../../jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" href="../../../../../reflection/css/style.css">
    <link rel="stylesheet" href="../../../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../../../jqwidgets/styles/jqx.adtopy-dev.css">
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
</script>
</head>

<body>
<?php include_once(__DIR__."/../../jQuery/JqxWidgets.html"); ?>
<div style="display:none">
    <div data-sivWidget="MyTest.test"></div>
</div>

<!-- con class="widget" se define un espacio con margenes, estilos, etc., que define lo que es un widget. 
Asi no se pone esa clase dentro de cada componente de jqxwidgets.html -->
<!-- class="widget-content" crea el estilo para ese widget (fondo blanco, bordes, etc..) -->
<div class="widget">
    <div data-sivView="MyTest.test" data-sivlayout="Siviglia.inputs.jqwidgets.Form"></div>
</div>

<!-- ejemplo anterior con widget-content -->
<!-- <div class="widget">
    <div class="widget-content">
        <div data-sivView="MyTest.test" data-sivlayout="Siviglia.inputs.jqwidgets.Form"></div>
    </div>
</div> -->

<script>
    Siviglia.Utils.buildClass({
        "context":"MyTest",
        "classes":{
            test:{
                "inherits":"Siviglia.inputs.jqwidgets.Form",
                "methods":{
                    preInitialize:function(params)
                    {
                        this.bType=new Siviglia.model.BaseTypedObject({
                            "FIELDS": {

                                "Field1": {
                                    "LABEL": "Field 1",
                                    "TYPE": "String"
                                },
                                "Field2": {
                                    "LABEL": "Field 2",
                                    "TYPE": "String"
                                },
                                "Field3": {
                                    "LABEL": "Field 3",
                                    "TYPE": "String"
                                },
                                "Field4": {
                                    "LABEL": "Field 4",
                                    "TYPE": "Integer"
                                },
                                "Field5": {
                                    "LABEL": "Field 5",
                                    "TYPE": "Integer"
                                },
                                "Field6": {
                                    "LABEL": "Field 6",
                                    "TYPE": "Integer"
                                },
                                // "Field7": {
                                //     "LABEL": "Field 7",
                                //     "TYPE": "/model/reflection/Model/types/ModelType"
                                // }  
                                // Falla, da este error:
                                // Siviglia.js:1075 --> PATH NOT FOUND::*type/definition/FIELDS/{%@current2%}/LABEL                              
                            },

                            GROUPS: {
                                //"G1": {"LABEL": "Grupo 1", "FIELDS": ["Field1","Field2","Field3"], "INPUTPARAMS": { "/": { "INPUT": "GridContainer" }} },
                                "G1": {"LABEL": "Grupo 1", "FIELDS": ["Field1","Field2","Field3"]},
                                "G2": {"LABEL": "Grupo 2", "FIELDS": ["Field4","Field5"]},
                                "G3": {"LABEL": "Grupo 3", "FIELDS": ["Field6"]},
                                //"G4": {"LABEL": "Grupo 4", "FIELDS": ["Field7"]}
                            },



                            "INPUTPARAMS":{
                                // "/": {
                                //     "INPUT": "AccordionContainer",
                                //     "JQXPARAMS":{width:700,height:500,position:top}
                                // }
                                // "/String": {
                                //     "INPUT": "Siviglia.inputs.jqxWidgets-json.String",
                                // Coge el tipo layout de GridContainer que tiene los widgets en JqxWidgets.html
                                // "/": {
                                //     "INPUT": "GridContainer"
                                // }
                                "/": {
                                    "INPUT": "GridContainer",
                                    "JQXPARAMS":{width:700,height:500}
                                }

                            }
                        });

                        var p={
                            "bto":this.bType
                        }
                        return this.Form$preInitialize(p);
                    },
                    setupBto:function()
                    {
                        this.Form$setupBto();
                    }
                }
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
