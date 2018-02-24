<?php
   $v10site=\Registry::getService("site");
   $v10ds=\lib\datasource\DataSourceFactory::getDataSource('\model\reflection\ReflectorFactory','NamespaceList');
   $v10it=$v10ds->fetchAll();
?><?php
    $v13page=\Registry::getService("page");
    $v13site=\Registry::getService("site");
    $v13name=$v13page->getPageName();
    $v13siteName=$v13site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v13site->getDefaultIso();?>">
<head>
    <title>Testing</title><?php $__serialized__bundle__Site=file_get_contents('/var/www/adtopy//sites/statics/html//reflection/bundles/bundle_Site.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com//node_modules/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css"/>
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
                    baseUrl:'<?php echo $v10site->getCanonicalUrl();?>/',
                    publicUrl:'<?php echo $v10site->getCanonicalUrl();?>',
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


</head>
<body id="<?php echo $v13name;?>" class="<?php echo 'site_'.$v13siteName.' page_'.$v13name.' ';?>nav-md">
<div style="display:none">
    <!-- HTML_DEPENDENCY Site WIDGETSTART 5a7f026cc01af --><!-- HTML DEPENDENCY END 5a7f026cc01af -->
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
                                <?php for($v10k=0;$v10k < $v10it->count();$v10k++){?>
                                    <li><a href="<?php $v10f=1;echo Registry::getService("router")->generateUrl("Namespaces",array('namespace'=>$v10it[$v10k]->name));?>"><?php echo $v10it[$v10k]->name;?></a></li>
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
            <?php
            global $SERIALIZERS;
            $v1currentPage=Registry::$registry["currentPage"];
            $v1params=Registry::$registry["params"];
            $v1serializer=\lib\storage\StorageFactory::getSerializerByName('web');
            $v1serializer->useDataSpace($SERIALIZERS["web"]["ADDRESS"]["database"]["NAME"]);
?><?php $v8currentPage=$v1currentPage;
$v8object='/web/Site';
$v8dsName='FullList';
$v8serializer=$v1serializer;
$v8params=$v1params;
$v8iterator=&$v1iterator;
 ?><div style="border:1px solid #DDDDDD;background-color:#EEEEEE">
   <div style="background-color:#CCCCCC">
        Titulo de la listaDescripcion de la lista

   </div>
   <div>   
       <table width="100%">
              
    
           <tr>
            <th  style="border-bottom:1px solid #AAAAAA;">id_site</th><th  style="border-bottom:1px solid #AAAAAA;">Host</th><th  style="border-bottom:1px solid #AAAAAA;">Canonical url</th><th  style="border-bottom:1px solid #AAAAAA;">Has SSL</th><th  style="border-bottom:1px solid #AAAAAA;">namespace</th><th  style="border-bottom:1px solid #AAAAAA;">name</th>
           </tr>
         <?php 
$v9object= $v8object;
$v9name= $v8dsName;
$v9serializer= $v8serializer;
$v9params= $v8params;

?><?php

        if($v9object)
        {
            $v9ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v9object),$v9name);
            if($v9params)
            {
                $v9defDs=$v9ds->getDefinition();

                if(is_object($v9params))
                {
                    $v9def=$v9params->getDefinition();

                    if(isset($v9defDs["INDEXFIELDS"]))
                    {
                        foreach($v9defDs["INDEXFIELDS"] as $v9key=>$v9value)
                            $v9ds->{$v9key}=$v9params->{$v9key};

                    }
                    if(isset($v9defDs["PARAMS"]))
                    {
                        foreach($v9defDs["PARAMS"] as $v9key=>$v9value)
                        {
                            if(isset($v9def["FIELDS"][$v9key]))
                                $v9ds->{$v9key}=$v9params->{$v9key};
                        }
                    }
                }
                else
                {
                    foreach($v9defDs["PARAMS"] as $v9key=>$v9value)
                    {
                        if(isset($v9params[$v9key]))
                            $v9ds->{$v9key}=$v9params[$v9key];
                    }
                }
            }
            if(isset($v9dsParams))
            {
                $v9pagingParams=$v9ds->getPagingParameters();
                foreach($v9dsParams as $v9key=>$v9value)
                    $v9pagingParams->{$v9key}=$v9value;
            }

            $v9ds->initialize();

         }
        ?><?php 
$v9iterator= &$v8iterator;

?><?php
    
      global $globalPath;
      global $globalContext;
      
      if(isset($v9subDs))
          $v9it=$globalPath->getPath($v9subDs,$globalContext);
      else
          $v9it=$v9ds->fetchAll();

      $globalPath->addPath($v9name,$v9it);
      $v9nItems=$v9it->count();      
      
      for($v9k=0;$v9k<$v9nItems;$v9k++)
      {
          $globalPath->addPath($v9name,$v9it[$v9k]);
          $v9iterator=$v9it[$v9k];

      
     ?>
                    <tr>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v2name='id_site';
$v2model=$v1iterator;
 ?><?php echo $v2model->{$v2name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v3name='host';
$v3model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v3model->{$v3name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v4name='canonical_url';
$v4model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v4model->{$v4name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v5name='hasSSL';
$v5model=$v1iterator;
 ?><?php if($v5model->{$v5name}){?>
    <div style="width:20px;height:20px;background-color:green"></div>
<?php } else{ ?>
    <div style="width:20px;height:20px;background-color:blue"></div>
<?php }?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v6name='namespace';
$v6model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v6model->{$v6name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v7name='websiteName';
$v7model=$v1iterator;
 ?><span style="font-family:Verdana;"><?php echo $v7model->{$v7name};?></span>

                        </td>
                        
                    </tr>
                    <?php }?>
    </table>
    </div>
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
