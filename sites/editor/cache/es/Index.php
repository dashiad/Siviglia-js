<?php
    $v6page=\Registry::getService("page");
    $v6site=\Registry::getService("site");
    $v6name=$v6page->getPageName();
    $v6siteName=$v6site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v6site->getDefaultIso();?>">
<head>
    <?php $__serialized__bundle__Global=file_get_contents('c:/xampp7/htdocs/adtopy//sites/statics/html//editor/bundles/bundle_Global.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/editor/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.css"/>


</head>
<body id="<?php echo $v6name;?>" class="<?php echo 'site_'.$v6siteName.' page_'.$v6name.' ';?>">
<div style="display:none">
    
</div>



    <div id="maincontainer">

    <div id="topsection"><div class="innertube"></div></div>

    <div id="contentwrapper">
    <div id="contentcolumn">
    <div class="innertube"></div>
    </div>
    </div>

    <div id="leftcolumn">
    <div class="innertube"><?php
$v1serializer=\Registry::getService("storage")->getSerializerByName('web');
$v1currentPage=Registry::$registry["currentPage"];
$v1params=Registry::$registry["params"];
?><?php $v3currentPage=$v1currentPage;
$v3object='/model/web/Site';
$v3dsName='FullList';
$v3serializer=$v1serializer;
$v3params=$v1params;
$v3iterator=&$v1iterator;
 ?><div style="border:1px solid #DDDDDD;background-color:#EEEEEE">
   <div style="background-color:#CCCCCC">
        Sites

   </div>
   <div>
       <table width="100%">

    <?php 
$v4object= $v3object;
$v4name= $v3dsName;
$v4serializer= $v3serializer;
$v4params= $v3params;

?><?php

        if($v4object)
        {
            $v4ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v4object),$v4name);
            if($v4params)
            {
                $v4defDs=$v4ds->getDefinition();

                if(is_object($v4params))
                {
                    $v4def=$v4params->getDefinition();

                    if(isset($v4defDs["INDEXFIELDS"]))
                    {
                        foreach($v4defDs["INDEXFIELDS"] as $v4key=>$v4value)
                            $v4ds->{$v4key}=$v4params->{$v4key};

                    }
                    if(isset($v4defDs["PARAMS"]))
                    {
                        foreach($v4defDs["PARAMS"] as $v4key=>$v4value)
                        {
                            if(isset($v4def["FIELDS"][$v4key]))
                                $v4ds->{$v4key}=$v4params->{$v4key};
                        }
                    }
                }
                else
                {
                    foreach($v4defDs["PARAMS"] as $v4key=>$v4value)
                    {
                        if(isset($v4params[$v4key]))
                            $v4ds->{$v4key}=$v4params[$v4key];
                    }
                }
            }
            if(isset($v4dsParams))
            {
                $v4pagingParams=$v4ds->getPagingParameters();
                foreach($v4dsParams as $v4key=>$v4value)
                    $v4pagingParams->{$v4key}=$v4value;
            }

            $v4ds->initialize();

         }
        ?><?php 
$v4iterator= &$v3iterator;

?><?php

      global $globalPath;
      global $globalContext;

      if(isset($v4subDs))
          $v4it=$globalPath->getPath($v4subDs,$globalContext);
      else
          $v4it=$v4ds->fetchAll();

      $globalPath->addPath($v4name,$v4it);
      $v4nItems=$v4it->count();

      for($v4k=0;$v4k<$v4nItems;$v4k++)
      {
          $globalPath->addPath($v4name,$v4it[$v4k]);
          $v4iterator=$v4it[$v4k];


     ?><?php }?>