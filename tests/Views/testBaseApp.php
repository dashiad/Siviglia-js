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
            site:'reflection',
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
<?php include_once(__DIR__."/../../jQuery/JqxApp.html");?>

<div class="widget">
    <div class="widget-content">
        <div data-sivView="Siviglia.model.reflection.Model.apps.ModelEditor"
             ></div>
    </div>
</div>

<script>
    var parser=new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>
</body>
</html>
