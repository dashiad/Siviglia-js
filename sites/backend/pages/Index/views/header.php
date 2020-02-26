<?php
global $editor;
$sites=$editor->getEditableSites();
$curSite=$editor->getCurrentSiteId();
$website=$editor->getCurrentSite();
$curSection=$editor->getCurrentSection();
$curSubWidget=$editor->getCurrentSubWidget();

if($curSite)
    $sections=$editor->getCurrentSite()->getSections();

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMS Percentil</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="style.css">
<?php
if($curSite){
?>
    <link rel="stylesheet" href="js/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="js/codemirror/theme/siviglia.css">
    <link rel="stylesheet" href="js/codemirror/addon/fold/foldgutter.css" />
    <script src="/js/jquery-1.9.1.js"></script>
    <script src="js/zclip/jquery.zclip.js"></script>
    <script src="js/codemirror/lib/codemirror.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/codemirror/mode/siviglia/siviglia.js"></script>
    <script src="js/codemirror/addon/fold/foldcode.js"></script>
    <script src="js/codemirror/addon/fold/foldgutter.js"></script>
    <script src="js/codemirror/addon/fold/brace-fold.js"></script>
    <script src="js/codemirror/addon/fold/siviglia-fold.js"></script>
    <script type="text/javascript">
<?php
    if($curSection){
?>
        function doPreview()
        {
            window.open('http://<?php echo $_SERVER["HTTP_HOST"]."/".$curSection->data["path"]?>?mode=__DEVELOP__&__SITE__=<?php echo $website->data["id_website"];?>','Preview');
        }
        function doPreviewEmail(tag)
        {
            //window.open("<?php echo 'index.php?site='.$website->data["id_website"].'&preview=1&templateOut='; ?>" + tag + "_work");
            // $("#miPreviewEmail").load("<?php echo 'index.php?site='.$website->data["id_website"].'&preview=1&templateOut='; ?>" + tag + "_work");

        }
<?php
    }
?>


        var showOptions={'blank':'block','dirty':'block','normal':'block','nott':'block'};
        function toggleMe(node)
        {
            var value=node.value;

            for(var k in showOptions)
            {

                    if(value==k || value=="all")
                        $(".translation_"+k).show();
                    else
                    {
                        if(value!="all")
                            $(".translation_"+k).hide();
                    }

            }

        }

        function toggleRealm(){
            $(".translations").addClass('no_visible');
            $('input[name=realm]').each(function( index ) {
                if ($(this).is(':checked')){
                    $(".translations."+$(this).val()).removeClass('no_visible');
                }
            });
        }

        $(document).ready(function(){
            toggleRealm();
            $("#btncopiar_zclip").zclip({
                path: "js/zclip/ZeroClipboard.swf",
                copy: function(){
                    return $("#texto_zclip").val();
                }
            });

            $('#search').keyup(function() {
            //$('a#searchAction').click(function(){
                var search = $(this).val();
                //var search = $('#search').val().toLowerCase();
                $('.translations').removeClass('hide');

                if(search.length>0) {
                    //var starTime = (new Date()).getTime();

                    //$('div.translations span.textTranslation').not(":contains('"+search+"')").parents('.translations').addClass('hide');
                    //$('div.translations:not([data-value*='+search+'])').addClass('hide');
                    var notMatch = $('div.translations').filter(function() {
                        return $(this).attr('data-value').toLowerCase().indexOf(search) == -1;
                    });
                    notMatch.addClass('hide');

                    //var endTime = (new Date()).getTime();
                    //var resultTime = endTime - starTime;
                    //console.debug('fin: ' + resultTime);
                }

            });


            var myTextArea=document.getElementById("elm1");
            myCodeMirror =CodeMirror.fromTextArea(myTextArea,
                {mode:'siviglia',
                    theme:'siviglia',
                    foldGutter: true,
                    lineNumbers: true,
                    lineWrapping: true,
                    maxHighlightLength:100000,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
                }
            );

        });



    </script>
<?php
}
?>
</head>

<body>
    <div id="header-wrap">
        <header class="group">
            <?php
            if (PROJECT_ENVIRONMENT == "kirondo") {?>
            <div style="float:left;width:100px"><a href="index.php" title="CMS Kirondo"><img src="images/kirondo.png" height="30"></a></div>
            <?php
            }
            else {
            ?>
            <div style="float:left;width:100px"><a href="index.php" title="CMS Percentil"><img src="images/logo1.png" height="30"></a></div>
            <?php
            }
            ?>
<?php
for($k=0;$k<$sites->count;$k++)
{
    echo '<h6><a ';
    if($sites[$k]["id_website"]==$curSite)
    echo ' class="selected" ';
    echo 'href="index.php?site='.$sites[$k]["id_website"].'">'.$sites[$k]["websiteName"].'</a></h6>';
}
?>
       </header>
    </div>
<?php
if($curSite){
?>
    <div id="container">
            <!--Container Izq-->
            <div id="service" class="menuLateral">
                <form method="POST" name="formDeleteSection" id="formDeleteSection" action="?action=deleteSection&site=<?php echo $curSite; ?>">
                    <input type="hidden" name="section_id" value="">
                </form>
<?php
    $sectionTypes=array("Section","Widget");
    //$sectionTypes=array("Section","Widget");
    foreach($sectionTypes as $curType)
    {
        if(isset($sections[$curType])) {
            echo '<div class="sectionType"><div class="headerType">'.$curType.'</div>';
            $value=$sections[$curType];
            for($j=0;$j<count($value);$j++)
            {
                $addedClass="";

                if($value[$j]["id_section"]==$curSection->id)
                    $addedClass="sectionSelected";

                echo '<div class="sectionLink '.$addedClass.'">';
                if ($curType=="Section"){
                    echo '<div class="deleteSection" onmouseout="this.style.width=2" onmouseover="this.style.width=15"><a href="javascript:deleteSection('.$value[$j]["id_section"].',\''.$value[$j]["NAME"].'\')">&nbsp;&nbsp;&nbsp;</a></div>';
                }
                echo '<a href="?site='.$curSite.'&'.strtolower($curType).'='.$value[$j]["id_section"].'" class="link1">'.$value[$j]["NAME"]."</a></div>";

            }
            echo "</br></div>";
        }
    }
?>
                <div class="sectionType"><div class="headerType">Traslation</div>
                <div class="sectionLink "><span style="color:black">Traducciones (NO DISPONIBLE TEMPORALMENTE)</span></div>
<!--
20151023 ESTEBAN
TEMPORALMENTE QUITAMOS EL ACCESO AL EDITOR DE TRADUCCIONES DEBIDO A QUE HEMOS DETECTADO QUE FALLA Y ESTABLECE A NULL LOS VALORES DE LITERALES 
                <div class="sectionLink "><a href="?site=<?php echo $curSite;?>&template=EditLang" class="link1">Traducciones</a></div>
-->
                <br><br><br><br>
            </div><!--Container Izq-->
        </div>
<?php
 }
?>


