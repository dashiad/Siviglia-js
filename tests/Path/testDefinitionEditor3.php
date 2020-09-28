<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel='stylesheet prefetch'
          href='http://statics.adtopy.com/node-modules/font-awesome/css/font-awesome.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js'></script>
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
    <link rel="stylesheet" href="../../jQuery/JqxWidgets.css">
    <link rel="stylesheet" href="../../../../reflection/css/style.css">
    <link rel="stylesheet" href="../../../jqwidgets/styles/jqx.adtopy-dev.css">

    <!-- cargar resto de css del theme -->
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/bootstrap-extended.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/colors.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/components.css">
    <link rel="stylesheet" href="../../../../reflection/css/bootstrap/vendors.min.css">
    <link rel="stylesheet" href="../../../../reflection/css/themes/dark-layout.css">
    <link rel="stylesheet" href="../../../../reflection/css/vendors/select2.min.css">

    <script src="../../../../reflection/js/vendors.min.js"></script>
    <script src="../../../../reflection/js/select2.min.js"></script>

    <style type="text/css">
        #svgChart {
            width: 1000px;
            height: 500px
        }
    </style>
</head>
<style type="text/css">
</style>
<body style="background-color: #e4f9eb !important;">
<?php include_once("../../jQuery/JqxWidgets-felipe.html"); ?>
<div style="display:none">
    <!--
    ORIGINAL JM
    <div data-sivWidget="Siviglia.model.reflection.MetaDefinition.forms.Add" data-widgetCode="Siviglia.model.reflection.MetaDefinition.forms.Add">

        <div><div>Definition:</div>
            <div data-sivCall="getInputFor" data-sivParams='{"key":"definition"}'></div>
        </div>
        <div><input type="button" data-sivEvent="click" data-sivCallback="submit" value="Guardar"></div>
    </div> -->


    <!-- felipe custom -->
    <div data-sivWidget="Siviglia.model.reflection.MetaDefinition.forms.Add" data-widgetCode="Siviglia.model.reflection.MetaDefinition.forms.Add">
        <div class="widget-content">
            <div><div class="widget-title">Definition:</div>
                <div data-sivCall="getInputFor" data-sivParams='{"key":"definition"}'></div>
            </div>
            <div><input class="form-button" type="button" data-sivEvent="click" data-sivCallback="submit" value="Guardar"></div>
        </div>
    </div>

</div>




<!--- INSTANCIACION DEL EDITOR DE MODELOS -->
<div class="widget">
    <div data-sivView="Siviglia.model.reflection.MetaDefinition.forms.Add"></div>
</div>
<script>


    Siviglia.Utils.buildClass({
        "context":"Siviglia.model.reflection.MetaDefinition.forms",
        "classes":{
            Add:{
                "inherits":"Siviglia.inputs.jqwidgets.Form",
                "methods":{
                    preInitialize:function(params)
                    {
                        var p={
                            "keys":params,
                            "model":"/model/reflection/MetaDefinition",
                            "form":"Add"
                        };
                        var bto=new Siviglia.model.BaseTypedObject({
                            "FIELDS":{
                                "definition":{"LABEL":"Definition","TYPE":"/model/reflection/Model/types/ModelType"}
                            }
                        })
                        //var t=Siviglia.types.TypeFactory.getType(null,"/model/reflection/Model/ModelType",null);
                        return this.Form$preInitialize({bto:bto});
                    }
                }
            }
        }
    });

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
