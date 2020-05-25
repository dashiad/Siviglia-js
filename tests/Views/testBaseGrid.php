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
<?php include_once(__DIR__."/../../jQuery/JqxLists.html");?>
<div style="display:none">

    <div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer">
    </div>
    <div data-sivWidget="Test.ButtonList" data-widgetCode="Test.ButtonList">
        <div>
            <input type="button" value="Borrar" data-sivEvent="click" data-sivCallback="onClicked">
        </div>
    </div>

</div>

<div data-sivView="Test.ListViewer" data-sivLayout="Siviglia.lists.jqwidgets.BaseGrid"></div>

<script>
    Siviglia.Utils.buildClass({
        "context":"Test",
        "classes":{
            ListViewer:{
                "inherits":"Siviglia.lists.jqwidgets.BaseGrid",
                "methods":{
                    preInitialize:function(params)
                    {
                        this.BaseGrid$preInitialize({
                            "ds": {
                                "model": "/model/ads/AdManager/LineItemSummary",
                                "name": "FullList",
                                "settings":{
                                    pageSize:20

                                }
                                },
                            "columns":{

                                "id":{"Type":"Field","Field":"id","Label":"id",gridOpts:{width:"80px"}},
                                "Id-name":{"Label":"Pstring","Type":"PString","str":'<a href="#" onclick="javascript:alert([%*id%]);">[%*name%]</a>'},
                                "Wid":{"Label":"Wid","Type":"Widget","Widget":"Test.ButtonList"},
                                "name":{"Type":"Field","Field":"name","Label":"name"},
                                "orderId":{"Type":"Field","Field":"orderId","Label":"orderId"},
                                "startDateTime":{"Type":"Field","Field":"startDateTime","Label":"startDateTime"},
                                "endDateTime":{"Type":"Field","Field":"endDateTime","Label":"endDateTime"},
                                "creativeRotationType":{"Type":"Field","Field":"creativeRotationType","Label":"creativeRotationType",gridOpts:{width:"30px",height:"100px"}},
                                "lineItemType":{"Type":"Field","Field":"lineItemType","Label":"lineItemType",gridOpts:{width:"50px"}},
                                "priority":{"Type":"Field","Field":"priority","Label":"priority"},
                                "budget":{"Type":"Field","Field":"budget","Label":"budget"},
                                "status":{"Type":"Field","Field":"status","Label":"status",gridOpts:{width:"80px"}},
                                "isArchived":{"Type":"Field","Field":"isArchived","Label":"isArchived"}
                            },
                            "gridOpts":{
                                width:"100%",
                                //rowsheight:100
                            }
                            }
                        );
                    }

                }
            },
            ButtonList:{
                "inherits":"Siviglia.UI.Expando.View",
                "methods":{
                    preInitialize:function(params)
                    {
                        this.data=params.row;

                    },
                    initialize:function(params)
                    {

                    },
                    onClicked:function(node,params)
                    {
                        alert(this.data.id);
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
