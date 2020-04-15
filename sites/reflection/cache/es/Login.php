<?php
    $v3page=\Registry::getService("page");
    $v3site=\Registry::getService("site");
    $v3name=$v3page->getPageName();
    $v3siteName=$v3site->getName();
    $v3layout_structure = 'horizontal';
?>
<!DOCTYPE html>
<html lang="<?php echo $v3site->getDefaultIso();?>">
<head>
    <title></title><script type="text/javascript" src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js" ></script>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/editor/css/style.css"/>
<?php $__serialized__bundle__Page=file_get_contents('C:\xampp\htdocs\adtopy\install\config/../..//sites/statics/html//reflection/bundles/bundle_Page.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/jqwidgets/styles/jqx.base.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/jqwidgets/styles/jqx.darkblue.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com/packages/jqwidgets/jqx-all.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/jqwidgets/globalization/globalize.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/Siviglia/Siviglia.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/Siviglia/SivigliaStore.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/Siviglia/SivigliaTypes.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/Siviglia/Model.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>


</head>


<body class="<?php echo $v3bodyClasses;?>loginPage" >


<!-- <body > -->
<div style="display:none">
    
</div>

    <?php include_once(PROJECTPATH."/sites/statics/html/packages/Siviglia/jQuery/JqxWidgets.html"); ?>
        <div class="siteTitle">Reflection</div>
        <div class="siteSubtitle">Adtopy project</div>
        <div class="loginForm">
            <div class="formTitle">Acceso</div>
            <div class="formContainer">
            <div data-sivView="Siviglia.model.web.WebUser.forms.Login"></div>
            </div>
        </div>

    

</body>
</html>
