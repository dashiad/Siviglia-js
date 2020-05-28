<html>
<!--
  Este ejemplo es muy parecido a testModelForm, la unica diferencia es que, mientras alli se pasaba el modelo
  como formulario, o sea, la definicion del modelo era la definicion del formulario, en este caso, se va a
  capturar la definicion del modelo, meter INPUTPARAMS para customizar la apariecncia del container del formulario.


-->
<head>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../../Siviglia.js"></script>
    <script src="../../SivigliaTypes.js"></script>
    <script src="../../SivigliaStore.js"></script>
    <script src="../../SivigliaTypes.js"></script>
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
    <div data-sivWidget="MyTest.test" data-widgetCode="MyTest.test">
    </div>
</div>

<div class="widget">
    <div class="widget-content">
        <div data-sivView="MyTest.test" data-sivlayout="Siviglia.inputs.jqwidgets.Form"></div>
    </div>
</div>

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
                                    "TYPE": "Integer"
                                },
                                "Field3": {
                                    "LABEL": "Field 3",
                                    "TYPE": "Integer"
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
                                }
                            },

                            GROUPS: {
                                "G1": {"LABEL": "Grupo 1", "FIELDS": ["Field1","Field2"]},
                                "G2": {"LABEL": "Grupo 2", "FIELDS": ["Field4","Field5"]},
                                "G3": {"LABEL": "Grupo 3", "FIELDS": ["Field6"]},
                                "G4": {"LABEL": "Grupo 1", "FIELDS": ["Field3"]}
                            },



                            "INPUTPARAMS":{
                                "/": {
                                    "INPUT": "MenuContainer",
                                    "JQXPARAMS":{width:700,height:500,position:top},
                                    "MENU":{
                                        "Actions":{
                                            "Label":"Actions",
                                            "Type":"Menu",
                                            "Menu":{
                                                "EditG1":{
                                                    "Label":"Edit G1",
                                                    "Type":"Form",
                                                    "Group":"G1",
                                                    "Action":{"Model":"/model/test/TestObject","Action":"EditG1"}
                                                },
                                                "EditG2":{
                                                    "Label":"Edit G2",
                                                    "Type":"From",
                                                    "Group":"G2",
                                                    "Action":{"Model":"/model/test/TestObject","Action":"EditG2"}
                                                },
                                                "SubMenu":{
                                                    "Label":"Submenu",
                                                    "Type":"Menu",
                                                    "Menu":{
                                                        "EditG3":{
                                                            "Label":"Edit G3",
                                                            "Type":"Form",
                                                            "Group":"G3",
                                                            "Action":{"Model":"/model/test/TestObject","Action":"EditG3"}
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                        "Actions2":{
                                            "Label":"Actions2",
                                            "Type":"Menu",
                                            "Menu":{
                                                "EditG4":{
                                                    "Label":"Edit G4",
                                                    "Type":"Form",
                                                    "Group":"G4",
                                                    "Action":{"Model":"/model/test/TestObject","Action":"EditG4"}
                                                }
                                            }
                                        }
                                    }
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
