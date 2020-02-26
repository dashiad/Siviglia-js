<!-- NOTA: NO FUNCIONA esta dependencia para añadir ficheros al final del body -->
<?php $v23bodyClasses=$v15bodyClasses;
 ?><?php
    $v23page=\Registry::getService("page");
    $v23site=\Registry::getService("site");
    $v23name=$v23page->getPageName();
    $v23siteName=$v23site->getName();
    $v23layout_structure = 'horizontal';
?>
<!DOCTYPE html>
<html lang="<?php echo $v23site->getDefaultIso();?>">
<head>
    <title>Backend Index - SmartClip v.1.0 beta</title><?php $__serialized__bundle__Global=file_get_contents('C:\xampp\htdocs\adtopy\sites\backend\html/../../..//sites/statics/html//backend/bundles/bundle_Global.srl');?><script type="text/javascript" src="http://statics.adtopy.com/backend/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/backend/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.css"/>
<?php $__serialized__bundle__Page=file_get_contents('C:\xampp\htdocs\adtopy\sites\backend\html/../../..//sites/statics/html//backend/bundles/bundle_Page.srl');?><script type="text/javascript" src="http://statics.adtopy.com/backend/bundles/Page-HEADERS-<?php echo $__serialized__bundle__Page;?>.js" ></script>


    <!-- select2 library for jQuery replacement for select boxes -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- script general que carga jquery 3 y demás dependencias de codigo js para la plantilla.
    Importante que esté antes de las siguientes librerías para que puedan funcionar bien: datatables, etc... -->
    <script src="http://statics.adtopy.com/backend/js/vendors/vendors2.min.js"></script>    

    <!-- datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.css"/>
 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.js"></script>
</head>


<body class="<?php echo $v23bodyClasses;?>" >


<!-- <body > -->
<div style="display:none">
    
</div>

    
  
    <!-- MENU NAVIGATION -->
    <?php
$v17serializer=\Registry::getService("storage")->getSerializerByName('web');
$v17currentPage=Registry::$registry["currentPage"];
$v17params=Registry::$registry["params"];
?>

<div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-smartclip navbar-without-dd-arrow navbar-brand-center" role="navigation" data-menu="menu-wrapper" data-nav="brand-center">
<?php $v18currentPage=$v17currentPage;
$v18object='/model/web/Site';
$v18dsName='FullList';
$v18serializer=$v17serializer;
$v18params=$v17params;
$v18iterator=&$v17iterator;
 ?><ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion">
    
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="http://backend.adtopy.com/" target="_self">
                <div class="sidebar-brand-icon">
                    <i class="icon-smartclip"></i>
                </div>
                <div class="sidebar-brand-text">Smartclip<sup> (backend)</sup></div>                
        </a>
    
<hr class="sidebar-divider my-0">

    
</ul>
</div>
    
    <!-- CONTENT -->
    <div class="app-content content-horizontal">
        <!-- BREADCRUMBS -->
        <?php
$v20serializer=\Registry::getService("storage")->getSerializerByName('web');
$v20currentPage=Registry::$registry["currentPage"];
$v20params=Registry::$registry["params"];
?>

<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 mb-0">
                        <li class="breadcrumb-item"><a href="http://backend.adtopy.com/" title="Backend HOME (SmartClip)"><i class="bx bx-home"></i></a></li>

                        <li class="breadcrumb-item">
                            <a id="item-tab" href="#">Jobs</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    
        
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">    
    <div class="card-header">
        <h4 class="card-title">Reporte ComScore</h4>        
        <a class="heading-elements-toggle">
            <i class="bx bx-dots-vertical font-medium-3"></i>
        </a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">                                
                
                <li><a data-action="expand"><?php $v13icon='expandir';
 ?><?php \output\html\helpers\Icon::getIcon($v13icon, $v13family); ?></a></li>
                <li><a data-action="collapse"><?php $v14icon='collapse';
 ?><?php \output\html\helpers\Icon::getIcon($v14icon, $v14family); ?></a></li>
              </li>                
              </li>
            </ul>
        </div>
                    
    </div>
    <div class="card-content collapse show">
        <div class="card-body">
            <p class="card-text text-justify"><?php
$v1serializer=\Registry::getService("storage")->getSerializerByName('web');
$v1currentPage=Registry::$registry["currentPage"];
$v1params=$v1currentPage->getPageParams();
?><?php $v8currentPage=$v1currentPage;
$v8object='/model/web/Jobs';
$v8dsName='FullList';
$v8serializer=$v1serializer;
$v8params=$v1params;
$v8iterator=&$v1iterator;
 ?><div class="card shadow mb-4">
   <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">        
        
            <i class="icon-statics"></i>
            Listado de trabajos
        </h5>
        <div class="row">
            <div class="col-sm-12">
                Tabla que muestra por fila los resultados de cada trabajo para la tarea ComScore.
            </div>
        </div>
   </div>
   <div class="card-body">
        <div class="table-responsive">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered dataTable" id="dataTableJobs" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                    <thead>
                    
                        <tr role="row">
                            <th  style="border-bottom:1px solid #AAAAAA;">ID</th><th  style="border-bottom:1px solid #AAAAAA;">Trabajo</th><th  style="border-bottom:1px solid #AAAAAA;">Tipo</th><th  style="border-bottom:1px solid #AAAAAA;">Estado</th><th  style="border-bottom:1px solid #AAAAAA;">Creado</th><th  style="border-bottom:1px solid #AAAAAA;">Actualizado</th>
                        </tr>
                        
                    </thead>
                    <tbody>
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
                                    <tr role="row">
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v2name='id_job';
$v2model=$v1iterator;
 ?><?php echo $v2model->{$v2name};?>
                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <a href="/Jobs/<?php echo $v1iterator->job_id;?>/view"><?php echo $v1iterator->job_id;?></a>
                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v4name='name';
$v4model=$v1iterator;
 ?><span><?php echo $v4model->{$v4name};?></span>

                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v5name='status';
$v5model=$v1iterator;
 ?><?php 
// imprime los valores de los jobs de sus tipos

switch ($v5model->{$v5name}) {
    case '0':
        //echo 'creado';
        echo '<i class="bx bx-info-circle primary" data-toggle="tooltip" data-placement="right" title="Job creado"></i>';
        break;
    case '1':   
        //echo 'en espera';
        echo '<i class="bx bx-time light" data-toggle="tooltip" data-placement="right" title="Job en espera"></i>';
        break;
    case '2':
        //echo 'en ejecucion';
        echo '<i class="bx bx-run warning" data-toggle="tooltip" data-placement="right" title="Job ejecutandose"></i>';
        break;
    case '3':
        //echo 'ok';
        echo "<i class='bx bx-check success' data-toggle='tooltip' data-placement='right' title='Job terminado'></i>";
        break;
    case '4':
        // echo 'error';
        echo '<i class="bx bx-error-alt danger" data-toggle="tooltip" data-placement="right" title="Job con errores"></i>';
        break;
    default:
        # code...
        break;
}

?>
                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v6name='created_at';
$v6model=$v1iterator;
 ?><?php echo $v6model->{$v6name};?>
                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v7name='updated_at';
$v7model=$v1iterator;
 ?><?php echo $v7model->{$v7name};?>
                                        </td>   
                                        
                                    </tr>
                                    <?php }?>
                    </tbody>
                    
                </table>
            </div>                    
        </div>


        </div>
    </div><!-- ./card-body -->
</div><!-- ./card shadow mb-4 -->    </p>
        </div>
    </div>
    
</div>
            </div>                
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer  footer-light">
    © Smartclip <?php echo date("Y"); ?>
</footer>

    <script src="http://statics.adtopy.com/backend/js/app2.js"></script>
    <script src="http://statics.adtopy.com/backend/js/app-menu.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vertical-menu-dark.js"></script>    
    <script src="http://statics.adtopy.com/backend/js/vendors/components.min.js"></script>    
    <script src="http://statics.adtopy.com/backend/js/vendors/pickers/picker.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vendors/pickers/picker.date.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vendors/pickers/picker.time.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vendors/pickers/pick-a-datetime.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vendors/pickers/daterangepicker.js"></script>
    <script src="http://statics.adtopy.com/backend/js/vendors/datatable-jobs.js"></script>    
</body>
</html>
