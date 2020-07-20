<?php
if(!$_GET["preview"]){

} else {
       global $editor;
       $currentEmail=$_GET["templateOut"];
       $localPrefix=$editor->getCurrentSite()->getLocalPrefix();
       $params=array();
        include_once(LIBPATH."/output/email/Email.php");
        $mail=new \lib\output\email\Email();

        if($_GET["sendTestEmail"]==1){
            //$asunto="Email Test Editor: ".$this->currentEmail->data["NAME"];
            $asunto="Email Test Editor";
            $mail->SendTemplate(_EDIT_EMAIL_,$asunto,$this->currentEmail->data["layoutDir"],$this->currentEmail->data["layout"]."_work",$params,$this->currentEmail->websiteNamespace,"acumba",$cc);
            echo "Email enviado";
        }


}
?>