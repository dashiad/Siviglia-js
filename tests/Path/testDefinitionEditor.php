<html>
<head>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <!-- Siviglia -->
    <script src="../../Siviglia.js"></script>
    <script src="../../SivigliaTypes.js"></script>
    <script src="../../SivigliaStore.js"></script>
    <script src="../../Model.js"></script>
    <script src="../../SivigliaTypes.js"></script>

    <!-- jqxgrid -->
    <script src="../../../jqwidgets/jqx-all.js"></script>
    <script src="../../../jqwidgets/globalization/globalize.js"></script>

    <!-- CSS bases -->
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.light.css">

    <!-- este tira de variables css del segundo style.css -->
    <link rel="stylesheet" href="../../jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" href="../../../../reflection/css/style.css">

    <!-- cargar resto de css del theme -->
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/bootstrap.css">
    <!-- <link rel="stylesheet" href="../../../../reflection/css/bootstrap/bootstrap-extended.css"> -->
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/colors.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/components.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/vendors.min.css">
    <link rel="stylesheet" href="../../../../reflection/css/themes/dark-layout.css">
    <!-- <link rel="stylesheet" href="../../../../reflection/css/themes/semi-dark-layout.css"> -->
    <link rel="stylesheet" href="../../../../reflection/css/vendors/select2.min.css">

    <script src="../../../../reflection/js/vendors.min.js"></script>
    <script src="../../../../reflection/js/select2.min.js"></script>    

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
<!-- <body class="dark-layout" style="background-color:#F2F4F4;"> -->
<body>
<?php include_once(__DIR__."/../../jQuery/JqxWidgets.html"); ?>

<!--- INSTANCIACION DEL EDITOR DE MODELOS -->
<div class="widget">
    <div class="widget-content">
        <div data-sivView="Siviglia.model.reflection.MetaDefinition.forms.Edit" data-sivParams='{"id_page":2}'></div>    
    </div>
</div>

<!-- forms edit metadefinition -->
<!-- <div data-sivView="Siviglia.model.reflection.MetaDefinition.forms.Edit" data-sivParams='{"id_page":2}'></div> -->


<script>
    var parser=new Siviglia.UI.HTMLParser();
    parser.parse($(document.body));
</script>
</body>
</html>
