<?php
    $v0page=\Registry::getService("page");
?><?php
   $v3site=\Registry::getService("site");
   $v3ds=\lib\datasource\DataSourceFactory::getDataSource('\model\reflection\ReflectorFactory','NamespaceList');
   $v3it=$v3ds->fetchAll();
?><?php
    $v6page=\Registry::getService("page");
    $v6site=\Registry::getService("site");
    $v6name=$v6page->getPageName();
    $v6siteName=$v6site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v6site->getDefaultIso();?>">
<head>
    <title>Mi titulo</title><?php $__serialized__bundle__Site=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Site.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/font-awesome/css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/nprogress/nprogress.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/iCheck/skins/flat/green.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/jqvmap/dist/jqvmap.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/build/css/custom.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.css"/>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/jquery/dist/jquery.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/fastclick/lib/fastclick.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/vendors/iCheck/icheck.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/Siviglia.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/SivigliaTypes.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/Model.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/SivigliaStore.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com//packages/Siviglia/ModelInstance.js" ></script>
<script type="text/javascript">

                var Siviglia=Siviglia || {};
                Siviglia.config={
                    baseUrl:'<?php echo $v3site->getCanonicalUrl();?>/',
                    publicUrl:'<?php echo $v3site->getCanonicalUrl();?>',
                    namespaces:['backoffice','web'],
                    defaultNamespace:'backoffice',
                    jsFramework:'jquery',
                    datasourcePrefix:'datasources',
                    isDevelopment:0,
                    mapper: 'BackofficeMapper',
                    id_lang:'es'
                };
               Siviglia.Model.initialize(Siviglia.config);
                var oApp=new Siviglia.App.App({});
                var Page=new Siviglia.App.Page(oApp);
                oApp.setPage(Page);
            </script>
<script type="text/javascript">

                Siviglia.Utils.buildClass(
                    {
                        context: 'Reflection',
                        classes:
                            {
                                Paths:{
                                    construct:function()
                                    {
                                        this.paths={
                                            "loadDefinition":"/Reflection/Definitions/[%name%]"
                                        }
                                    },
                                    methods:
                                        {
                                            buildPath:function(name,params,controller)
                                            {
                                                var entry=Siviglia.issetOr(this.paths[name],null);
                                                if(entry==null)
                                                    throw "Path desconocido : "+name;
                                                var baseUrl=null;
                                                var link=null;
                                                if(Siviglia.isObject(entry))
                                                {
                                                    baseUrl=entry.baseUrl;
                                                    link=entry.link;
                                                }
                                                else
                                                {
                                                    baseUrl=top.Siviglia.config.publicUrl;
                                                    link=entry;
                                                }
                                                var ps=new Siviglia.Utils.ParametrizableString(controller);
                                                return ss.parse(baseUrl+link,params);
                                            }
                                        }
                                }
                            }
                    });
                top.Siviglia.Paths=new Reflection.Paths();
            </script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-HEADERS-<?php echo $__serialized__bundle__Site;?>.js" ></script>
<?php $__serialized__bundle__Page=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Page.srl');?><script type="text/javascript">

                Siviglia.Utils.buildClass({
                    context: 'Components',
                    classes: {
                        Tabbar: {
                            inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods: {
                                constants:
                                    {
                                        TAB_CHANGED:1
                                    },
                                preInitialize:function(params)
                                {

                                    this.tabSource=params.tabSource;
                                    this.tabLabel=params.tabLabel;
                                    this.type=params.type;
                                    this.pathRoot.context.tabLabel=this.tabLabel;
                                    this.tabNodes={};
                                    this.curSelection=null;

                                },
                                getCurrentTab:function()
                                {
                                    return this.curSelection;
                                },
                                setCurrentTab:function(node,params)
                                {
                                    this.curSelection=params.current[this.tabLabel];
                                    this.fireEvent(Components.Tabbar.TAB_CHANGED,{node:node,value:this.curSelection,index:params.idx});
                                },
                                getTabLabel:function(node,params)
                                {
                                    node.html(params.current[this.tabLabel]);
                                    var val=params.current[this.tabLabel];
                                    node.attr("href","#tab-"+val);
                                    node.attr("id","tabLabel-"+val)
                                    if(params.index==0)
                                        node.parent().attr("class",node.attr("class")+" active");

                                },
                                getTabContent:function(node,params)
                                {
                                    var val=params.current[this.tabLabel];
                                    node.attr("id","tab-"+val);
                                    node.attr("aria-labelledby","tabLabel-"+val);
                                    this.tabNodes[params.current[this.tabLabel]]=node;
                                    if(params.index==0)
                                        this.curSelection=val;
                                }
                            }
                        },
                        SelectorOrNew:{
                            inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods: {
                                preInitialize: function (params) {

                                    this.selectorSource = Siviglia.issetOr(params.url, null);
                                    this.options = params.options;
                                    this.selectorLabel = params.label;
                                    this.selectorValue = params.value;

                                },
                                initialize: function (params) {
                                    var source = null;
                                    var dataAdapter = null;
                                    if (this.selectorSource) {
                                        source =
                                            {
                                                datatype: "json",
                                                /*datafields: [
                                                 { name: this.selectorLabel },
                                                 ],*/
                                                url: this.selectorSource
                                            };
                                        dataAdapter = new $.jqx.dataAdapter(source);
                                    }
                                    else {

                                        dataAdapter = new $.jqx.dataAdapter(
                                            {
                                                datatype:'json',
                                                localData: this.options

                                            }
                                            );

                                    }

                                    this.combo.jqxComboBox(
                                        {
                                            width: 200,
                                            height: 25,
                                            selectionMode: "dropDownList",
                                            source: dataAdapter,
                                            displayMember: this.selectorLabel,
                                            valueMember: this.selectorValue
                                        });
                                    var m = this;
                                    this.combo.on('change', function (event) {
                                        if (event.args) {
                                            var item = event.args.item;
                                            if (item) {
                                                m.fireEvent("ELEMENT_CHANGE", {site: item.value})
                                            }
                                        }
                                    });
                                },
                                onNew: function () {
                                    var val = this.newItem.val();
                                    if (val == "") {
                                        alert("Introduce un nombre de configuracion");
                                        return;
                                    }
                                    this.fireEvent("NEW_ELEMENT", {site: val});
                                }
                            }
                        }
                    }
                });
            </script>
<script type="text/javascript">

                Siviglia.Utils.buildClass({
                    context: 'Reflection.Widgets',
                    classes: {
                        Namespace: {
                            inherits: "Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods: {
                                preInitialize:function(params)
                                {
                                    var p=$.Deferred();
                                    var m=new Siviglia.Model.Model("/model/reflection/ReflectorFactory");
                                    this.self=this;
                                    this.currentObject={name:'Selecciona un modelo'};
                                    this.currentObjectWidget=null;
                                    this.namespace=params.namespace;
                                    var t=this;
                                    m.getDataSource("NamespaceObjects",{namespace:params.namespace}).then(function(d){
                                        t.objects=d;
                                        p.resolve();
                                        });
                                    return p;
                                },
                                initialize:function(params)
                                {
                                    /*this.view.theTabs.viewObject.addListener(Components.Tabbar.TAB_CHANGED,function(ev,p){
                                        console.dir(arguments);
                                    })
                                    console.dir(this.theTabs);*/
                                },
                                onObjectSelected:function(current)
                                {
                                    if(this.currentObject==current)
                                        return;
                                    this.currentObject=current;
                                    if(this.currentObjectWidget) {
                                        this.currentObjectWidget.destruct();
                                        this.objectWrap.remove();
                                    }
                                    this.objectWrap=document.createElement("div");
                                    this.currentObjectWidget= new Reflection.Widgets.NamespaceObject('Reflection.Widgets.NamespaceObject',
                                        {object:current,controller:this},
                                        {},
                                        $(this.objectWrap),
                                        Siviglia.model.Root
                                    );
                                    this.objectContainer.append(this.objectWrap);
                                    this.notifyPathListeners();
                                }
                            }
                        },
                        ObjectList:{
                            inherits:"Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods:{
                                preInitialize:function(params)
                                {
                                    this.objects=params.objects;
                                    this.controller=params.controller;
                                    this.self=this;
                                },
                                initialize:function(params)
                                {

                                },
                                onObjectSelected:function(current)
                                {
                                    this.controller.onObjectSelected(current);
                                }
                            }
                        },
                        ObjectTree:{
                            inherits:"Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods:{
                                preInitialize:function(params)
                                {

                                },
                                initialize:function(params)
                                {

                                },
                                onClicked:function(node,params)
                                {

                                    this.controller.onObjectSelected(params.object);
                                }
                            }
                        },
                        NamespaceObject:{
                            inherits:"Siviglia.UI.Widget,Siviglia.Dom.EventManager",
                            methods:{
                                preInitialize:function(params)
                                {
                                    debugger;
                                    this.object=params.object;
                                    this.controller=params.controller;
                                    var m=new Siviglia.Model.Model("/model/reflection/Model");
                                    this.self=this;
                                    var t=this;
                                    m.getDataSource("ObjectSummary",{class:this.object.layer+'/'+this.object.class}).then(function(d){
                                        t.objects=d;
                                        p.resolve();
                                    });
                                    return p;
                                },
                                initialize:function(params)
                                {

                                }
                            }
                        }
                    }
                });
            </script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>
<style type="text/css">

            .miclase {width:100px;font-weight:bold}
            </style>
<style type="text/css">

            .miclase {width:100px;font-weight:bold}
            </style>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/reflection/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.css"/>


</head>
<body id="<?php echo $v6name;?>" class="<?php echo 'site_'.$v6siteName.' page_'.$v6name.' ';?>nav-md">
<div style="display:none">
    <!-- HTML_DEPENDENCY Site WIDGETSTART 5a7f522ce51fa --><!-- HTML DEPENDENCY END 5a7f522ce51fa --><!-- HTML_DEPENDENCY Page WIDGETSTART 5a7f522cabaac -->
<div sivWidget="Components.Tabbar" widgetParams="tabSource,tabLabel,type" widgetCode="Components.Tabbar" role="tabpanel">
    <ul class="nav nav-tabs bar_tabs" role="tablist" sivLoop="/*tabSource" contextIndex="current">
        <li role="presentation">
            <a role="tab" data-toggle="tab" sivCall="getTabLabel" sivParams='{"current":"/@current","index":"/@current-index"}' sivEvent="click" sivCallback="setCurrentTab"></a>
        </li>
    </ul>
    <div class="tab-content" sivLoop="/*tabSource" contextIndex="current">
        <div class="tab-pane fade" role="tabpanel" sivCall="getTabContent" sivParams='{"current":"/@current"}'>

        </div>
    </div>
</div>


<div sivWidget="Components.Box" class="x_panel">
    <div class="x_title">
        <h2 widgetNode="TITLE"></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                </ul>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" widgetNode="CONTENT">

    </div>
</div>

<div sivWidget="Components.SelectorOrNew" widgetParams="url,options,label,value" widgetCode="Components.SelectorOrNew">
        <div>
            <div sivId="combo" style="float:left">
            </div>
            <div style="float:left;margin-left:30px">
                <div class="form-group">
                    <div style="display:table-cell">
                        <input type="text" class="form-control" style="height:27px" sivId="newItem">
                    </div>
                    <div style="display:table-cell;padding-left:5px">
                        <button class="btn btn-primary" style="font-size:12px" sivEvent="click" sivCallback="onNew">Nuevo</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- HTML DEPENDENCY END 5a7f522cabaac --><!-- HTML_DEPENDENCY Page WIDGETSTART 5a7f522cabbec -->
<div sivWidget="Reflection.Widgets.Namespace" widgetParams="factoryClass" widgetCode="Reflection.Widgets.Namespace">
    <div class="page-title">
        <div class="title_left"><h3>Namespace <span sivValue="/*namespace"></span></h3></div>
        <div class="title_right"></div>
    </div>
    <div class="row">
        <div class="col-md-2 col-sm-2 well">
            <div sivId="modelTree" sivView="Reflection.Widgets.ObjectList" sivParams='{"objects":"/*objects","controller":"/*self"}'></div>
        </div>
        <div class="col-md-10 col-sm-10">
            <div sivView="Components.Box">
                <div viewNode="TITLE"><span sivValue="/*currentObject/name"></span></div>
                <div viewNode="CONTENT">
                    <div sivId="objectContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>



<div sivWidget="Reflection.Widgets.ObjectList" widgetParams="objects,controller" widgetCode="Reflection.Widgets.ObjectList">
    <div sivView="Reflection.Widgets.ObjectTree" sivParams='{"objects":"/*objects","controller":"/*self"}'></div>
</div>

<div sivWidget="Reflection.Widgets.ObjectTree" widgetParams="objects,controller" widgetCode="Reflection.Widgets.ObjectTree">
    <ul sivLoop="/*objects" contextIndex="current">
        <li><a href="#" sivEvent="click" sivCallback="onClicked" sivParams='{"object":"/@current"}' sivValue="/@current/name"></a>
            <div sivView="Reflection.Widgets.ObjectTree" sivParams='{"objects":"/@current/objects","controller":"/*controller"}'></div>
        </li>
    </ul>
</div>

<div sivWidget="Reflection.Widgets.NamespaceObject" widgetParams='object,controller' widgetCode="Reflection.Widgets.NamespaceObject">
    <h1 sivValue="/*object/name"></h1>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Definicion<span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="#">Acciones<span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="#">DataSources<span class="sr-only">(current)</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HTML<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Forms</a></li>
                            <li><a href="#">Views</a></li>
                            <li><a href="#">Widgets</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Javascript<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Forms</a></li>
                            <li><a href="#">Views</a></li>
                            <li><a href="#">Widgets</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div><!-- HTML DEPENDENCY END 5a7f522cabbec -->
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
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    
    <div class="menu_section">
            <h3>System</h3>
                <ul class="nav side-menu">
                    
                    <li>
                        <a><i class="fa fa-cubes"></i>Namespaces <span class="fa fa-chevron-down"></span></a>
                        
                            <ul class="nav child_menu">
                                <?php for($v3k=0;$v3k < $v3it->count();$v3k++){?>
                                    <li><a href="<?php $v3f=1;echo Registry::getService("router")->generateUrl("Namespaces",array('namespace'=>$v3it[$v3k]->name));?>"><?php echo $v3it[$v3k]->name;?></a></li>
                                <?php } ?>
                            </ul>
                        
                    </li>
                    
                </ul>
            
    </div>
    
    <div class="menu_section">
            <h3>Definitions</h3>
                <ul class="nav side-menu">
                    
                    <li>
                        <a><i class="fa fa-cubes"></i>Definitions <span class="fa fa-chevron-down"></span></a>
                        
                            <ul class="nav child_menu">
                                
                                    <li><a href="">Add</a></li>
                                
                            </ul>
                        
                    </li>
                    
                </ul>
            
    </div>
    
</div>
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
            
                <div class="title_right"></div>
            
            </div>
            <div class="clearfix"></div>
            
        <div sivView="Reflection.Widgets.Namespace" sivParams='{"factoryClass":"pepe","namespace":"<?php echo $v0page->namespace;?>"}'>
        </div>
    
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>



    <script type="text/javascript" src="http://statics.adtopy.com//node_modules/gentelella/build/js/custom.min.js" ></script>
<script type="text/javascript" src="http://statics.adtopy.com/reflection/bundles/Site-BODYEND-<?php echo $__serialized__bundle__Site;?>.js" ></script>

</body>
</html>
