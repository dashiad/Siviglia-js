<?php
   $v10site=\Registry::getService("site");
   $v10ds=\lib\datasource\DataSourceFactory::getDataSource('\model\reflection\ReflectorFactory','NamespaceList');
   $v10it=$v10ds->fetchAll();
?><?php
    $v14page=\Registry::getService("page");
    $v14site=\Registry::getService("site");
    $v14name=$v14page->getPageName();
    $v14siteName=$v14site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v14site->getDefaultIso();?>">
<head>
    <title>Testing</title><?php $__serialized__bundle__Site=file_get_contents('c:/xampp7/htdocs/adtopy//sites/statics/html//reflection/bundles/bundle_Site.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.js" ></script>
<?php $__serialized__bundle__Page=file_get_contents('c:/xampp7/htdocs/adtopy//sites/statics/html//reflection/bundles/bundle_Page.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>


</head>
<body id="<?php echo $v14name;?>" class="<?php echo 'site_'.$v14siteName.' page_'.$v14name.' ';?>nav-md">
<div style="display:none">
    <!-- HTML_DEPENDENCY Site WIDGETSTART 5dfa37393581f --><!-- HTML DEPENDENCY END 5dfa37393581f --><!-- HTML_DEPENDENCY Site WIDGETSTART 5dfa373913895 -->

<div id="" style="display:none">
    <div sivWidget="AUTOUI_FACTORY" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.Factory" class="WidgetFactory">
        <div class="factoryContainer"></div>
    </div>
    <div sivWidget="AUTOPAINTER_DictionaryType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.DictionaryPainter"
         class="DictionaryType ContainerType">
        <div class="panel panel-primary">
            <div class="panel-heading" sivValue="/*title" class="title"></div>
            <div class="panel-body">
                <!--<div class="container-fluid">-->
                <p class="text-info description" sivValue="/*description"></p>
                <div sivIf="/*hasSimpleType == false">
                <div class="row" style="margin-left:0px">
                    <div style="display:flex">
                    <div class="containerLabel" width="150px">
                        <div class="well">
                        <ul  class="nav nav-stacked" sivLoop="/*uinode/getKeys" contextIndex="current">
                            <li role="presentation">
                                <button class="btn btn-primary" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"key":"/@current"}'></button>
                                <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                            </li>
                        </ul>
                        </div>
                        <div class="newItemWidget" sivCall="buildNewItemWidget"></div>
                    </div>
                        <div sivIf="/*currentKey != null">
                        <div class="containerInput dictionaryInput">
                            <div class="currentWidget" sivCall="getInputFor" sivParams='{"key":"/*currentKey"}'>
                            </div>
                        </div>
                        </div>

                    </div>

                </div>
                </div>
                <div sivIf="/*hasSimpleType == true" >
                    <div  sivLoop="/*uinode/getKeys" contextIndex="current">
                        <div style="display:flex">
                        <div>
                            <span class="label label-primary" sivValue="/@current"></span>
                        </div>
                        <div>
                            <span sivCall="getInputFor" sivParams='{"key":"/@current"}'></span>
                        </div>
                        <div>
                            <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                        </div>
                        </div>
                    </div>
                    <div class="newItemWidget" sivCall="buildNewItemWidget"></div>
                </div>

                <!--</div>-->
            </div>
            <div class="actions">
                <div class="addItem" sivId="newItemNode"></div>
                <div style="float:right" class="saveNode" sivId="saveNode">
                    <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>

    <div sivWidget="AUTOPAINTER_FixedDictionaryType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FixedDictionaryPainter"
         class="DictionaryType ContainerType">
        <div class="panel panel-primary">
            <div class="panel-heading" sivValue="/*title" class="title"></div>
            <div class="panel-body">
                <p class="text-info description" sivValue="/*description"></p>
                <!--<div class="container-fluid">-->
                <div class="row" style="margin-bottom:10px;margin-left:0px">
                    <div class="containerLabel well" style="margin-right:5px">
                        <ul  class="nav nav-stacked" sivLoop="/*uinode/getKeys" contextIndex="current">
                            <li role="presentation">
                                <button class="btn btn-primary" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"key":"/@current"}'></button>
                                <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
                            </li>
                        </ul>
                        <div class="newItemWidget" sivId="newItemNode" sivCall="buildNewItemWidget" ></div>
                    </div>
                    <div class="containerInput dictionaryInput" style="padding-right:10px;padding-left:10px">
                        <div class="currentWidget" sivCall="getInputFor" sivParams='{"key":"/*currentKey"}'>
                        </div>
                    </div>
                </div>
                <!--</div>-->
                <div class="actions">
                    <div class="addItem" sivId="newItemNode"></div>
                    <div style="float:right" class="saveNode" sivId="saveNode">
                        <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_ContainerType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ContainerPainter"
         class="ContainerType">
        <div class="panel panel-primary">
            <!--<div class="panel-heading" sivValue="/*title" class="title"></div>-->
            <div class="panel-body">
                <p class="text-info description" sivValue="/*description"></p>

                    <div class="inputs container" sivLoop="/*uinode/getKeys" contextIndex="current">
                        <div class="row">
                            <div class="col col-sm-1 col-md-1">
                            <label class="text-primary"  class="control-label" sivCall="getLabel" sivParams='{"key":"/@current"}'></label>
                            </div>
                            <div class="col col-sm-11 col-md-11">
                            <div sivIf="/*uinode/isSimpleType == false" class="containerInput">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>

                            <div sivIf="/*uinode/isSimpleType == true">
                                <div sivCall="getSubInput" sivParams='{"key":"/@current"}'></div>
                            </div>
                            </div>

                        </div>
                    </div>
                    <div class="saveNode" sivId="saveNode">
                        <input type="button" value="Guardar" sivEvent="click" sivCallback="doSave">
                    </div>

            </div>

        </div>

    </div>

    <div sivWidget="AUTOPAINTER_StringType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.StringPainter" class="StringType">
        <div class="input-group" sivId="inputNode">
        </div>
    </div>
    <div sivWidget="AUTOPAINTER_BooleanType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.BooleanPainter" class="BooleanType">
        <div class="input-group" sivId="inputNode">
        </div>
    </div>
    <div sivWidget="AUTOPAINTER_ArrayType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ArrayPainter" class="ArrayType">
        <p class="text-info description" sivValue="/*description"></p>

        <div sivLoop="/*uinode/children" contextIndex="current" class="arrayValues" style="min-height:20px;margin-bottom:5px">
            <span class="label label-primary" style="margin-right:4px;font-size:14px">
                <span sivValue="/@current/value"></span>
                <span class="remove ion-close-circled " style="color:red;padding-left:5px" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current"}'></span>
            </span>
        </div>

        <div  class="newItem" sivId="newItemNode"></div>
    </div>

    <div sivWidget="AUTOPAINTER_ObjectArrayType" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.ObjectArrayPainter" class="ArrayType">
        <p class="text-info description" sivValue="/*description"></p>
        <div class="row" style="margin-left:0px">

                <div sivIf="/*keyDirection == VERTICAL">
                    <div style="display:flex">
                    <div class="well">
                    <ul  class="nav nav-stacked" sivLoop="/*uinode/children" contextIndex="current">
                        <li role="presentation">
                            <button class="btn btn-primary" sivCall="getLabel" sivValue="/@current" sivEvent="click" sivCallback="onLabelClicked" sivParams='{"index":"/@current-index"}'></button>
                            <span class="removebutton" style="float:right" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current-index"}'></span>
                        </li>
                    </ul>
                    <div class="newItemWidget" sivId="newItemNode">
                        <input type="button" sivEvent="click" sivCallback="addItem">
                    </div>
                    </div>
                    </div>
                </div>
                <div sivIf="/*keyDirection == HORIZONTAL">
                    <div>
                    <span class="newItemWidget add" sivId="newItemNode" sivEvent="click" sivCallback="addItem">
                    </span>
                    <span  sivLoop="/*uinode/children" contextIndex="current">
                        <span class="label label-primary" style="display:inline-block;margin-right:4px;font-size:14px">
                            <span sivCall="getLabel"  sivEvent="click" sivCallback="onLabelClicked" sivParams='{"index":"/@current-index"}'></span>
                            <span class="removebutton" sivEvent="click" sivCallback="onRemoveClicked" sivParams='{"key":"/@current-index"}'></span>
                        </span>
                    </span>
                    </div>
                </div>
                <div class="containerInput dictionaryInput">
                    <div class="currentWidget">
                    </div>
                </div>

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_NewItem" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.NewItemPainter" class="NewItem">
        <div class="NewItemString">

                <div style="display:table-row">
                    <div style="display:table-cell">
                    <input style="float:left" type="text" class="form-control  addKey" placeholder="Nuevo" sivId="newItemString">
                    <select style="float:left" class="form-control" sivId="newItemSelector"></select>
                    </div>
                    <div style="display:table-cell;vertical-align: top;line-height: 1.0;font-size: 20px;"><div sivEvent="click" sivCallback="onAdd" class="add"></div></div>

                </div>


        </div>
        <div class="NewItemSelector" style="display:none">

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_TypeSwitcher" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.TypeSwitchPainter" class="TypeSwitchType">
        <div class="panel panel-primary">
            <!--<div class="panel-heading" sivValue="/*title" class="title"></div>-->
            <div class="panel-body">
                <div sivId="fieldContainer"></div>
                <div class="typeChanger">
                    <div sivId="typeSwitchSelector">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_SelectorType" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.SelectorPainter" class="SelectorType">
        <div class="input-group" sivId="inputNode">
        </div>
        <div class="NewItemSelector" style="display:none">

        </div>
    </div>
    <div sivWidget="AUTOPAINTER_SubdefinitionType" widgetParams="parentObject,parentNode" widgetCode="Siviglia.AutoUI.Painter.SubdefinitionPainter" class="SubdefinitionType">
        <div sivId="subcontainer">

        </div>
    </div>

    <div sivWidget="AUTOPAINTER_FixedPainter" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FixedPainter" class="FixedType">
        <div sivValue="/*uinode/value"></div>
    </div>

    <div sivWidget="AUTOPAINTER_FormContainer" widgetParams="parentObject,parentNode,value" widgetCode="Siviglia.AutoUI.Painter.FormContainerPainter" class="FormContainerType">
        <div class="container">
            <div class="row">
                <div sivId="listContainer" class="col col-md-2 col-sm-2">
                    <ul class="nav nav-tabs tabs-left" sivLoop="/*groups" contextIndex="current">
                        <li class="nav-item">
                            <a class="nav-link" sivCall="getHLink" data-toggle="tab" sivValue="/@current/LABEL" sivParams='{"current":"/@current-index"}'></a>
                        </li>
                </ul>
                </div>
                    <div sivId="viewContainer" class="col col-md-10 col-sm-10">
                        <div class="tab-content card" sivLoop="/*groups" contextIndex="current">
                            <div class="tab-pane" sivCall="getContents" sivParams='{"current":"/@current-index"}' role="tabpanel">

                            </div>
                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>
<!-- HTML DEPENDENCY END 5dfa373913895 -->
</div>

    <div class="container body">

    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>John Doe</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a><h5 id="sectionTitle" style="font-size: 15px;font-weight: bold;color: #73899c;margin-top: 7px;">SECTION TITLE</h5></a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="images/img.jpg" alt="">John Doe
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="javascript:;"> Profile</a></li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><a href="javascript:;">Help</a></li>
                                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="page-title">
            
                <div class="title_left"><h3></h3></div>
            <script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-BODYEND-<?php echo $__serialized__bundle__Site;?>.js" ></script>

</body>
</html>
