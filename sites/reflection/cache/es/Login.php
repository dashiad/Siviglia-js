<?php
    $v2page=\Registry::getService("page");
    $v2site=\Registry::getService("site");
    $v2name=$v2page->getPageName();
    $v2siteName=$v2site->getName();
    $v2layout_structure = 'horizontal';
?>
<!DOCTYPE html>
<html lang="<?php echo $v2site->getDefaultIso();?>">
<head>
    <title></title><script type="text/javascript" src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/js/vendor/jquery-ui.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/js/vendor/jquery.scrollTo-min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/js/vendor/jquery.ui.selectmenu.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/themes/redmond/theme.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/js/jdesktop.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/packages/njdesktop/js/jdesktop.widgets.js" ></script>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/css/style.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/css/jdesktop.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.structure.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/jquery-ui/jquery-ui.theme.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/jdesktop.forms.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/jdesktop.text.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/njdesktop/themes/redmond/style.css"/>
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


<body class="<?php echo $v2bodyClasses;?>loginPage" >


<!-- <body > -->
<div style="display:none">
    
</div>

    
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
<?php include_once(PROJECTPATH."/sites/statics/html/packages/Siviglia/jQuery/JqxWidgets.html"); ?>