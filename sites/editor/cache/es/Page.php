<?php
    $v48page=\Registry::getService("page");
    $v48site=\Registry::getService("site");
    $v48name=$v48page->getPageName();
    $v48siteName=$v48site->getName();
?>
<!DOCTYPE html>
<html lang="<?php echo $v48site->getDefaultIso();?>">
<head>
    <title>El titulo</title><?php $__serialized__bundle__Global=file_get_contents('c:/xampp7/htdocs/adtopy//sites/statics/html//editor/bundles/bundle_Global.srl');?><link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/editor/bundles/Global-HEADERS-<?php echo $__serialized__bundle__Global;?>.css"/>


</head>
<body id="<?php echo $v48name;?>" class="<?php echo 'site_'.$v48siteName.' page_'.$v48name.' ';?>">
<div style="display:none">
    
</div>



    <div id="maincontainer">

    <div id="topsection"><div class="innertube"></div></div>

    <div id="contentwrapper">
    <div id="contentcolumn">
    <div class="innertube"><?php
$v5serializer=\Registry::getService("storage")->getSerializerByName('web');
$v5params=Registry::$registry["PAGE"];
?><?php $v42object='/model/web/Page';
$v42layer='web';
$v42name='EditAction';
$v42form=&$v5form;
 ?><?php
 $v42modelkeys=null;
 $v42tModel=null;
 ?><?php 
$v42keys= $v5formKeys;
$v42serializer= $v5serializer;
$v42model= &$v5currentModel;

?><?php echo $modelkeys=$keys;
                $modelSerializer=$serializer;
                $tModel=& $model;;?><?php
// Parameters : object / layer / name / & $form
  $v42curObject=$v42object;
  $v42needModel=true;
  $v42form=\lib\output\html\Form::getForm($v42object,$v42name,$v42modelkeys,$v42tModel);
  $v42actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
  $v42curSite= \model\web\Site::getCurrentWebsite();
  $v42curSiteName=$v42curSite->getName();
  global $request;
  $v42curUrl=$request->getUrl();


?><div class="cbackground3 ccolor3 fontSize3" style="padding:8px;text-align:right">EditAction \model\Page</div><div class="cbackground3 ccolor3 fontSize3" style="padding:8px;text-align:right">Form Description        
        <form method="POST" enctype="multipart/form-data" action="/action">
        <input type="hidden" name="FORM" value="<?php echo $v42name?>">
        <input type="hidden" name="MODEL" value="<?php echo $v42object?>">
            <input type="hidden" name="SITE" value="<?php echo $v42curSiteName;?>">
            <input type="hidden" name="PAGE" value="<?php echo $v42curUrl;?>">

        <?php

            $v42hk=null;
            if(isset($v42keys))
            {
                $v42hk=$v42keys;
                foreach($v42keys as $v42curKey=>$v42curValue)
                {
                    echo "<input type=\"hidden\" name=\"KEYS[".$v42curKey."]\" value=\"".$v42curValue."\">";
                    $v42keys[]=$v42curValue;
                }
            }

            $v42hash=\lib\output\html\Form::getHash($v42name,$v42object,$v42curSiteName,$v42curUrl,$v42hk,\Registry::$registry["session"]);
        ?>
            <input type="hidden" name="__FROM[SECCODE]" value="<?php echo $v42hash;?>">
                <?php
                    if(isset($v42instanceError)) {
                        $v42hasMessage=false;
                    ?>
                <div style="background-color:red;padding:5px;color:white">
                    Elemento no encontrado
                </div>
                    <?php
                    }
                    ?><div class="titled_subcontainer formGroup">
        <div class="titled_subcontainer_title fontSize2 backgroundColor2 bordercolor2 color2">Form Group Title</div><div class="titled_subcontainer_contents">Form Group Description
            <table class="formGroupInputContainer" cellpadding="0" cellspacing="0">      
            <?php $v8name='tag';
 ?><?php

    
    $v8actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v8errored=0;
    if($v8actionResult)
    {
        $v8fieldErrors=$v8actionResult->getFieldErrors($v8name);        
        if($v8fieldErrors)
        {
            $v8errored=1;
            $v8extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v8errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v8type='INVALID';
$v8code='2';

?><?php                     
              if($v8fieldErrors[$v8type][$v8code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v8type='UNSET';
$v8code='1';

?><?php                     
              if($v8fieldErrors[$v8type][$v8code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v8type='TOO_SHORT';
$v8code='100';

?><?php                     
              if($v8fieldErrors[$v8type][$v8code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v8type='TOO_LONG';
$v8code='101';

?><?php                     
              if($v8fieldErrors[$v8type][$v8code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v8type='INVALID_CHARACTERS';
$v8code='102';

?><?php                     
              if($v8fieldErrors[$v8type][$v8code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v8extra=isset($v8extraClass)?$v8extraClass:""; $v8sc=isset($v8styleClass)?$v8styleClass:"";?>
    <td class="inputLabel<?php echo $v8extra;?><?php echo $v8sc;?>">
    tag</td>
    <td class="inputContainer<?php echo $v8extra;?><?php echo $v8sc;?>">
           <?php $v6model=$v5currentModel;
$v6name='tag';
$v6form=$v5form;
 ?><?php $v6val=$v6form->{$v6name}; ?><?php $v7name=$v6name;
$v7value=$v6val;
 ?><input type="hidden" name="INPUTS[<?php echo $v7name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v7name;?>]" value="<?php echo $v7value;?>">

    </td>
    <td class="inputHelp<?php echo $v8extra;?><?php echo $v8sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v13name='id_site';
 ?><?php

    
    $v13actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v13errored=0;
    if($v13actionResult)
    {
        $v13fieldErrors=$v13actionResult->getFieldErrors($v13name);        
        if($v13fieldErrors)
        {
            $v13errored=1;
            $v13extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v13errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v13type='INVALID';
$v13code='2';

?><?php                     
              if($v13fieldErrors[$v13type][$v13code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v13type='UNSET';
$v13code='1';

?><?php                     
              if($v13fieldErrors[$v13type][$v13code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v13extra=isset($v13extraClass)?$v13extraClass:""; $v13sc=isset($v13styleClass)?$v13styleClass:"";?>
    <td class="inputLabel<?php echo $v13extra;?><?php echo $v13sc;?>">
    id_site</td>
    <td class="inputContainer<?php echo $v13extra;?><?php echo $v13sc;?>">
           <?php $v9model=$v5currentModel;
$v9name='id_site';
$v9form=$v5form;
 ?><?php
    $v9curModel=$v9form;
    $v9remField=$v9curModel->__getField($v9name);
    $v9localDefinition=$v9remField->getDefinition();

    if(!$v9ds)
    {        

        if(!is_a($v9remField,'lib\model\Relation1x1'))
        {

            $v9remoteDefinition=\lib\model\types\TypeFactory::getObjectField($v9localDefinition["MODEL"],$v9localDefinition["FIELD"]);
            $v9remField=new lib\model\Relation1x1($v9name,$v9curModel, $v9remoteDefinition, $v9remField->getValue());
        }        

        $v9params=$v9form->__getInputParams($v9name); 
        if($v9params["DATASOURCE"])
        {
            
            $v9ds=\lib\datasource\DataSourceFactory::getDataSource($v9params["DATASOURCE"]["MODEL"],$v9params["DATASOURCE"]["NAME"]);
            if(isset($v9params["DATASOURCE"]["PARAMS"]))
            {
                global $globalContext;
                global $globalPath;
                $globalContext->currentModel=$v9form;            
                foreach($v9params["DATASOURCE"]["PARAMS"] as $v9key=>$v9value)
                    $v9ds->{$v9key}=$globalPath->parseString($v9value,$globalContext);
            }            
        }
        else
           $v9ds=\lib\datasource\DataSourceFactory::getDataSource($v9remoteDefinition["MODEL"],"FullList");
        
                
    }
    try
    {
        $v9val=$v9model->{$v9localDefinition["FIELD"]};
    }catch(\lib\model\BaseModelException $v9e)
    {
        if($v9e->getCode()==\lib\model\BaseModelException::ERR_INVALID_OFFSET)
        {
            $v9val=null;
        }
    }
    $v9labelField=$v9params["LABEL"];
    $v9valueField=$v9params["VALUE"]; 
    ?><?php $v10name=$v9name;
$v10value=$v9val;
$v10form=$v9form;
 ?><?php
 echo "<input type=\"hidden\" name=\"INPUTS[".$v10name."]\" value=\"Select\">";
 $v10fDef=$v10form->__getInputParams($v10name);
 
 $v10valueField=isset($v10fDef["VALUE"])?$v10fDef["VALUE"]:null;
 $v10labelField=isset($v10fDef["LABEL"])?$v10fDef["LABEL"]:null;
 $v10mKey=is_array($v10valueField); 
 if($v10mKey)
     $v10nKeys=count($v10valueField); 
 $v10mLabels=is_array($v10labelField)?$v10labelField:array($v10labelField);
 if($v10mLabels)
     $v10nLabels=count($v10labelField);
 
?>
<select name="FIELDS[<?php echo $v10name?>]"  autocomplete="off">
    <?php
         if(isset($v10fDef["PRE_OPTIONS"]))
         {
             foreach($v10fDef["PRE_OPTIONS"] as $v10keyO=>$v10valueO)
                 echo '<option value="'.$v10keyO.'" '.($v10keyO==$v10value?'selected="selected"':"").'>'.$v10valueO.'</option>';
         }
    ?><?php 
$v10ds= $v9ds;

?><?php 
$v11name= $v10name;
$v11ds= $v10ds;
$v11inputIterator= &$v10selectIterator;

?><?php    
      global $globalContext; 
      $globalContext->name=$v11name;

?><?php 
$v12name= $v11name;
$v12ds= $v11ds;
$v12iterator= &$v11inputIterator;

?><?php

      global $globalPath;
      global $globalContext;

      if(isset($v12subDs))
          $v12it=$globalPath->getPath($v12subDs,$globalContext);
      else
          $v12it=$v12ds->fetchAll();

      $globalPath->addPath($v12name,$v12it);
      $v12nItems=$v12it->count();

      for($v12k=0;$v12k<$v12nItems;$v12k++)
      {
          $globalPath->addPath($v12name,$v12it[$v12k]);
          $v12iterator=$v12it[$v12k];


     ?><?php if(!$v10mKey)
            {
                $v10curVal=$v10selectIterator->{$v10valueField};
                
            }
            else
            {                      
                 $v10curVal="";
                 for($v10k=0;$v10k<$v10nKeys;$v10k++)
                     $v10curVal.=($v10k>0?',':"").$v10selectIterator->{$v10valueField[$v10k]};                      
            }
            $v10label=array();
            foreach($v10labelField as $v10labelValue)
                $v10label[]=$v10selectIterator->{$v10labelValue};
            
            ?><?php echo '<option value="'.$v10curVal.'" '.($v10value==$v10curVal?'selected="selected"':"").'>'?><?php echo implode(" ",$v10label).'</option>';?><?php }?>
</select>

    </td>
    <td class="inputHelp<?php echo $v13extra;?><?php echo $v13sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v16name='name';
 ?><?php

    
    $v16actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v16errored=0;
    if($v16actionResult)
    {
        $v16fieldErrors=$v16actionResult->getFieldErrors($v16name);        
        if($v16fieldErrors)
        {
            $v16errored=1;
            $v16extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v16errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v16type='INVALID';
$v16code='2';

?><?php                     
              if($v16fieldErrors[$v16type][$v16code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v16type='UNSET';
$v16code='1';

?><?php                     
              if($v16fieldErrors[$v16type][$v16code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v16type='TOO_SHORT';
$v16code='100';

?><?php                     
              if($v16fieldErrors[$v16type][$v16code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v16type='TOO_LONG';
$v16code='101';

?><?php                     
              if($v16fieldErrors[$v16type][$v16code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v16type='INVALID_CHARACTERS';
$v16code='102';

?><?php                     
              if($v16fieldErrors[$v16type][$v16code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v16extra=isset($v16extraClass)?$v16extraClass:""; $v16sc=isset($v16styleClass)?$v16styleClass:"";?>
    <td class="inputLabel<?php echo $v16extra;?><?php echo $v16sc;?>">
    name</td>
    <td class="inputContainer<?php echo $v16extra;?><?php echo $v16sc;?>">
           <?php $v14model=$v5currentModel;
$v14name='name';
$v14form=$v5form;
 ?><?php $v14val=$v14form->{$v14name}; ?><?php $v15name=$v14name;
$v15value=$v14val;
 ?><input type="hidden" name="INPUTS[<?php echo $v15name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v15name;?>]" value="<?php echo $v15value;?>">

    </td>
    <td class="inputHelp<?php echo $v16extra;?><?php echo $v16sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v19name='date_add';
 ?><?php

    
    $v19actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v19errored=0;
    if($v19actionResult)
    {
        $v19fieldErrors=$v19actionResult->getFieldErrors($v19name);        
        if($v19fieldErrors)
        {
            $v19errored=1;
            $v19extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v19errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v19type='INVALID';
$v19code='2';

?><?php                     
              if($v19fieldErrors[$v19type][$v19code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v19type='UNSET';
$v19code='1';

?><?php                     
              if($v19fieldErrors[$v19type][$v19code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v19extra=isset($v19extraClass)?$v19extraClass:""; $v19sc=isset($v19styleClass)?$v19styleClass:"";?>
    <td class="inputLabel<?php echo $v19extra;?><?php echo $v19sc;?>">
    date_add</td>
    <td class="inputContainer<?php echo $v19extra;?><?php echo $v19sc;?>">
           <?php $v17model=$v5currentModel;
$v17name='date_add';
$v17form=$v5form;
 ?><?php
    $v17val=$v17form->{$v17name}; 
    
?><?php $v18name=$v17name;
$v18value=$v17val;
 ?><input type="hidden" name="INPUTS[<?php echo $v18name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v18name;?>]" value="<?php echo $v18value;?>">

    </td>
    <td class="inputHelp<?php echo $v19extra;?><?php echo $v19sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v22name='date_modified';
 ?><?php

    
    $v22actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v22errored=0;
    if($v22actionResult)
    {
        $v22fieldErrors=$v22actionResult->getFieldErrors($v22name);        
        if($v22fieldErrors)
        {
            $v22errored=1;
            $v22extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v22errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v22type='INVALID';
$v22code='2';

?><?php                     
              if($v22fieldErrors[$v22type][$v22code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v22type='UNSET';
$v22code='1';

?><?php                     
              if($v22fieldErrors[$v22type][$v22code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v22extra=isset($v22extraClass)?$v22extraClass:""; $v22sc=isset($v22styleClass)?$v22styleClass:"";?>
    <td class="inputLabel<?php echo $v22extra;?><?php echo $v22sc;?>">
    date_modified</td>
    <td class="inputContainer<?php echo $v22extra;?><?php echo $v22sc;?>">
           <?php $v20model=$v5currentModel;
$v20name='date_modified';
$v20form=$v5form;
 ?><?php
    $v20val=$v20form->{$v20name}; 
    
?><?php $v21name=$v20name;
$v21value=$v20val;
 ?><input type="hidden" name="INPUTS[<?php echo $v21name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v21name;?>]" value="<?php echo $v21value;?>">

    </td>
    <td class="inputHelp<?php echo $v22extra;?><?php echo $v22sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v25name='id_type';
 ?><?php

    
    $v25actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v25errored=0;
    if($v25actionResult)
    {
        $v25fieldErrors=$v25actionResult->getFieldErrors($v25name);        
        if($v25fieldErrors)
        {
            $v25errored=1;
            $v25extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v25errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v25type='INVALID';
$v25code='2';

?><?php                     
              if($v25fieldErrors[$v25type][$v25code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v25type='UNSET';
$v25code='1';

?><?php                     
              if($v25fieldErrors[$v25type][$v25code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v25extra=isset($v25extraClass)?$v25extraClass:""; $v25sc=isset($v25styleClass)?$v25styleClass:"";?>
    <td class="inputLabel<?php echo $v25extra;?><?php echo $v25sc;?>">
    id_type</td>
    <td class="inputContainer<?php echo $v25extra;?><?php echo $v25sc;?>">
           <?php $v23model=$v5currentModel;
$v23name='id_type';
$v23form=$v5form;
 ?><?php       
    $v23val=$v23form->{$v23name}; 
?><?php $v24name=$v23name;
$v24value=$v23val;
 ?><input type="hidden" name="INPUTS[<?php echo $v24name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v24name;?>]" value="<?php echo $v24value;?>">

    </td>
    <td class="inputHelp<?php echo $v25extra;?><?php echo $v25sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v28name='isPrivate';
 ?><?php

    
    $v28actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v28errored=0;
    if($v28actionResult)
    {
        $v28fieldErrors=$v28actionResult->getFieldErrors($v28name);        
        if($v28fieldErrors)
        {
            $v28errored=1;
            $v28extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v28errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v28type='INVALID';
$v28code='2';

?><?php                     
              if($v28fieldErrors[$v28type][$v28code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v28type='UNSET';
$v28code='1';

?><?php                     
              if($v28fieldErrors[$v28type][$v28code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v28extra=isset($v28extraClass)?$v28extraClass:""; $v28sc=isset($v28styleClass)?$v28styleClass:"";?>
    <td class="inputLabel<?php echo $v28extra;?><?php echo $v28sc;?>">
    isPrivate</td>
    <td class="inputContainer<?php echo $v28extra;?><?php echo $v28sc;?>">
           <?php $v26model=$v5currentModel;
$v26name='isPrivate';
$v26form=$v5form;
 ?><?php
    $v26val=$v26form->{$v26name}; 
?><?php $v27name=$v26name;
$v27value=$v26val;
 ?><?php
  $v27curModel=Registry::$registry["currentFormModel"];
  $v27val=$v27curModel->{$v27name};
  $v27code.="<input type=\"hidden\" name=\"INPUTS[".$v27name."]\" value=\"Checkbox\">";
  $v27code.="<input type=\"checkbox\" name=\"FIELDS[".$v27name."]\"";
  if($v27value===true)
      $v27code.=" checked";
  echo $v27code.">";
?>
    </td>
    <td class="inputHelp<?php echo $v28extra;?><?php echo $v28sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v31name='path';
 ?><?php

    
    $v31actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v31errored=0;
    if($v31actionResult)
    {
        $v31fieldErrors=$v31actionResult->getFieldErrors($v31name);        
        if($v31fieldErrors)
        {
            $v31errored=1;
            $v31extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v31errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v31type='INVALID';
$v31code='2';

?><?php                     
              if($v31fieldErrors[$v31type][$v31code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v31type='UNSET';
$v31code='1';

?><?php                     
              if($v31fieldErrors[$v31type][$v31code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v31type='TOO_SHORT';
$v31code='100';

?><?php                     
              if($v31fieldErrors[$v31type][$v31code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v31type='TOO_LONG';
$v31code='101';

?><?php                     
              if($v31fieldErrors[$v31type][$v31code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v31type='INVALID_CHARACTERS';
$v31code='102';

?><?php                     
              if($v31fieldErrors[$v31type][$v31code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v31extra=isset($v31extraClass)?$v31extraClass:""; $v31sc=isset($v31styleClass)?$v31styleClass:"";?>
    <td class="inputLabel<?php echo $v31extra;?><?php echo $v31sc;?>">
    path</td>
    <td class="inputContainer<?php echo $v31extra;?><?php echo $v31sc;?>">
           <?php $v29model=$v5currentModel;
$v29name='path';
$v29form=$v5form;
 ?><?php $v29val=$v29form->{$v29name}; ?><?php $v30name=$v29name;
$v30value=$v29val;
 ?><input type="hidden" name="INPUTS[<?php echo $v30name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v30name;?>]" value="<?php echo $v30value;?>">

    </td>
    <td class="inputHelp<?php echo $v31extra;?><?php echo $v31sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v34name='title';
 ?><?php

    
    $v34actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v34errored=0;
    if($v34actionResult)
    {
        $v34fieldErrors=$v34actionResult->getFieldErrors($v34name);        
        if($v34fieldErrors)
        {
            $v34errored=1;
            $v34extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v34errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v34type='INVALID';
$v34code='2';

?><?php                     
              if($v34fieldErrors[$v34type][$v34code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v34type='UNSET';
$v34code='1';

?><?php                     
              if($v34fieldErrors[$v34type][$v34code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v34type='TOO_SHORT';
$v34code='100';

?><?php                     
              if($v34fieldErrors[$v34type][$v34code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v34type='TOO_LONG';
$v34code='101';

?><?php                     
              if($v34fieldErrors[$v34type][$v34code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v34type='INVALID_CHARACTERS';
$v34code='102';

?><?php                     
              if($v34fieldErrors[$v34type][$v34code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v34extra=isset($v34extraClass)?$v34extraClass:""; $v34sc=isset($v34styleClass)?$v34styleClass:"";?>
    <td class="inputLabel<?php echo $v34extra;?><?php echo $v34sc;?>">
    title</td>
    <td class="inputContainer<?php echo $v34extra;?><?php echo $v34sc;?>">
           <?php $v32model=$v5currentModel;
$v32name='title';
$v32form=$v5form;
 ?><?php $v32val=$v32form->{$v32name}; ?><?php $v33name=$v32name;
$v33value=$v32val;
 ?><input type="hidden" name="INPUTS[<?php echo $v33name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v33name;?>]" value="<?php echo $v33value;?>">

    </td>
    <td class="inputHelp<?php echo $v34extra;?><?php echo $v34sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v37name='tags';
 ?><?php

    
    $v37actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v37errored=0;
    if($v37actionResult)
    {
        $v37fieldErrors=$v37actionResult->getFieldErrors($v37name);        
        if($v37fieldErrors)
        {
            $v37errored=1;
            $v37extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v37errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v37type='INVALID';
$v37code='2';

?><?php                     
              if($v37fieldErrors[$v37type][$v37code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v37type='UNSET';
$v37code='1';

?><?php                     
              if($v37fieldErrors[$v37type][$v37code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v37type='TOO_SHORT';
$v37code='100';

?><?php                     
              if($v37fieldErrors[$v37type][$v37code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v37type='TOO_LONG';
$v37code='101';

?><?php                     
              if($v37fieldErrors[$v37type][$v37code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v37type='INVALID_CHARACTERS';
$v37code='102';

?><?php                     
              if($v37fieldErrors[$v37type][$v37code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v37extra=isset($v37extraClass)?$v37extraClass:""; $v37sc=isset($v37styleClass)?$v37styleClass:"";?>
    <td class="inputLabel<?php echo $v37extra;?><?php echo $v37sc;?>">
    tags</td>
    <td class="inputContainer<?php echo $v37extra;?><?php echo $v37sc;?>">
           <?php $v35model=$v5currentModel;
$v35name='tags';
$v35form=$v5form;
 ?><?php $v35val=$v35form->{$v35name}; ?><?php $v36name=$v35name;
$v36value=$v35val;
 ?><input type="hidden" name="INPUTS[<?php echo $v36name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v36name;?>]" value="<?php echo $v36value;?>">

    </td>
    <td class="inputHelp<?php echo $v37extra;?><?php echo $v37sc;?>">
           **Insert Field Help**
    </td>
</tr>

<?php $v40name='description';
 ?><?php

    
    $v40actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v40errored=0;
    if($v40actionResult)
    {
        $v40fieldErrors=$v40actionResult->getFieldErrors($v40name);        
        if($v40fieldErrors)
        {
            $v40errored=1;
            $v40extraClass=" errored";
        }  
        
    }
?><?php                     
       if($v40errored){                     
     ?>                
     <tr><td colspan=3 class="inputErrors">
       <ul class="inputErrorList">
          <?php 
$v40type='INVALID';
$v40code='2';

?><?php                     
              if($v40fieldErrors[$v40type][$v40code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v40type='UNSET';
$v40code='1';

?><?php                     
              if($v40fieldErrors[$v40type][$v40code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v40type='TOO_SHORT';
$v40code='100';

?><?php                     
              if($v40fieldErrors[$v40type][$v40code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v40type='TOO_LONG';
$v40code='101';

?><?php                     
              if($v40fieldErrors[$v40type][$v40code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?><?php 
$v40type='INVALID_CHARACTERS';
$v40code='102';

?><?php                     
              if($v40fieldErrors[$v40type][$v40code]){ 
             ?>
                <li class="inputError">Error</li>
             <?php }?>
         </ul>
         </td>
      </tr>
        <?php } ?>


<tr>
    <?php $v40extra=isset($v40extraClass)?$v40extraClass:""; $v40sc=isset($v40styleClass)?$v40styleClass:"";?>
    <td class="inputLabel<?php echo $v40extra;?><?php echo $v40sc;?>">
    description</td>
    <td class="inputContainer<?php echo $v40extra;?><?php echo $v40sc;?>">
           <?php $v38model=$v5currentModel;
$v38name='description';
$v38form=$v5form;
 ?><?php $v38val=$v38form->{$v38name}; ?><?php $v39name=$v38name;
$v39value=$v38val;
 ?><input type="hidden" name="INPUTS[<?php echo $v39name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v39name;?>]" value="<?php echo $v39value;?>">

    </td>
    <td class="inputHelp<?php echo $v40extra;?><?php echo $v40sc;?>">
           **Insert Field Help**
    </td>
</tr>


            </table>
        </div>
</div>

        </form>
    </div></div>
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
