<?php
    $v20page=\Registry::getService("page");
    $v20site=\Registry::getService("site");
    $v20name=$v20page->getPageName();
    $v20siteName=$v20site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v20site->getDefaultIso();?>">
<head>
    <title>El titulo</title><?php $__serialized__bundle__Global=file_get_contents('c:/xampp7/htdocs/adtopy//sites/statics/html//editor/bundles/bundle_Global.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/editor/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.css"/>


</head>
<body id="<?php echo $v20name;?>" class="<?php echo 'site_'.$v20siteName.' page_'.$v20name.' ';?>">
<div style="display:none">
    
</div>



    <div id="maincontainer">

    <div id="topsection"><div class="innertube"></div></div>

    <div id="contentwrapper">
    <div id="contentcolumn">
    <div class="innertube"><?php
$v5serializer=\Registry::getService("storage")->getSerializerByName('web');
$v5currentPage=Registry::$registry["currentPage"];
$v5params=$v5currentPage->getPageParams();
?><?php $v17currentPage=$v5currentPage;
$v17object='/model/web/Page';
$v17dsName='FullList';
$v17serializer=$v5serializer;
$v17params=$v5params;
$v17iterator=&$v5iterator;
 ?><div style="border:1px solid #DDDDDD;background-color:#EEEEEE">
   <div style="background-color:#CCCCCC">
        Titulo de la listaDescripcion de la lista

   </div>
   <div>
       <table width="100%">

    
           <tr>
            <th  style="border-bottom:1px solid #AAAAAA;">id_page</th><th  style="border-bottom:1px solid #AAAAAA;">Tag</th><th  style="border-bottom:1px solid #AAAAAA;">name</th><th  style="border-bottom:1px solid #AAAAAA;">date_add</th><th  style="border-bottom:1px solid #AAAAAA;">date_modified</th><th  style="border-bottom:1px solid #AAAAAA;">id_type</th><th  style="border-bottom:1px solid #AAAAAA;">isPrivate</th><th  style="border-bottom:1px solid #AAAAAA;">path</th><th  style="border-bottom:1px solid #AAAAAA;">title</th><th  style="border-bottom:1px solid #AAAAAA;">tags</th><th  style="border-bottom:1px solid #AAAAAA;">description</th>
           </tr>
         <?php 
$v18object= $v17object;
$v18name= $v17dsName;
$v18serializer= $v17serializer;
$v18params= $v17params;

?><?php

        if($v18object)
        {
            $v18ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v18object),$v18name);
            if($v18params)
            {
                $v18defDs=$v18ds->getDefinition();

                if(is_object($v18params))
                {
                    $v18def=$v18params->getDefinition();

                    if(isset($v18defDs["INDEXFIELDS"]))
                    {
                        foreach($v18defDs["INDEXFIELDS"] as $v18key=>$v18value)
                            $v18ds->{$v18key}=$v18params->{$v18key};

                    }
                    if(isset($v18defDs["PARAMS"]))
                    {
                        foreach($v18defDs["PARAMS"] as $v18key=>$v18value)
                        {
                            if(isset($v18def["FIELDS"][$v18key]))
                                $v18ds->{$v18key}=$v18params->{$v18key};
                        }
                    }
                }
                else
                {
                    foreach($v18defDs["PARAMS"] as $v18key=>$v18value)
                    {
                        if(isset($v18params[$v18key]))
                            $v18ds->{$v18key}=$v18params[$v18key];
                    }
                }
            }
            if(isset($v18dsParams))
            {
                $v18pagingParams=$v18ds->getPagingParameters();
                foreach($v18dsParams as $v18key=>$v18value)
                    $v18pagingParams->{$v18key}=$v18value;
            }

            $v18ds->initialize();

         }
        ?><?php 
$v18iterator= &$v17iterator;

?><?php

      global $globalPath;
      global $globalContext;

      if(isset($v18subDs))
          $v18it=$globalPath->getPath($v18subDs,$globalContext);
      else
          $v18it=$v18ds->fetchAll();

      $globalPath->addPath($v18name,$v18it);
      $v18nItems=$v18it->count();

      for($v18k=0;$v18k<$v18nItems;$v18k++)
      {
          $globalPath->addPath($v18name,$v18it[$v18k]);
          $v18iterator=$v18it[$v18k];


     ?>
                    <tr>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v6name='id_page';
$v6model=$v5iterator;
 ?><?php echo $v6model->{$v6name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v7name='tag';
$v7model=$v5iterator;
 ?><span style="font-family:Verdana;"><?php echo $v7model->{$v7name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <a href="/Page/<?php echo $v5iterator->id_page;?>/edit"><?php echo $v5iterator->name;?></a>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v9name='date_add';
$v9model=$v5iterator;
 ?><?php echo $v9model->{$v9name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v10name='date_modified';
$v10model=$v5iterator;
 ?><?php echo $v10model->{$v10name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v11name='id_type';
$v11model=$v5iterator;
 ?><?php echo $v11model->{$v11name};?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v12name='isPrivate';
$v12model=$v5iterator;
 ?><?php if($v12model->{$v12name}){?>
    <div style="width:20px;height:20px;background-color:green"></div>
<?php } else{ ?>
    <div style="width:20px;height:20px;background-color:blue"></div>
<?php }?>
                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v13name='path';
$v13model=$v5iterator;
 ?><span style="font-family:Verdana;"><?php echo $v13model->{$v13name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v14name='title';
$v14model=$v5iterator;
 ?><span style="font-family:Verdana;"><?php echo $v14model->{$v14name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v15name='tags';
$v15model=$v5iterator;
 ?><span style="font-family:Verdana;"><?php echo $v15model->{$v15name};?></span>

                        </td>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <?php $v16name='description';
$v16model=$v5iterator;
 ?><span style="font-family:Verdana;"><?php echo $v16model->{$v16name};?></span>

                        </td>
                        
                    </tr>
                    <?php }?>
    </table>
    </div>
</div>

</div>
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


     ?>
                    <tr>
                        
                        <td style="border-bottom:1px solid #AAAAAA;">
                            <a href="/Site/<?php echo $v1iterator->namespace;?>"><?php echo $v1iterator->namespace;?></a>

                        </td>
                        
                    </tr>
                    <?php }?>
    </table>
    </div>
</div>

</div>

    </div>

    <div id="rightcolumn">
    <div class="innertube"></div>
    </div>

    <div id="footer"></div>

    </div>

</body>
</html>
