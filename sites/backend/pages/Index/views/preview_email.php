

                        <div class="sectionActions">

                            <table width="100%">
                                <tr>
                                    <td style="text-align:left" width="70%">
                                        <div class="formInput">

                               <!-- <input type="button" value="Preview" onclick="doPreviewEmail('<?php echo $this->currentEmail->data["view"]; ?>')">-->

                                        </div>
                                    </td>
                                    <td style="text-align:right" width="10%">
                                        <div class="formInput" style="text-align:right;">
                                            <form method="POST"  name="formSendEmailTest" id="formSendEmailTest" action="?site=<?php echo $curSite."&preview=1&email=".$this->currentEmail->data["id_section"]."&sendTestEmail=1&templateOut=".$this->currentEmail->data["view"];?>" target="_blank">
                                                <input type="submit" value="SEND TEST" class="btInfo">

                                            </form>
                                        </div>
                                    <td style="text-align:right" width="10%">
                                        <div class="formInput" style="text-align:right;">
                                            <form method="POST"  name="formRecoverLayout" id="formRecoverLayout" action="?action=recoverLayout&site=<?php echo $curSite."&".$sectionType;?>=<?php echo $curSection->id;?>">
                                                <input type="button" value="RECOVER" class="btCancel" onclick="javascript:recoverLayout()">
                                            </form>
                                        </div>
                                    </td>
                                    <td style="text-align:right" width="10%">
                                        <div class="formInput" style="text-align:right;">
                                            <form method="POST"  action="?action=acceptLayout&site=<?php echo $curSite."&".$sectionType;?>=<?php echo $curSection->id;?>">
                                                <input type="submit" value="ACCEPT" class="btSend">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="background-color: #ffffff" name="miPreviewEmail" id="miPreviewEmail">
                            <?php

                            include_once(LIBPATH."/output/email/Email.php");
                            $mail=new \lib\output\email\Email();
                            echo $mail->renderTemplate($this->currentEmail->data["layoutDir"]."/".$this->currentEmail->websiteNamespace."/".$this->currentEmail->data["layout"]."_work.wid",null,$this->currentEmail->websiteNamespace);
                            ?>
                        </div>


