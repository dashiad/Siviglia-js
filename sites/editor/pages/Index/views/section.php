<?php
include_once(CUSTOMPATH."/pages/Editor/views/header.php");
if($curSite){
?>
<div class="contentDerecha">
    <?php
        if($curSection)
        {
        ?>
            <div class="sectionName">
            <?php if($curSubWidget){?>
                <a style="color:white;text-decoration:underline" href="index.php?site=<?php echo $curSite;?>&section=<?php echo $curSection->id;?>"><?php echo $curSection->data["NAME"];?></a>
                : <b><?php echo $curSubWidget;?></b>
                <?php } else {
                    echo $curSection->data["NAME"];
                 } ?>
            </div>
            <!--FORM SAVE SECTION-->
            <?php
            $linkTarget="site=".$curSite;
            switch($editor->getCurrentSectionType())
            {
                case "section":
                {
                    $linkTarget.="&section=".$curSection->id;
                    $subWidget=$editor->getCurrentSubWidget();
                    if($subWidget)
                    {
                        $linkTarget.="&widget=".$subWidget;
                    }
                }break;
                case "widget":
                {
                    $linkTarget.="&widget=".$_GET["widget"];
                }
            }


            ?>
            <form method="post" id="saveLayout" action="?action=saveLayout&<?php echo $linkTarget;?>">
                <?php
                $subWidget=$editor->getCurrentSubWidget();
                if($subWidget)
                {?>
                    <input type="hidden" name="widget" value="<?php echo $subWidget;?>">
                <?php
                }
                ?>
                <textarea id="elm1" name="layout" rows="30" cols="100"><?php
                    echo $curSection->getEditableLayout($subWidget);
                    ?></textarea>
                <div class="sectionActions">
                    <table>
                        <tr>
                            <td><input type="button" value="RESET" class="btCancel" onclick="javascript:resetSaveLayout()"></td>
                            <td><input type="submit" value="SAVE" class="btSend"></td>
                        </tr>
                    </table>

                </div>
                <!-- </textarea> -->
            </form>
            <?php
            if($curSection->data["id_type"]==1){
                include_once(CUSTOMPATH . "/pages/Editor/views/preview_section.php");
            }
            if($curSection->data["id_type"]==2){
                include_once(CUSTOMPATH . "/pages/Editor/views/preview_email.php");
            }
            if($curSection->data["id_type"]==3){
                include_once(CUSTOMPATH . "/pages/Editor/views/preview_widget.php");
            }
        }
    ?>
</div>
<!--Container Derecha-->
<div class="contentDerecha2">
    <div class="contentForm">
    <div class="headerType">Add new</div>
        <form method="POST" id="addSection" action="?action=addSection&site=<?php echo $curSite;?>">
            <div style="float:left">
            <table>
                        <tr>
                            <td class="formlabel">Name</td><td class="formInput"><input name="name" class="textBoxSmall" type="text"></td>
                            </tr><tr>
                            <td class="formlabel">Tag</td><td class="formInput"><input name="tag" class="textBoxSmall" type="text"></td>
                        </tr><tr>
                            <td class="formlabel">Type</td><td class="formInput"><select name="type"  class="selectBoxSmall">
                                    <option value="1" selected="selected">Section</option>
                                   <!-- <option value="2">Email</option>-->
                                   <!-- <option value="3">Widget</option>-->
                                </select></td>
                        </tr><tr>
                            <td class="formlabel">Public Url</td><td class="formInput"><input name="url" class="textBoxSmall" type="text"></td>
                        </tr><tr>
                            <td colspan="2" class="formsubmit">
                               <!-- <input type="submit" value="Ok">-->
                            </td>
                        </tr>
                    </table>
            </div>
            <div class="formsubmit">
                <input type="button" value="SEND" class="btSendSmall" onclick="javascript:validateFormAll('addSection')">
            </div>
            <div style="clear:both">

            </div>
                </form>
</div>
                <div id="service">
                <?php
                if($curSection)
                {
                    $sectionType=$editor->getCurrentSectionType();
                    $section=$editor->getCurrentSection();
                    ?>
                    <div class="contentForm">
                    <div class="headerType">Edit</div>

                    <form method="POST" action="?action=editSection&<?php echo $linkTarget;?>">
                        <div style="float:left">
                        <table>
                            <tr>
                                <td class="formlabel">Name</td><td class="formInput"><input name="name" class="textBoxSmall" type="text" value="<?php echo $curSection->data["NAME"];?>"></td>
                            </tr><tr>
                                <td class="formlabel">Public Url</td><td class="formInput"><input name="url" class="textBoxSmall" type="text" value="<?php echo $curSection->data["path"];?>"></td>
                            </tr><tr>
                                <!--<td colspan="2" class="formsubmit"><input type="submit" value="Modificar"></td>-->
                                <td class="formsubmit"></td>
                            </tr><tr>
                                <td class="formlabel">Tag: <?php echo $curSection->data["tag"];?></td>

                            </tr>
                        </table>
                        </div>
                        <div class="formsubmit">
                            <input type="submit" value="MODIFY" class="btSend">
                        </div>
                        <div style="clear:both"></div>
                    </form>
                        </div>

                </div><!-- end service-->
            <?php
                    if($editor->getCurrentSectionType()=="section")
               include_once(CUSTOMPATH . "/pages/Editor/views/section_files.php");
            ?>
            </div><!--Container Derecha2-->





    <?php }
    }
include_once(CUSTOMPATH."/pages/Editor/views/footer.php");
    ?>
