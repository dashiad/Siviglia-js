<?php
    include_once('control/config.php');
	include_once('control/startup.php');
	include_once('objects/section/section.php');
	include 'objects\section\objects\file\file.php';
	include 'objects\section\objects\section_lang\section_lang.php';
    include_once(CUSTOMPATH."../backoffice/lib/output/html/templating/TemplateParser2.php");
    include_once(CUSTOMPATH."../backoffice/lib/output/html/templating/TemplateHTMLParser.php");
	$id=intval($_GET["id_section"]);


	$section=new Section($id);
	$section->load();
	$data=$section->getData();
	$path=$section->getLayoutPath();

	$oLParser=new CLayoutHTMLParserManager();
    $widgetPath=array(dirname(__FILE__)."/widgets/");
    $oManager=new CLayoutManager(CUSTOMPATH."..","html",$widgetPath,array("L"=>array("lang"=>"en","LANGPATH"=>CUSTOMPATH."/lib/templating/lang/")));
    $definition=array("TEMPLATE"=>$path);  
    $oManager->renderLayout($definition,$oLParser,true);  
	