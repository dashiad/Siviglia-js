<?php
namespace lib\reflection\html\forms;
class FormWidget extends \lib\reflection\base\ConfiguredObject //ClassFileGenerator
{
    function __construct($name,$parentForm)
    {        
        $parentAction=$parentForm->getAction();
        $parentModel=$parentAction->parentModel;
        $this->action=$parentAction;
        $this->form=$parentForm;
        
        parent::__construct($name,
                            $parentModel,
                            $parentModel->objectName->getNamespace()."\\html\\forms",
                            "/html/forms",
                            'formWidgets',
                            "\\lib\\output\\html\\Form",
                            null,".wid");
    }


    function generateCode()
    {
        if(!$this->mustRebuild())
            return;
        $def=$this->form->getDefinition();
        if( isset($def["NOFORM"] ) && !$def["NOFORM"])
            return;
        
        
        $phpCode = <<<'TEMPLATE'
            global $SERIALIZERS;
            $formKeys={%formKeys%};
            $serializer=\lib\storage\StorageFactory::getSerializerByName('{%layer%}');
            $serializer->useDataSpace($SERIALIZERS["{%layer%}"]["ADDRESS"]["database"]["NAME"]);
TEMPLATE;
        $phpCode="<"."?php\n".$phpCode."\n?>\n\n";

        $formCode=<<<'TEMPLATE'
                <?php $styleClass="";$inputParams="";$form=null; ?>
                [*/FORMS/form({"object":"{%objectName%}","layer":"{%layer%}","name":"{%actionName%}","form":"&$form"})]        
                [_TITLE]{%title%}[#]
                [_DESCRIPTION]Form Description[#]
                [_MODEL({"keys":"$formKeys","serializer":"$serializer","model":"&$currentModel"})][#]                           
                [_FORMGROUP]
                        [_TITLE]Form Group Title[#]
                        [_DESCRIPTION]Form Group Description[#]
                        [_FORMERRORS]
{%formerrors%}
                        [#]
                        [_FIELDS]
{%inputs%}         
                        [#]
                [#]
                [_BUTTONS]
                        [*/INPUTS/Submit][_LABEL][@L]Aceptar[#][#][#]
                [#]        
          [#]
TEMPLATE;
             
        $this->fillActionErrors($actionErrors);
        
        if(is_array($actionErrors))
        {
            
            foreach($actionErrors as $key2=>$value2)
            {
              $formErrors.="\t\t\t\t[_ERROR({\"type\":\"".$key2."\",\"code\":\"".$value2."\"})][@L]".$this->parentModel->objectName->getNormalizedName()."_".$this->action->name."_".$key2."[#][#]\n";
            }
            
        }
      // [*/types/inputs/Relation1x1Input({"name":"a3","labelField":"c2","valueField":"c1"})][#]
      
      $actionRole=$this->action->getRole();
      switch($actionRole)
      {
      case "Add":
          {
              $keys=null;
          }break;
      case "Edit":
          {
              $keys=$this->action->getIndexFields();
          }break;
      case "AddRelation":
          {
              $keys=$this->action->getIndexFields();
              $defaultInput="AddRelationMxN";                           
          }break;
      case "SetRelation":
          {
              $keys=$this->action->getIndexFields();              
          }break;
      case "DeleteRelation":
          {
              $keys=$this->action->getIndexFields();
              $defaultInput="DeleteRelationMxN";
          }
      default:
          {
              echo "No es posible generar codigo para formularios basados en acciones de tipo ".$actionRole;
              return;
          }
      }
      if($keys)
      {
          foreach($keys as $kkey=>$kvalue)
              $keyCads[]='"'.$kkey.'"=>Registry::$registry["params"]["'.$kkey.'"]';
          $keyExpr="array(".implode(",",$keyCads).");";
      }
      else
          $keyExpr="null;";

      $modelCache=array();
      if($def["ACTION"]["INHERIT"])
      {
          $actDef=$this->action->getDefinition();
          $formFields=$actDef["FIELDS"];
      }
      else
      {
          $formFields=$def["FIELDS"];
      }
      $inputsExpr="";
          foreach($formFields as $key=>$value)
          {
              if(isset($def["INDEXFIELDS"][$key]))
                      continue;
              // Aqui, se esta tomando TARGET_RELATION a nivel de campo, no a nivel de form.
              if(isset($value["TARGET_RELATION"]))
              {
                  $targetRel=$value["TARGET_RELATION"];
                  $relationField=$this->action->parentModel->getFieldOrAlias($targetRel);
                  if(isset($formFields[$targetRel]))
                      $fDef=$formFields[$targetRel];
                  else
                      $fDef=array();
                  $inputsExpr.=$relationField->getFormInput($this->form,$targetRel,$fDef,$def["INPUTS"][$targetRel]);
              }
              else
              {
                  // Hay que tener en cuenta, que FIELD puede ser del tipo "a/b/c" , por lo que el campo devuelto no
                  // tiene por que ser ni del mismo modelo.
                  $curField=$this->parentModel->getFieldOrAlias($value["FIELD"]);

                  $inputsExpr.=$curField->getFormInput($this->form,$key,$value,$this->definition["INPUTS"][$key]);
              }
          }
      

      $searchs = array("{%formKeys%}","{%layer%}","{%objectName%}","{%actionName%}","{%inputs%}","{%title%}");
      $replaces = array($keyExpr,
                          $this->action->parentModel->objectName->layer,
                          str_replace('\\','/',$this->action->parentModel->objectName->getNormalizedName()),
                          $this->action->name,
                          $inputsExpr,
                          $this->action->name." ".$this->parentModel->objectName->getNormalizedName()
                       );
      
      $formWidget=$phpCode."\n".$formCode."\n";
      
      $formWidget=str_replace($searchs,$replaces,$formWidget);      
      file_put_contents($this->filePath,$formWidget);
    }

    function fillBaseErrors($typeDef,& $errors)
    {
        
        // Al final, de BaseTypeException solo quiero unset
            if( $typeDef["REQUIRED"] )
                $errors["UNSET"]=1;
            $errors["INVALID"]=2;
    }
    function fillActionErrors(& $errors)
    {
        $layer=$this->parentModel->objectName->layer;
        $objname=$this->parentModel->objectName->getNormalizedName();
        $destPath=$this->parentModel->objectName->getActionFileName($this->action->getName());
        if(!is_file($destPath))
                return;
        include_once($destPath);        
        $exceptionClass=$this->parentModel->objectName->getNamespacedActionException($this->action->getName());
        
        if( !class_exists($exceptionClass) )
            return;
        
        
        $reflectionClass=new \ReflectionClass($exceptionClass);

        // Se obtienen las constantes
        $constants = $reflectionClass->getConstants ();
        foreach($constants as $key=>$value)
        {        

         if( strpos($key,"ERR_")===0 )
            {
                $key=substr($key,4);
            }
            $errors[$key]=$value;
        }
    }  
}
