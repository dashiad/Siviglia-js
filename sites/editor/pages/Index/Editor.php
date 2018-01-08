<?php

    class Editor
    {

        var $user;
        var $website=null;
        var $currentSiteId;
        var $actionError;
        function __construct($currentUser)
        {



            $this->user=$currentUser;

            include_once(LIBPATH."/output/html/templating/TemplateParser2.php");

            $path=array(
                PROJECTPATH."../custom/widgets",
                PROJECTPATH.'/html/Website',
                PROJECTPATH.DEFAULT_NAMESPACE.'/objects',
                PROJECTPATH.'/output/html/Widgets'
            );

            if($_GET["site"])
            {
                $site=intval($_GET["site"]);

                $this->website=new Website($site);

                if(!$this->website->canBeEdited($this->user))
                {
                    $this->currentSiteId=null;
                }
                else
                {
                    $this->currentSiteId=$site;
                }
                // Si tenemos site, se añade a la lista de path de busqueda de widgets
                array_unshift($path,PROJECTPATH."../custom/widgets/".$this->website->getLocalPrefix());
            }

            \CLayoutManager::setDefaultWidgetPath($path);

            if($_GET["section"] && !empty($_GET["section"]))
            {
                $section=intval($_GET["section"]);
                include_once(CUSTOMPATH."/objects/Section/SectionFactory.php");
                $this->currentSection=SectionFactory::getInstance()->getSection($section);
            }
            if($_GET["email"])
            {
                $email=mysql_real_escape_string($_GET["email"]);
                include_once(CUSTOMPATH."/objects/Section/EmailFactory.php");
                $this->currentEmail=EmailFactory::getInstance()->getEmail($site,$email);
                $this->currentEmail->data["id_type"]=2;
            }
            if($_GET["widget"] && empty($_GET["section"]))
            {
                $widget=mysql_real_escape_string($_GET["widget"]);
                include_once(CUSTOMPATH."/objects/Section/WidgetFactory.php");
                $this->currentWidget=WidgetFactory::getInstance()->getWidget($site,$widget);
                $this->currentWidget->data["id_type"]=3;
            }


            global $editor;
            $editor=$this;
        }

        function getCurrentSectionType()
        {

          if($this->currentSection)
              return "section";
          if($this->currentWidget)
              return "widget";
          if($this->currentEmail)
              return "email";

        }
        function getCurrentSiteId()
        {
          return $this->currentSiteId;
        }
        function getCurrentSite()
        {
          return $this->website;
        }
        function getCurrentSection()
        {
            if($this->currentSection)
                return $this->currentSection;
            if($this->currentWidget)
                return $this->currentWidget;
            if($this->currentEmail)
                return $this->currentEmail;
        }
        function getCurrentSubWidget()
        {
            return  isset($_GET["section"]) && isset($_GET["widget"])?$_GET["widget"]:null;
        }
        function getCurrentWidget()
        {
            return  isset($_GET["widget"])?$_GET["widget"]:null;
        }
        function route()
        {

$b = 11;
          if(isset($_GET["action"]))
          {
              switch($_GET["action"])
              {
                  case 'editSection':
                  {
                      $this->currentSection->edit($_POST["name"],$_POST["url"],$this->website->getLocalPrefix());
                  }break;
                  case 'addSection':
                  {
                      $fields=array("name","tag","type","url");
                      $required=array(1,1,1,1);
                      $labels=array("Name","Tag","Type","Public url");
                      $this->actionError=$this->checkForm($fields,$required,$labels);

                      if($this->actionError)
                          $this->actionError["FORM"]="addSection";
                      else
                      {
                          include_once(CUSTOMPATH."/objects/Section/SectionFactory.php");
                          $namespace=$this->website->getLocalPrefix();
                          $result=SectionFactory::getInstance()->addSection($this->currentSiteId,$_POST,$namespace);
                          if(is_array($result))
                          {
                              $this->actionError=$result;
                              $this->actionError["FORM"]="addSection";
                          }
                          else
                          {
                              include_once(CUSTOMPATH."/lib/Router.php");
                              $router = new Router($namespace);
                             // $router->refreshRouteFile($_POST['tag'], $_POST['url']);
                              $router->refreshCache();
                              header("Location: index.php?site=".$this->currentSiteId."&section=".$result->id."&ex=created");
                              exit();
                          }
                      }

                  }break;
                  case 'deleteSection':
                  {
                      include_once(CUSTOMPATH."/objects/Section/SectionFactory.php");
                      $namespace=$this->website->getLocalPrefix();
                      SectionFactory::getInstance()->deleteSection($this->currentSiteId,$_POST,$namespace);
                      include_once(CUSTOMPATH."/lib/Router.php");
                      $router = new Router($namespace);
                      $router->refreshCache();
                      header("Location: index.php?site=".$this->currentSiteId."&ex=deleted");
                      exit();
                  }break;
                  case 'saveLayout':
                  {
                      if($this->currentSection)
                      {
                          $this->currentSubWidget=$_POST["widget"];
                          $this->currentSection->saveLayout($_POST["layout"],$_POST["widget"]);
                      }

                      if($this->currentWidget)
                          $this->currentWidget->saveLayout($_POST["layout"]);
                      if($this->currentEmail)
                          $this->currentEmail->saveLayout($_POST["layout"]);
                  }break;
                  case 'addwidget':
                      {
                           $this->currentSection->getEditableLayout($_POST["widgetName"]);
                           header("Location: index.php?site=".$_GET["site"]."&section=".$_GET["section"]."&widget=".$_POST["widgetName"]);
                          exit();
                      }break;
                  case 'editWidget':
                  {
                      $this->currentWidget->edit($_POST["name"],$_POST["url"]);
                  }break;
                  case 'deleteSubWidget':
                  {
                      $this->currentSection->deleteSubWidget($_GET["name"]);
                      header("Location: index.php?site=".$_GET["site"]."&section=".$_GET["section"]);
                      exit();
                  }break;
                  case 'editEmail':
                  {
                      $this->currentEmail->edit($_POST["name"],$_POST["url"]);
                  }break;
                  case 'saveWidget':
                  {
                      $this->currentWidget->saveLayout($_POST["layout"]);
                  }break;
                  case 'saveEmail':
                  {
                      $this->currentEmail->saveLayout($_POST["layout"]);
                  }break;
                  case 'saveTranslation':
                  {
                      include_once(PROJECTPATH."/lib/output/html/templating/html/plugins/T.php");
                      T::saveTranslations($this->website->getLocalPrefix(),$_POST["translation"]);
                  }break;
                  case 'deleteFile':
                  {
                      $this->currentSection->deleteFile($_POST["id_resource"]);
                  }break;
                  case 'addFile':
                  {
                      if($_FILES['file']['error'])
                      {
                          $this->actionError=array("MESSAGE"=>"Error al subir fichero");
                          $this->actionError["FORM"]="addSection";
                      }
                      else
                      {
                        $temppath = $_FILES['file']['tmp_name'];
                        $name = $_FILES['file']['name'];
                        $this->currentSection->addFile ($temppath, $name);
                      }
                  }break;
                  case 'acceptLayout':
                  {
                      $section=$this->getCurrentSection();
                      $section->acceptChanges($this->getCurrentSubWidget());

                      //$this->currentSection->acceptChanges();
                  }break;
                  case 'recoverLayout':
                  {
                      $section=$this->getCurrentSection();
                      $section->discardChanges($this->getCurrentSubWidget());

                      //$this->currentSection->acceptChanges();
                  }break;
              }
              if(!$this->actionError)
              {
                  $qs="";
                  if($this->currentSiteId)
                  {
                      $qs="site=".$this->currentSiteId;
                      if($this->currentSection)
                          $qs.="&section=".$this->currentSection->id;
                      if($this->currentWidget)
                      {
                          //$qs.="&widget=".$this->getCurrentSubWidget();
                          $qs.="&widget=".$this->getCurrentWidget();
                      }
                      if($this->currentEmail)
                          $qs.="&email=".$this->currentEmail->id;
                      if($this->currentSubWidget)
                          $qs.="&widget=".$this->currentSubWidget;
                  }
                  header("Location: index.php?".$qs);
              } else {

                  echo "<b>".$this->actionError["FORM"].":".$this->actionError["ERROR"]."</b>";
              }
          }
          if(isset($_GET["template"]))
          {
              $this->renderTemplate(CUSTOMPATH."/pages/Editor/views/".$_GET["template"].".html");
          }
          if(isset($_GET["templateOut"]))
          {
                include_once(CUSTOMPATH."/pages/Editor/views/emails.php");
          }   else {
            if(isset($_GET["view"]))
                $view=$_GET["view"];
              else
                  $view="section";
                    include_once(CUSTOMPATH."/pages/Editor/views/".$view.".php");
          }

        }
        function getEditableSites()
        {
            return Website::getEditableSites($this->user);
        }
        function checkForm($fields,$required,$labels)
        {
          for($k=0;$k<count($fields);$k++)
          {
              if($required[$k] && !isset($_POST[$fields[$k]]))
              {
                  return array("ERROR"=>"Field ".$labels[$k]." is required.");
              }
          }
          return null;
        }
        function renderTemplate($template)
        {

            include_once(CUSTOMPATH."../backoffice/lib/output/html/templating/TemplateParser2.php");
            include_once(CUSTOMPATH."../backoffice/lib/output/html/templating/TemplateHTMLParser.php");

            $oLParser=new CLayoutHTMLParserManager();

            $widgetPath=array(CUSTOMPATH."widgets/"._LOCAL_PREFIX_,
                CUSTOMPATH."widgets/percentil",
                CUSTOMPATH."widgets/"
            );

/*
            $pluginPath = array("L"=>array("lang"=>_LOCAL_DEFAULT_ISO_,"LANGPATH"=>CUSTOMPATH."/lib/templating/lang/"),
                "CSS"=>array("CSSPATH"=>CUSTOMPATH."html/"._CURRENTPAGE_WEBPATH_),
                "SCRIPT"=>array("SCRIPTPATH"=>CUSTOMPATH."html/"._CURRENTPAGE_WEBPATH_)
            );
*/

$b = 11;
            global $currentPage;
            $pluginPath = array("L"=>array("lang"=>_LOCAL_DEFAULT_ISO_,"LANGPATH"=>CUSTOMPATH."/lib/templating/lang/"),
                "CSS"=>array("CSSPATH"=>CUSTOMPATH."html/".$currentPage->currentPageWebPath),
                "SCRIPT"=>array("SCRIPTPATH"=>CUSTOMPATH."html/".$currentPage->currentPageWebPath)
            );

            $oManager=new CLayoutManager(CUSTOMPATH."..","html",$widgetPath,$pluginPath);

            $definition=array("TEMPLATE"=>$template);

            $oManager->renderLayout($definition,$oLParser,true);
        }
    }
?>