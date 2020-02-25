<?php
$v0page=Registry::$registry["PAGE"];
//var_dump($page);
$v0idJob=$v0page->job_id;
?><?php $v15bodyClasses="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static";?><?php $v30bodyClasses=$v15bodyClasses;
 ?><?php
    $v30page=\Registry::getService("page");
    $v30site=\Registry::getService("site");
    $v30name=$v30page->getPageName();
    $v30siteName=$v30site->getName();
    $v30layout_structure = 'horizontal';
?>
<!DOCTYPE html>
<html lang="<?php echo $v30site->getDefaultIso();?>">
<head>
    <title>EDITOR SECCION - v.1.0 beta Smartclip</title><?php $__serialized__bundle__Global=file_get_contents('/vagrant/adtopy/sites/backend/html/../../..//sites/statics/html//backend/bundles/bundle_Global.srl');?><script type="text/javascript" src="http://statics.adtopy.com/backend/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.js" ></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"/>
<link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/backend/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.css"/>


    <!-- select2 library for jQuery replacement for select boxes -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- script general que carga jquery 3 y demás dependencias de codigo js para la plantilla.
    Importante que esté antes de las siguientes librerías para que puedan funcionar bien: datatables, etc... -->
    <script src="http://statics.adtopy.com/editor/js/vendors/vendors2.min.js"></script>    

    <!-- datatables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.js"></script>



</head>


<body class="<?php echo $v30bodyClasses;?>" >


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
<?php $v19currentPage=$v17currentPage;
$v19object='/model/web/Site';
$v19dsName='FullList';
$v19serializer=$v17serializer;
$v19params=$v17params;
$v19iterator=&$v17iterator;
 ?><ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion">
    
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="http://editor.adtopy.com/" target="_self">
                <div class="sidebar-brand-icon">
                    <i class="icon-smartclip"></i>
                </div>
                <div class="sidebar-brand-text">Smartclip <sup>(beta)</sup></div>
                
        </a>
    
<hr class="sidebar-divider my-0">

    <?php 
$v20object= $v19object;
$v20name= $v19dsName;
$v20serializer= $v19serializer;
$v20params= $v19params;

?><?php

        if($v20object)
        {
            $v20ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v20object),$v20name);
            if($v20params)
            {
                $v20defDs=$v20ds->getDefinition();

                if(is_object($v20params))
                {
                    $v20def=$v20params->getDefinition();

                    if(isset($v20defDs["INDEXFIELDS"]))
                    {
                        foreach($v20defDs["INDEXFIELDS"] as $v20key=>$v20value)
                            $v20ds->{$v20key}=$v20params->{$v20key};

                    }
                    if(isset($v20defDs["PARAMS"]))
                    {
                        foreach($v20defDs["PARAMS"] as $v20key=>$v20value)
                        {
                            if(isset($v20def["FIELDS"][$v20key]))
                                $v20ds->{$v20key}=$v20params->{$v20key};
                        }
                    }
                }
                else
                {
                    foreach($v20defDs["PARAMS"] as $v20key=>$v20value)
                    {
                        if(isset($v20params[$v20key]))
                            $v20ds->{$v20key}=$v20params[$v20key];
                    }
                }
            }
            if(isset($v20dsParams))
            {
                $v20pagingParams=$v20ds->getPagingParameters();
                foreach($v20dsParams as $v20key=>$v20value)
                    $v20pagingParams->{$v20key}=$v20value;
            }

            $v20ds->initialize();

         }
        ?><?php 
$v20iterator= &$v19iterator;

?><?php

      global $globalPath;
      global $globalContext;

      if(isset($v20subDs))
          $v20it=$globalPath->getPath($v20subDs,$globalContext);
      else
          $v20it=$v20ds->fetchAll();

      $globalPath->addPath($v20name,$v20it);
      $v20nItems=$v20it->count();

      for($v20k=0;$v20k<$v20nItems;$v20k++)
      {
          $globalPath->addPath($v20name,$v20it[$v20k]);
          $v20iterator=$v20it[$v20k];


     ?>
                            <li class="nav-item">
                                
<a class="nav-link" id="<?php echo $v17iterator->namespace;?>-tab" href="/Site/<?php echo $v17iterator->namespace;?>"><i class="icon-<?php echo $v17iterator->namespace;?>"></i> <?php echo $v17iterator->namespace;?></a>
                            </li>
                        <?php }?>
</ul>
</div>

    <!-- MENU EXTRA -->
        <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-fixed navbar-light navbar-brand-center">
    <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">                        
                        <ul class="nav navbar-nav">
    menu extra izq (hamburguesa...)
    <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
</ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
menu extra derecha (zona usuario, perfil...)
</ul>                    
                </div>
            </div>
        </div>
    </div>        
    </nav>

    <!-- CONTENT -->
    <div class="app-content content-horizontal">
        <!-- BREADCRUMBS -->
        <?php
$v24serializer=\Registry::getService("storage")->getSerializerByName('web');
$v24currentPage=Registry::$registry["currentPage"];
$v24params=Registry::$registry["params"];
?>

<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <?php $v26currentPage=$v24currentPage;
$v26object='/model/web/Site';
$v26dsName='FullList';
$v26serializer=$v24serializer;
$v26params=$v24params;
$v26iterator=&$v24iterator;
 ?><nav aria-label="breadcrumb">
    <ol class="breadcrumb p-0 mb-0">
        <li class="breadcrumb-item"><a href="<?php $_SERVER['SERVER_NAME']; ?>" title="HOME"><i class="bx bx-home"></i></a></li>

    <?php 
$v27object= $v26object;
$v27name= $v26dsName;
$v27serializer= $v26serializer;
$v27params= $v26params;

?><?php

        if($v27object)
        {
            $v27ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v27object),$v27name);
            if($v27params)
            {
                $v27defDs=$v27ds->getDefinition();

                if(is_object($v27params))
                {
                    $v27def=$v27params->getDefinition();

                    if(isset($v27defDs["INDEXFIELDS"]))
                    {
                        foreach($v27defDs["INDEXFIELDS"] as $v27key=>$v27value)
                            $v27ds->{$v27key}=$v27params->{$v27key};

                    }
                    if(isset($v27defDs["PARAMS"]))
                    {
                        foreach($v27defDs["PARAMS"] as $v27key=>$v27value)
                        {
                            if(isset($v27def["FIELDS"][$v27key]))
                                $v27ds->{$v27key}=$v27params->{$v27key};
                        }
                    }
                }
                else
                {
                    foreach($v27defDs["PARAMS"] as $v27key=>$v27value)
                    {
                        if(isset($v27params[$v27key]))
                            $v27ds->{$v27key}=$v27params[$v27key];
                    }
                }
            }
            if(isset($v27dsParams))
            {
                $v27pagingParams=$v27ds->getPagingParameters();
                foreach($v27dsParams as $v27key=>$v27value)
                    $v27pagingParams->{$v27key}=$v27value;
            }

            $v27ds->initialize();

         }
        ?><?php 
$v27iterator= &$v26iterator;

?><?php

      global $globalPath;
      global $globalContext;

      if(isset($v27subDs))
          $v27it=$globalPath->getPath($v27subDs,$globalContext);
      else
          $v27it=$v27ds->fetchAll();

      $globalPath->addPath($v27name,$v27it);
      $v27nItems=$v27it->count();

      for($v27k=0;$v27k<$v27nItems;$v27k++)
      {
          $globalPath->addPath($v27name,$v27it[$v27k]);
          $v27iterator=$v27it[$v27k];


     ?>
                        <li class="breadcrumb-item">
                            <a id="<?php echo "item"; //echo $iterator->namespace;?>-tab" href="#"><?php echo "item"; //echo $iterator->namespace;?></a>
                        </li>
                    <?php }?>
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
        <h4 class="card-title">Detalle del trabajo: <?php echo $v0idJob; ?></h4>        
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
$v8object='/model/web/Jobs/Worker';
$v8dsName='FullList';
$v8serializer=$v1serializer;
$v8params=$v1params;
$v8iterator=&$v1iterator;
 ?><div class="card shadow mb-4">
   <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">        
        
            <i class="icon-statics"></i>
            Tabla del Worker
        </h5>
        <div class="row">
            <div class="col-sm-12">
                Detalles de los workers asociados al job.
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
                            <th  style="border-bottom:1px solid #AAAAAA;">ID</th><th  style="border-bottom:1px solid #AAAAAA;">Trabajo</th><th  style="border-bottom:1px solid #AAAAAA;">Índice</th><th  style="border-bottom:1px solid #AAAAAA;">Estado</th><th  style="border-bottom:1px solid #AAAAAA;">Creado</th><th  style="border-bottom:1px solid #AAAAAA;">Actualizado</th>
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
                                            <?php $v2myId=uniqid();?>
            <!-- Button trigger for basic modal -->
            <button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#componentmodal<?php echo $v2myId; ?>">
              <?php echo $v1iterator->id_worker; ?>
            </button>

            <!--Basic Modal -->
            <div class="modal fade text-left" id="componentmodal<?php echo $v2myId; ?>" tabindex="-1" role="dialog" aria-labelledby="ModalLabelComponent<?php echo $v2myId; ?>" style="display: none;" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
                <div class="modal-content">
                
                  <div class="modal-header bg-dark">
                    <h3 class="modal-title white" id="ModalLabelComponent<?php echo $v2myId; ?>">Worker <?php echo $v1iterator->id_worker; ?></h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p>
                      <?php 
$v2name='worker_id';
$v2model= $v1iterator;

?><?php  
                                            if ($v1iterator->status==3 || $v1iterator->status==4){
                                                $v1result=[];
                                                $v1json = json_decode($v1iterator->items,true);                                                
                                                foreach($v1json as $v1fila)
                                                {
                                                    echo "Nombre de call: <strong>".$v1fila['call']."</strong>"; echo "<br>";
                                                    echo "Params: <strong>".$v1fila['params']."</strong> <br />";
                                                }
                                            }
                                        ?>
                    </p>
                  </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                      <i class="bx bx-x d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                      <i class="bx bx-check d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Accept</span>
                    </button>
                  </div> -->
                </div>
              </div>
            </div>
          </div>

          
                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v3name='job_id';
$v3model=$v1iterator;
 ?><span><?php echo $v3model->{$v3name};?></span>

                                        </td>   
                                        
                                        <td style="border-bottom:1px solid #AAAAAA;">
                                            <?php $v4name='index';
$v4model=$v1iterator;
 ?><?php echo $v4model->{$v4name};?>
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
    (TODO) llamada al modulo del footer static
        <button class="btn btn-primary btn-icon scroll-top" type="button" style="display: inline-block;"><i class="bx bx-up-arrow-alt"></i></button>
    
</footer>


    <script src="http://statics.adtopy.com/editor/js/app2.js"></script>
    <script src="http://statics.adtopy.com/editor/js/app-menu.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vertical-menu-dark.js"></script>
    
    <!-- <script src="http://statics.adtopy.com/editor/js/vendors/prism.min.js"></script> -->
    <!-- disabled form-validation, because we use: metadata server-validation
    <script src="http://statics.adtopy.com/editor/js/vendors/jqBootstrapValidation.js"></script>
    <script src="http://statics.adtopy.com/editor/js/form/form-validation.js"></script>
     -->
    <script src="http://statics.adtopy.com/editor/js/vendors/components.min.js"></script>    
    <script src="http://statics.adtopy.com/editor/js/vendors/pickers/picker.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vendors/pickers/picker.date.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vendors/pickers/picker.time.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vendors/pickers/pick-a-datetime.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vendors/pickers/daterangepicker.js"></script>
    <script src="http://statics.adtopy.com/editor/js/vendors/datatable-jobs.js"></script>
    
    
</body>
</html>
