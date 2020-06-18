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
    <div id="wid-1" data-sivWidget="Siviglia.test.w1" data-widgetCode="Siviglia.test.w1">
        <div id="view-2" data-sivView="Siviglia.test.w2" data-sivParams='{"it":"*n"}' data-noContainer="true">

        </div>
    </div>
    <div id="wid-2" data-sivWidget="Siviglia.test.w2" data-widgetCode="Siviglia.test.w2">
        <div data-sivLoop="*n" data-contextIndex="current">
            <span data-sivValue="[%@current%]"></span>
        </div>
    </div>
</div>


<!--<div data-sivView="Siviglia.model.web.Page.forms.Edit" data-sivParams='{"id_page":2}' data-sivlayout="Siviglia.inputs.jqwidgets.Container"></div>-->
<div id="view-1" data-sivView="Siviglia.test.w1" data-noContainer="true"></div>


<script>
    Siviglia.Utils.buildClass({
        "context":"Siviglia.test",
        "classes":{
            "w1":{
                "inherits": "Siviglia.UI.Expando.View",
                "methods":{
                    preInitialize:function(params){
                        this.n=["a","b","c"]
                    },
                    initialize:function(params){
                        setTimeout(function(){this.n.push("z")}.bind(this),3000);
                    }
                }
            },
            "w2":{
                "inherits": "Siviglia.UI.Expando.View",
                "methods":{
                    preInitialize:function(params){
                        this.n=params.it;
                    }
                    ,
                    initialize:function(params){}
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
