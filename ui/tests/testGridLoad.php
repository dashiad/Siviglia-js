<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TestGridLoad (FullList_Grid)</title>
    <link rel='stylesheet prefetch'
          href='http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css'>
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js'></script>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../../Siviglia.js"></script>
    <script src="../../SivigliaStore.js"></script>

    <script src="../../SivigliaTypes.js"></script>
    <script src="../../Model.js"></script>

    <script src="../../../jqwidgets/jqx-all.js"></script>
    <script src="../../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="/reflection/css/style.css">
    <link rel="stylesheet" href="../../jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" href="../../jQuery/css/jqx.base.css">
    <link rel="stylesheet" href="../../jQuery/css/jqx.adtopy-dev.css">
    <script src="/node_modules/autobahn-browser/autobahn.min.js"></script>
    <script src="/reflection/js/WampServer.js"></script>

    <style type="text/css">
        #svgChart {
            width: 1000px;
            height: 500px
        }
    </style>
</head>
<style type="text/css">
</style>
<body style="background-color:#EEE; background-image:none;">
<?php include_once("../jQuery/JqxViews.html"); ?>
<?php include_once("../jQuery/JqxTypes.html"); ?>
<?php include_once("../jQuery/JqxWidgets.html"); ?>
<?php include_once("../jQuery/JqxLists.html"); ?>

<!--- INSTANCIACION DEL EDITOR DE MODELOS -->
<!-- antigua llamada <div data-sivView="Siviglia.model.web.WebUser.lists.FullList" data-sivParams='{}'></div> -->

<!-- nueva llamada del FullList_Grid -->
<div data-sivView="Siviglia.model.smartclip.Sage.Supervisor.AUTILIS.lists.FullList_Grid" data-sivParams='{}'></div>
<!--<div data-sivView="Siviglia.model.web.WebUser.lists.FullList_Grid" data-sivParams='{}'></div>-->


<script>

var Siviglia=Siviglia || {};
    Siviglia.config={
        baseUrl:'http://reflection.adtopy.com/',
        staticsUrl:'http://statics.adtopy.com/',
        metadataUrl:'http://metadata.adtopy.com/',

        locale:'es-ES',
        // Si el mapper es XXX, debe haber:
        // 1) Un gestor en /lib/output/html/renderers/js/XXX.php
        // 2) Un Mapper en Siviglia.Model.XXXMapper
        // 3) Las urls de carga de modelos seria /js/XXX/model/zzz/yyyy....
        mapper:'Siviglia',
        user:{
            USER_ID:"1",
            TOKEN:"1"
        },
        wampServer:{
            "URL":"ws://127.0.0.1",
            "PORT":8999,
            "REALM":"adtopy"
        }
    };
    Siviglia.Model.initialize(Siviglia.config);
if(top.Siviglia.config.user!==null) {

    if (typeof top.Siviglia.config.wampServer !== "undefined") {

        var wConfig = Siviglia.config.wampServer;
        var wampServer = new Siviglia.comm.WampServer(
            wConfig.URL,
            wConfig.PORT,
            wConfig.REALM,
            top.Siviglia.config.user.TOKEN
        );
        Siviglia.Service.add("wampServer", wampServer);

    }
}

</script>
<script>
    var parser = new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>

</body>
</html>
