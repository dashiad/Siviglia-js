<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css'>
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js'></script>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="../Siviglia.js"></script>
    <script src="../SivigliaStore.js"></script>

    <script src="../SivigliaTypes.js"></script>
    <script src="../Model.js"></script>


    <script src="../../jqwidgets/jqx-all.js"></script>
    <script src="../../jqwidgets/globalization/globalize.js"></script>
    <link rel="stylesheet" href="/reflection/css/style.css">
    <link rel="stylesheet" href="../jQuery/JqxWidgets.css">
    <link rel="stylesheet" href="../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../jqwidgets/styles/jqx.light.css">

    <style type="text/css">
        #svgChart {
            width: 1000px;
            height: 500px
        }
    </style>
</head>
<style type="text/css">
</style>
<body style="background-color:#EEE">
<?php include_once("../jQuery/JqxViews.html"); ?>
<?php include_once("../jQuery/JqxTypes.html"); ?>
<?php include_once("../jQuery/JqxWidgets.html"); ?>
<div style="display:none">
    <div data-sivWidget="a.a" data-widgetCode="a.b">

    </div>
</div>


<!--- INSTANCIACION DEL EDITOR DE MODELOS -->
<div data-sivView="Siviglia.model.web.WebUser.views.View" data-sivParams='{"USER_ID":1}'></div>
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
        mapper:'Siviglia'
    };
    Siviglia.Model.initialize(Siviglia.config);


</script>
<script>
    var parser = new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>


</body>
</html>
