<html>
<head>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../../Siviglia.js"></script>
    <script src="../../SivigliaTypes.js"></script>
    <script src="../../SivigliaStore.js"></script>
    <script src="../../Model.js"></script>
    <script src="../../SivigliaTypes.js"></script>
    <script src="../../../jqwidgets/jqx-all.js"></script>
    <script src="../../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.light.css">
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
    <div data-sivWidget="Siviglia.tests.b" data-widgetCode="Siviglia.tests.b">aaa</div>
    <div data-sivWidget="Siviglia.tests.a" data-widgetCode="Siviglia.tests.a">
        <div data-sivId="theNode"></div>
    </div>
</div>


<div data-sivView="Siviglia.tests.a"></div>


<script>
    Siviglia.Utils.buildClass({
        "context":"Siviglia.tests",
        "classes":{
            a:{
                "inherits":"Siviglia.UI.Expando.View",
                "methods":{
                    preInitialize:function(params)
                    {
                    },
                    initialize:function(params){
                        var stack = new Siviglia.Path.ContextStack();
                        var instance=new Siviglia.tests.b(
                            "Siviglia.tests.b",
                            {},
                            {},
                            $("<div></div>"),
                            stack
                        );
                        instance.__build().then(function(){
                            // Se crea el layout y se le pasa la instancia.

                            this.theNode.append(instance.rootNode);

                        }.bind(this))

                    }
                }
            },
            b:
                {
                    "inherits": "Siviglia.UI.Expando.View",
                    "methods": {
                        preInitialize: function (params) {
                        },
                        initialize: function (params) {

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
