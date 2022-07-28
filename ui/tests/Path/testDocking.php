<html>
<!--
  Ejemplo de Docking de jqxwidgets: https://www.jqwidgets.com/jquery-widgets-documentation/documentation/jqxdocking/jquery-docking-styling-and-appearance.htm?search=

  Donde se puede ver la implementacion de widgets en forma de ventana.
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
    <div data-sivWidget="TestDocking.test"></div>
</div>

<!-- con class="widget" se define un espacio con margenes, estilos, etc., que define lo que es un widget. 
Asi no se pone esa clase dentro de cada componente de jqxwidgets.html -->
<!-- class="widget-content" crea el estilo para ese widget (fondo blanco, bordes, etc..) -->
<div class="widget">
    <div data-sivView="TestDocking.test" data-sivlayout="Siviglia.inputs.jqwidgets.Form"></div>
</div>

<script>
    Siviglia.Utils.buildClass({
        "context":"TestDocking",
        "classes":{
            test:{
                "inherits":"Siviglia.inputs.jqwidgets.Form",
                "methods":{
                    preInitialize:function(params)
                    {
                        this.bType=new Siviglia.model.BaseTypedObject({

                            "FIELDS": {

                                "GRUPO_1": {
                                    "TYPE": "Container",
                                    "LABEL": "Grupo 1 grid-container",
                                    "FIELDS": 
                                    {
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
                                    }
                                },

                                "GRUPO_2":
                                {
                                    "TYPE": "Container",
                                    "LABEL": "Grupo 2 grid-container",
                                    "FIELDS":
                                    {
                                        "Field4": {
                                            "LABEL": "Field 4",
                                            "TYPE": "Integer"
                                        },
                                        "Field5": {
                                            "LABEL": "Field 5",
                                            "TYPE": "Integer"
                                        },
                                    }
                                },

                                "GRUPO_3":
                                {
                                    "TYPE": "Container",
                                    "LABEL": "Grupo 3 normal",
                                    "FIELDS":
                                    {
                                        "Field8":
                                        {
                                            "LABEL": "Field 8",
                                            "TYPE": "String"
                                        },
                                        "Field9":
                                        {
                                            "LABEL": "Field 9",
                                            "TYPE": "Integer"
                                        }
                                    }
                                },
                                
                                "Field6": {
                                    "LABEL": "Field 6",
                                    "TYPE": "Integer"
                                },
                                "Field7": {
                                    "LABEL": "Field 7",
                                    "TYPE": "String"
                                }  
                            },

                            GROUPS: {
                                "G1": {"LABEL": "Tab 1", "FIELDS": ["GRUPO_1", "GRUPO_2"]},
                                "G2": {"LABEL": "Grupo 3", "FIELDS": ["GRUPO_3"]},
                                "G3": {"LABEL": "Grupo 44", "FIELDS": ["Field6"]},
                                "G4": {"LABEL": "Grupo 55", "FIELDS": ["Field7"]}
                            },

                            "INPUTPARAMS":{
                                "/": {
                                    "INPUT": "DockingContainer"                                    
                                },
                                

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