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
        <div style="background-color: #333333;border-bottom: 2px solid black;color: white;font-family: Verdana;font-size: 20px;font-weight: bold;padding: 3px;text-align: right;">
<div style="margin:2px;background-color:#222">
    Titulo de la lista
    </div>
</div>
Descripcion de la lista

   </div>
   <div>   
       <table width="100%">
              
    
           <tr>
            <th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
id_site
</div>
</th><th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
Host
</div>
</th><th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
Canonical url
</div>
</th><th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
Has SSL
</div>
</th><th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
namespace
</div>
</th><th  style="border-bottom:1px solid #AAAAAA;"><div style="font-family:Verdana;font-size:10px;text-decoration:underline">
name
</div>
</th>
           </tr>
         <?php 
$v11object= $v8object;
$v11name= $v8dsName;
$v11serializer= $v8serializer;
$v11params= $v8params;

?><?php

        if($v11object)
        {
            $v11ds=\lib\datasource\DataSourceFactory::getDataSource(str_replace("/","\\",$v11object),$v11name);
            if($v11params)
            {
                $v11defDs=$v11ds->getDefinition();

                if(is_object($v11params))
                {
                    $v11def=$v11params->getDefinition();

                    if(isset($v11defDs["INDEXFIELDS"]))
                    {
                        foreach($v11defDs["INDEXFIELDS"] as $v11key=>$v11value)
                            $v11ds->{$v11key}=$v11params->{$v11key};

                    }
                    if(isset($v11defDs["PARAMS"]))
                    {
                        foreach($v11defDs["PARAMS"] as $v11key=>$v11value)
                        {
                            if(isset($v11def["FIELDS"][$v11key]))
                                $v11ds->{$v11key}=$v11params->{$v11key};
                        }
                    }
                }
                else
                {
                    foreach($v11defDs["PARAMS"] as $v11key=>$v11value)
                    {
                        if(isset($v11params[$v11key]))
                            $v11ds->{$v11key}=$v11params[$v11key];
                    }
                }
            }
            if(isset($v11dsParams))
            {
                $v11pagingParams=$v11ds->getPagingParameters();
                foreach($v11dsParams as $v11key=>$v11value)
                    $v11pagingParams->{$v11key}=$v11value;
            }

            $v11ds->initialize();

         }
        ?><?php 
$v11iterator= &$v8iterator;

?><?php
    
      global $globalPath;
      global $globalContext;
      
      if(isset($v11subDs))
          $v11it=$globalPath->getPath($v11subDs,$globalContext);
      else
          $v11it=$v11ds->fetchAll();

      $globalPath->addPath($v11name,$v11it);
      $v11nItems=$v11it->count();      
      
      for($v11k=0;$v11k<$v11nItems;$v11k++)
      {
          $globalPath->addPath($v11name,$v11it[$v11k]);
          $v11iterator=$v11it[$v11k];

      
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
    <div style="width:20px;height:20px;background-color:red"></div>
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

