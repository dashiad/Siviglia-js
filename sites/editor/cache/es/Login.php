<?php
$v0styleClass="";
$v0inputParams="";
$v0form=null;
?><?php $v8object='\\model\\web\\WebUser';
$v8layer='web';
$v8name='Login';
$v8form=&$v0form;
 ?><?php
 $v8modelkeys=null;
 $v8tModel=null;
 ?><?php
// Parameters : object / layer / name / & $form
  $v8curObject=$v8object;
  $v8needModel=true;
  $v8form=\lib\output\html\Form::getForm($v8object,$v8name,$v8modelkeys,$v8tModel);
  $v8actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
  $v8curSite= \model\web\Site::getCurrentWebsite();
  $v8curSiteName=$v8curSite->getName();
  global $request;
  $v8curUrl=$request->getUrl();


?><div class="bordercolor3" style="margin-top:10px;">
  
    <div class="cbackground3 ccolor3 fontSize3" style="padding:8px;text-align:right">
       
    </div>
  
    <div class="background2 color2 fontSize1" style="padding:8px">
                
        <form method="POST" enctype="multipart/form-data" action="/action">
        <input type="hidden" name="FORM" value="<?php echo $v8name?>">
        <input type="hidden" name="MODEL" value="<?php echo $v8object?>">
            <input type="hidden" name="SITE" value="<?php echo $v8curSiteName;?>">
            <input type="hidden" name="PAGE" value="<?php echo $v8curUrl;?>">

        <?php

            $v8hk=null;
            if(isset($v8keys))
            {
                $v8hk=$v8keys;
                foreach($v8keys as $v8curKey=>$v8curValue)
                {
                    echo "<input type=\"hidden\" name=\"KEYS[".$v8curKey."]\" value=\"".$v8curValue."\">";
                    $v8keys[]=$v8curValue;
                }
            }

            $v8hash=\lib\output\html\Form::getHash($v8name,$v8object,$v8curSiteName,$v8curUrl,$v8hk,\Registry::$registry["session"]);
        ?>
            <input type="hidden" name="__FROM[SECCODE]" value="<?php echo $v8hash;?>">
                <?php
                    if(isset($v8instanceError)) {
                        $v8hasMessage=false;
                    ?>
                <div style="background-color:red;padding:5px;color:white">
                    Elemento no encontrado
                </div>
                    <?php
                    }
                    ?><div class="titled_subcontainer formGroup">
        <div class="titled_subcontainer_title fontSize2 backgroundColor2 bordercolor2 color2"></div><div class="titled_subcontainer_contents">
            <table class="formGroupInputContainer" cellpadding="0" cellspacing="0">      
            <?php $v3name='LOGIN';
$v3styleClass=$v0styleClass;
 ?><?php

    
    $v3actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v3errored=0;
    if($v3actionResult)
    {
        $v3fieldErrors=$v3actionResult->getFieldErrors($v3name);        
        if($v3fieldErrors)
        {
            $v3errored=1;
            $v3extraClass=" errored";
        }  
        
    }
?>


<tr>
    <?php $v3extra=isset($v3extraClass)?$v3extraClass:""; $v3sc=isset($v3styleClass)?$v3styleClass:"";?>
    <td class="inputLabel<?php echo $v3extra;?><?php echo $v3sc;?>">
    USUARIO</td>
    <td class="inputContainer<?php echo $v3extra;?><?php echo $v3sc;?>">
           <?php $v1name='LOGIN';
$v1value='LOGIN';
$v1params=$v0inputParams;
$v1form=$v0form;
 ?><?php
    $v1val=$v1form->{$v1name}; 
    
?><?php $v2name=$v1name;
$v2value=$v1val;
 ?><input type="hidden" name="INPUTS[<?php echo $v2name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v2name;?>]" value="<?php echo $v2value;?>">

    </td>
    <td class="inputHelp<?php echo $v3extra;?><?php echo $v3sc;?>">
           
    </td>
</tr>

<?php $v6name='PASSWORD';
$v6styleClass=$v0styleClass;
 ?><?php

    
    $v6actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
    $v6errored=0;
    if($v6actionResult)
    {
        $v6fieldErrors=$v6actionResult->getFieldErrors($v6name);        
        if($v6fieldErrors)
        {
            $v6errored=1;
            $v6extraClass=" errored";
        }  
        
    }
?>


<tr>
    <?php $v6extra=isset($v6extraClass)?$v6extraClass:""; $v6sc=isset($v6styleClass)?$v6styleClass:"";?>
    <td class="inputLabel<?php echo $v6extra;?><?php echo $v6sc;?>">
    CLAVE</td>
    <td class="inputContainer<?php echo $v6extra;?><?php echo $v6sc;?>">
           <?php $v4name='PASSWORD';
$v4value='PASSWORD';
$v4params=$v0inputParams;
$v4form=$v0form;
 ?><?php
    $v4val=$v4form->{$v4name}; 
    
?><?php $v5name=$v4name;
$v5value=$v4val;
 ?><input type="hidden" name="INPUTS[<?php echo $v5name?>]" value="TextField">
<input type="text" name="FIELDS[<?php echo $v5name;?>]" value="<?php echo $v5value;?>">

    </td>
    <td class="inputHelp<?php echo $v6extra;?><?php echo $v6sc;?>">
           
    </td>
</tr>


            </table>
        </div>
</div>
<?php
            if($v8actionResult)
            {
                if(!$v8actionResult->isOk())
                {
                    ?><div style="color: red;width: 100%;text-align: center; margin: 10px auto;">Usuario/Contraseña incorrectos</div><?php
                }

                $v8fieldErrors=$v8actionResult->getGlobalErrors();
                if($v8fieldErrors)
                {
                    ?><?php
                }
            }
            ?><?php
            if($v8actionResult)
            {
                if(!$v8actionResult->isOk())
                {
                    ?><?php
                }

                $v8fieldErrors=$v8actionResult->getGlobalErrors();
                if($v8fieldErrors)
                {
                    ?><?php
                }
            }
            ?><div style="background-color:#666666;padding:6px;text-align: right;clear:both">
 <input type="submit" value="Aceptar">

</div>

        </form>
    
    </div>
  
</div>
