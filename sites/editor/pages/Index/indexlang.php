<?php

	include_once('control/config.php');
	include_once('control/startup.php');
	include_once('objects/section/section.php');
	include 'objects\section\objects\file\file.php';
	include 'objects\section\objects\section_lang\section_lang.php';

if($_GET["lang"])
{
    setcookie("lang",$_GET["lang"]);
    $lang=$_GET["lang"];
}
else
{
    $lang=$_COOKIE["lang"];
}

    if(!$lang)
        die("Necesitamos un lenguaje");

	if(isset($_POST["action"])){
		
		switch($_POST["action"]){

			case "addNewSection":{
				
				$name = $_POST["newSectionName"];
				$type = $_POST["newSectionType"];
				$langn = $_POST["lang"];
				$newSection = new Section();
				$newSection -> addSection($name, $langn, $type);
			}
			break;

			case "deleteFile":{
				$delete = new Files();
				$delete -> removeFile($_POST["path"], $_POST["currentSection"]);
			}
			break;

			case "addFile":{

				$temppath = $_FILES['file']['tmp_name'];
				$idsection = $_POST["section"];
				$name = $_FILES['file']['name'];

				$add = new Files();
				$add -> addFile ($temppath, $name, $idsection);
			}
			break;

			case "saveSectionLang":{

				$new = $_POST['elm1'];
				$path = $_POST["path"];
				$section = $_POST["section"];
				$name = $_POST["name"];

				$modify = new SectionLang();
				$modify -> modifiedSectionLang ($path, $new, $section, $name);
				
			}
			break;

			case "preSectionLang":{

				$path = $_POST["path"];

			}
			break;
		}
	}

	$sectionList=Section::getSectionsbyLang($lang);
	$sectionsByType=array();

	foreach($sectionList as $key=>$value)
		$sectionsByType[$value["Type"]][]=$value;

	if (!$_GET["id"]){
		$currentSection = $sectionsByType["Seccion"][0]['id_section'];

	}
	else{
		$currentSection= $_GET["id"];
	}	
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CMS Percentil</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="style.css">

</head>

<body>

<div id="header-wrap">
    <header class="group">

        <h3><a href="index.php" title="CMS Percentil">CMS Percentil</a></h3>
        <h6><a href="indexlang.php?lang=3" title="French"></a></h6>
        <h5><a href="indexlang.php?lang=2" title="German"></a></h5>
        <h4><a href="indexlang.php?lang=1" title="Spanish"></a></h4>

        <nav class="group">
            <ul>
                <li class="home"><a href="index.php" title="Home"></a></li>
                <li class="menu"><a href="pagina2.php" title=" Secciones"></a></li>
                <li class="menu"><a href="pagina2.php" title=" Traducciones"></a></li>
            </ul>
        </nav>
    </header>
</div><<!-- end header wrap -->


</body>
</html>
