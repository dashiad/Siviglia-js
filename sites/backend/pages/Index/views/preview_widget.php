

                        <div class="sectionActions">

                            <table width="100%">
                                <tr>
                                    <td style="text-align:left" width="90%">
                                        <div class="formInput"><input type="button" class="btInfo" value="PREVIEW" onclick="doPreview()"></div>
                                    </td>
                                    <td style="text-align:right" width="5%">
                                        <div class="formInput" style="text-align:right;">
                                            <form method="POST"    name="formRecoverLayout" id="formRecoverLayout" action="?action=recoverLayout&<?php echo $linkTarget;?>">
                                                <input type="button" value="RECOVER" class="btCancel" onclick="javascript:recoverLayout()">
                                            </form>
                                        </div>
                                    </td>
                                    <td style="text-align:right" width="5%">
                                        <div class="formInput" style="text-align:right;">
                                            <form method="POST"  action="?action=acceptLayout&<?php echo $linkTarget;?>">
                                                <input type="submit" value="ACCEPT" class="btSend">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <br>

