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
    <link rel="stylesheet" href="../../jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" href="../../../../reflection/css/style.css">
    <link rel="stylesheet" href="../../jQuery/css/jqx.base.css">
    <link rel="stylesheet" href="../../jQuery/css/jqx.adtopy-dev.css">

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
<body style="background-color:#EEE; background-image:none;">
<?php include_once(__DIR__."/../../jQuery/JqxWidgets.html"); ?>
<?php include_once(__DIR__."/../../jQuery/JqxLists.html");?>
<div style="display:none">

    <div data-sivWidget="Test.ListViewerForm" data-widgetCode="Test.ListViewerForm">
        <div class="input">
            <div class="label">Id</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"id"}'></div>
        </div>
        <div class="input">
            <div class="label">Name</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"name"}'></div>
        </div>
        <div class="input">
            <div class="label">OrderName</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"orderName"}'></div>
        </div>
        <div class="input">
            <div class="label">startDateTime</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"startDateTime"}'></div>
        </div>
        <div class="input">
            <div class="label">endDateTime</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"endDateTime"}'></div>
        </div>
        <div class="input">
            <div class="label">LineItemType</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"lineItemType"}'></div>
        </div>
        <div class="input">
            <div class="label">Status</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"status"}'></div>
        </div>
        <div class="input">
            <div class="label">isArchived</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"isArchived"}'></div>
        </div>
        <div class="input">
            <div class="label">isMissingCreatives</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"isMissingCreatives"}'></div>
        </div>
        <div class="input">
            <div class="label">userConsentEligibility</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"userConsentEligibility"}'></div>
        </div>
        <div class="input">
            <div class="label">remoteId</div>
            <div class="inputContainer" data-sivCall="getInputFor" data-sivParams='{"key":"remoteId"}'></div>
        </div>
    </div>

    <div data-sivWidget="Test.ListViewer" data-widgetCode="Test.ListViewer">
    </div>

    <div data-sivWidget="Test.ButtonList" data-widgetCode="Test.ButtonList">
        <div>
            <input type="button" value="Borrar" data-sivEvent="click" data-sivCallback="onClicked">
        </div>
    </div>

</div>

<div class="widget">
    <div class="widget-content">
        <div data-sivView="Test.ListViewer" data-sivLayout="Siviglia.lists.jqwidgets.BaseGrid"></div>
    </div>
</div>

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
                            "filters":"Test.ListViewerForm",
                            "ds": {
                                "model": "/model/ads/AdManager/LineItemSummary",
                                "name": "FullList",
                                "settings":{
                                    pageSize:20
                                }
                                },
                            "columns":{

                                "id":{"Type":"Field","Field":"id","Label":"id",gridOpts:{width:"80px"}},
                                "Id-name":{"Label":"Pstring","Type":"PString","str":'<a href="#" onclick="javascript:alert([%*id%]);">[%*name%]</a>',gridOpts:{width:'10%'}},
                                "Wid":{"Label":"Wid","Type":"Widget","Widget":"Test.ButtonList",gridOpts:{width:'10%'}},
                                "name":{"Type":"Field","Field":"name","Label":"name",gridOpts:{width:'10%'}},
                                "orderId":{"Type":"Field","Field":"orderId","Label":"orderId",gridOpts:{width:'10%'}},
                                "startDateTime":{"Type":"Field","Field":"startDateTime","Label":"startDateTime",gridOpts:{width:'10%'}},
                                "endDateTime":{"Type":"Field","Field":"endDateTime","Label":"endDateTime",gridOpts:{width:'10%'}},
                                "creativeRotationType":{"Type":"Field","Field":"creativeRotationType","Label":"creativeRotationType",gridOpts:{width:"30px",height:"100px"}},
                                "lineItemType":{"Type":"Field","Field":"lineItemType","Label":"lineItemType",gridOpts:{width:"50px"}},
                                "priority":{"Type":"Field","Field":"priority","Label":"priority"},
                                "budget":{"Type":"Field","Field":"budget","Label":"budget"},
                                "status":{"Type":"Field","Field":"status","Label":"status",gridOpts:{width:"40px"}},
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
            },
            ListViewerForm:{
                "inherits":"Siviglia.inputs.jqwidgets.Form",
                "methods":{
                    preInitialize:function(params)
                    {

                        return this.Form$preInitialize(params);
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
