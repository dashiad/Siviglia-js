<?php

$files=$curSection->getFiles();
$sourceDir=$curSection->getSectionResourcesPath();
?>
<!--RIGHT DIV TABLE -->
<script>
    function askForName()
    {
        var widName=window.prompt("Nombre del nuevo widget");
        document.getElementById("newWidgetName").value=widName;
        document.getElementById("newWidgetForm").submit();

    }
    function deleteWidget(name)
    {
        if(window.confirm("Seguro que quieres eliminar el widget "+name))
        {
            document.location.href='index.php?action=deleteSubWidget&site=<?php echo $curSite;?>&section=<?php echo $curSection->id;?>&name='+name;
        }
    }
</script>
<div class="SectionFileContainer">
    <div class="headerType">
        SubWidgets
        <div style="float:right">
            <form method="POST" action="?action=addwidget&site=<?php echo $curSite;?>&section=<?php echo $curSection->id;?>" id="newWidgetForm">
                <input type="hidden" name="widgetName" id="newWidgetName">
            </form>
            <input type="button" onclick="askForName()" value="Nuevo">
        </div>
    </div>
    <div>
        <?php
            $subWidgets=$curSection->getSubWidgets();
            for($i=0;$i<count($subWidgets);$i++)
            {
                $s=$subWidgets[$i];?>
                <div style="float:left;padding:3px;border:1px solid black">
                    <div class="deleteSection" onmouseout="this.style.width=2" onmouseover="this.style.width=15"><a href="javascript:deleteWidget('<?php echo $subWidgets[$i];?>')">&nbsp;&nbsp;&nbsp;</a></div>
                    <a href="?site=<?php echo $curSite;?>&section=<?php echo  $curSection->id;?>&widget=<?php echo $subWidgets[$i];?>" class="link1">
                        <?php echo $subWidgets[$i];?></a>
                </div>
            <?php }

        ?>
        <div style="clear:both;margin-bottom:5px"></div>
    </div>
</div>
<div class="SectionFileContainer">
    <div class="headerType">Files</div>
    <?php
    for ($i = 0; $i < $files->count; $i++){
        $curFile=$files[$i];
        list($width, $height, $type, $attr) =@getimagesize($sourceDir."/".basename($files[$i]["path"]));
        ?>
        <div class="fileContainer">
            <div style="position:relative">
                <div style="position:absolute;top:0px;right:0px">
                    <form method="POST" action="?action=deleteFile&site=<?php echo $curSite;?>&section=<?php echo $curSection->id;?>">
                        <input type="hidden" name="id_resource" value="<?php echo $curFile["id_resource"];?>">
                        <div class="formInput">
                            <input type="submit" style="width:16px;height:16px;background-image:url('/Edit/images/icons/delete16.png');cursor:pointer" value="">
                        </div>
                        <input type="hidden" id="texto_zclip" value= "<?php echo $curFile["path"];?>" style="border:0px; font-size:12px; width:150px; color:#888;" />
                    </form>
                </div>
                <div style="position:absolute;bottom:0px;right:0px"><input type="button"  value="Copy" id="btncopiar_zclip"></div>
            <?php if($type){?>
                <img src="<?php echo $curFile["path"];?>" width="<?php echo min(array(75,$width));?>px" title="<?php echo $curFile["path"]?>" onmouseover="this.nextSibling.style.display='block';"
                    onmouseout="this.nextSibling.style.display='none';"><img src="<?php echo $curFile["path"];?>" width="<?php $cMin=min(array(400,$width)); echo $cMin;?>px" title="<?php echo $curFile["path"]?>"
                                                                             style="position:absolute;z-index:2000;left:-<?php echo $cMin;?>px;top:0px;display:none">
            <?php } else { ?>
                <div style="width:75px;height:75px;background-image:url('/Edit/images/icons/resource.png');"></div>
            <?php } ?>
            </div>
        </div>

    <?php
        if($i>0 && $i%5==0)
            echo '<div style="clear:both"></div>';
    } ?>
    <div style="clear:both"></div>
    <br>
    <br>
    <br>

    <div class="contentForm">
        <div class="headerType">New Files</div>
        <!-- FORMULARIO PARA SUBIR UN FICHERO-->
        <form enctype="multipart/form-data" method="post" action="?action=addFile&site=<?php echo $curSite."&".$sectionType;?>=<?php echo $curSection->id;?>">
            <table width="100%">
                <tr>
                    <td width="50%"><input id="file" name="file" type="file" class="btInfoFile" /></td>
                    <td width="50%"><input id="enviar" name="addFile" type="submit" value="Add File" class="btInfo"  /></td>
                </tr>
            </table>

            <input type="hidden" name="section" value="<?php echo $currentSection?>">
        </form>

    </div>
</div>

