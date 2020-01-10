<?php
$v1serializer=\Registry::getService("storage")->getSerializerByName('web');
$v1params=Registry::$registry["PAGE"];
?><?php $v38object='/model/web/Page';
$v38layer='web';
$v38name='EditAction';
$v38form=&$v1form;
 ?><?php
 $v38modelkeys=null;
 $v38tModel=null;
 ?><?php
// Parameters : object / layer / name / & $form
  $v38curObject=$v38object;
  $v38needModel=true;
  $v38form=\lib\output\html\Form::getForm($v38object,$v38name,$v38modelkeys,$v38tModel);
  $v38actionResult=isset(Registry::$registry["lastAction"])?Registry::$registry["lastAction"]:null;
  $v38curSite= \model\web\Site::getCurrentWebsite();
  $v38curSiteName=$v38curSite->getName();
  global $request;
  $v38curUrl=$request->getUrl();


?><div class="bordercolor3" style="margin-top:10px;">
  
    <div class="cbackground3 ccolor3 fontSize3" style="padding:8px;text-align:right">
       
    </div>
  
    <div class="background2 color2 fontSize1" style="padding:8px">
                
        <form method="POST" enctype="multipart/form-data" action="/action">
        <input type="hidden" name="FORM" value="<?php echo $v38name?>">
        <input type="hidden" name="MODEL" value="<?php echo $v38object?>">
            <input type="hidden" name="SITE" value="<?php echo $v38curSiteName;?>">
            <input type="hidden" name="PAGE" value="<?php echo $v38curUrl;?>">

        <?php

            $v38hk=null;
            if(isset($v38keys))
            {
                $v38hk=$v38keys;
                foreach($v38keys as $v38curKey=>$v38curValue)
                {
                    echo "<input type=\"hidden\" name=\"KEYS[".$v38curKey."]\" value=\"".$v38curValue."\">";
                    $v38keys[]=$v38curValue;
                }
            }

            $v38hash=\lib\output\html\Form::getHash($v38name,$v38object,$v38curSiteName,$v38curUrl,$v38hk,\Registry::$registry["session"]);
        ?>
            <input type="hidden" name="__FROM[SECCODE]" value="<?php echo $v38hash;?>">
                <?php
                    if(isset($v38instanceError)) {
                        $v38hasMessage=false;
                    ?>
                <div style="background-color:red;padding:5px;color:white">
                    Elemento no encontrado
                </div>
                    <?php
                    }
                    ?>
        </form>
    
    </div>
  
</div>
