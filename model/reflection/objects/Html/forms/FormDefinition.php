<?php
namespace model\reflection\Html\forms;
include_once(PROJECTPATH."/model/reflection/objects/base/ConfiguredObject.php");
class FormDefinition extends \model\reflection\base\ConfiguredObject //ClassFileGenerator
{
    function __construct($name,$parentAction)
    {                
        $parentModel=$parentAction->parentModel;
        $this->name=$name;
        $this->action=$parentAction;
        
        parent::__construct($name,
                            $parentModel,
                            "\\html\\forms",
                            "/html/forms",
                            'forms',
                            "\\lib\\output\\html\\Form",
                            null);
    }

    function initialize($def=null)
    {
        parent::initialize($def);
        if($this->action)
            $this->action->addForm($this);
        $this->widget=new FormWidget($this->className,$this);
    }
    
    function create()
    {
        $modelDef=$this->parentModel;
        $objName=$modelDef->objectName->getNormalizedName();
        $name=$this->className;
        $actionDef=$this->action;
        $def=array();
        $def["NAME"]=$name;
        $def["MODEL"]=$objName;
        if($this->action) {
            $def["ACTION"] = array("MODEL" => $modelDef->objectName->getNamespaced("objects"),
                "ACTION" => $name, "INHERIT" => true);

            $def["ROLE"] = $actionDef->getRole();
        }
        $def["REDIRECT"]=array("ON_SUCCESS"=>"",
                               "ON_ERROR"=>"");
        $def["INPUTS"]=array();
        $relationName=$actionDef->getTargetRelation();
        if($relationName!="")
            $def["TARGET_RELATION"]=$relationName;

        if($this->action) {
            $indexes = $actionDef->getIndexFields();
            if ($indexes) {
                $def["INDEXFIELDS"] = $indexes;
            }
        }
        
        $fields=$actionDef->getFields();
        if($fields)
        {            
            
            $srcModel=$this->parentModel;
            
            $fromPrivateObject=$srcModel->objectName->isPrivate();
            
            foreach($fields as $key=>$value)
            {         
                // El parentModel del field actual es la Action, no es una modeldefinition.

                $targetDef=$value->getRawDefinition();
                // Esta presuponiendo que el modelo es siempre el modelo padre de la accion, y no otro.
                $origField=$value->parentModel->parentModel->getFieldOrAlias($key);
                
                
                // En caso de que estemos editando un objeto privado, y tenemos una relacion con el objeto publico al que
                // pertenece, se incluye el campo de la relacion como parametro requerido.
                if($fromPrivateObject)
                {
                    if($origField->isRelation())
                    {
                        $targetObject=$origField->getRemoteModel();
                        
                        $role=$origField->getRole();
                        // Se comprueba si la relacion apunta al objeto que es el que define el namespace donde se encuentra este objeto.
                        if($role=="BELONGS_TO" && 
                           $targetObject->objectName->equals($srcModel->objectName->getNamespaceModel()))
                        {
                            $remFields=$origField->getRemoteFieldNames();
                                $def["INDEXFIELDS"][$key]=array("REQUIRED"=>1,"MODEL"=>''.$targetObject->objectName,"FIELD"=>$remFields[0],"MAP_TO"=>$key);
                                continue;
                        }
                    }
                }

                // El parentModel del field actual es la Action, no es una modeldefinition.
                /* $parent=$value->parentModel->parentModel;
                $def["FIELDS"][$key]=array("MODEL"=>$parent->objectName->getNormalizedName(),
                                           "FIELD"=>$targetDef["FIELD"],
                                           "REQUIRED"=>(isset($targetDef["REQUIRED"])?$targetDef["REQUIRED"]:0)
                                           );              
                $targetRelation=$value->getTargetRelation();
                if($targetRelation!="")
                {
                    $def["FIELDS"][$key]["TYPE"]="DataSet";
                    $def["FIELDS"][$key]["TARGET_RELATION"]=$targetRelation;
                }*/
                $params=null;
                if($relationName!="")
                {
                    $field=$this->parentModel->getFieldOrAlias($relationName);
                    $params=$field->getDefaultInputParams($this,$value);
                    // Ojo, los parametros de ponen al campo cuyo nombre es el nomrbe de la relacion multiple
                    if($params)
                        $def["INPUTS"][$relationName]["PARAMS"]=$params;
                }
                else
                {
                    $fieldModel=$targetDef["MODEL"];
                    if($fieldModel)
                    {

                        $fieldParentModel=\model\reflection\ReflectorFactory::getModel($fieldModel);
                        $fieldInstance=$fieldParentModel->getFieldOrAlias($targetDef["FIELD"]);
                        $params=$fieldInstance->getDefaultInputParams($this,$value);
                        if($params)
                            $def["INPUTS"][$key]["PARAMS"]=$params;
                    }                               
                }
                
                        
            }            
        }
        else
        {
            $def["NOFORM"]=true;
        }
        
        $this->initialize($def);
     
    }

    function getFormClass()
    {
        $layer=$this->parentModel->getLayer();
        $objName=$this->parentModel->objectName->getNormalizedName();
        $name=$this->name;
        return '\\'.$layer.'\\'.$objName.'\html\forms\\'.$name;
    }
    function getRole()
    {
        return $this->action->getRole();
    }

    function getFormPath()
    {
        return dirname($this->filePath);
    }

    function getWidgetPath()
    {
        $objName=$this->parentModel->objectName->getNormalizedName();
        $name=$this->name;
        return '/'.$objName.'/html/forms/'.$name;
    }
    
    function getDefinition()
    {
        if( !isset($this->definition["INDEXFIELDS"] ))
        {
            $this->definition["INDEXFIELDS"]=array();
        }
        return $this->definition;
    }
    function saveModelMethods()
    {
        $def=$this->getDefinition();
    
        $this->addProperty(array("NAME"=>"definition",
                                      "ACCESS"=>"static",
                                      "DEFAULT"=>$def
                                      ));
        $this->addMethod(array(
                "NAME"=>"__construct",
                "COMMENT"=>" Constructor for ".$this->name,
                "CODE"=>"\t\t\tparent::__construct(".$this->className."::\$definition,\$actionResult);\n",
                "PARAMS"=>array(
                        "actionResult"=>array(
                            "DEFAULT"=>"null",
                            "COMMENT"=>"\\lib\\action\\ActionResult instance.Errors found while validating this action must be notified to this object"
                    )
            )));
        if(isset($def["FIELDS"])) {
            $this->addMethod(array(
                "NAME" => "validate",
                "COMMENT" => " Callback for validation of form :" . $this->name,
                "PARAMS" => array(
                    "params" => array(
                        "COMMENT" => " Parameters received,as a BaseTypedObject.\nIts fields are:\n" . (isset($def["INDEXES"]) ? "keys: " . implode(",", array_keys($def["INDEXES"])) . "\n" : "") .
                            ($def["FIELDS"] ? "fields: " . implode(",", array_keys($def["FIELDS"])) : "")

                    ),
                    "actionResult" => array("COMMENT" => "\\lib\\action\\ActionResult instance.Errors found while validating this action must be notified to this object"
                    ),
                    "user" => array(
                        "COMMENT" => " User executing this request"
                    )

                ),
                "CODE" => "\n/" . "* Insert the validation code here *" . "/\n\n\t\treturn \$actionResult->isOk();\n"

            ));
        }
            $this->addMethod(array(
                    "NAME"=>"onSuccess",
                    "COMMENT"=>" Callback executed when this form had success.".$this->name,
                    "PARAMS"=>array(
                        "actionResult"=>array(
                            "COMMENT"=>" Action Result object"
                            )
                        ),
                    "CODE"=>"\n/"."* Insert callback code here *"."/\n\nreturn true;\n"
                ));
            $this->addMethod(array(
                    "NAME"=>"onError",
                    "COMMENT"=>" Callback executed when this action had an error".$this->name,
                    "PARAMS"=>array(
                        
                        "actionResult"=>array("COMMENT"=>"\\lib\\action\\ActionResult instance.Errors found while validating this action must be notified to this object"
                            )
                        ),
                    "CODE"=>"\n/"."* Insert callback code here *"."/\n\nreturn true;\n"
                ));        
    }

    function saveDefinition()
    {
        $definition=$this->getDefinition();
           
        $this->addProperty(array("NAME"=>"definition",                                  
                                      "DEFAULT"=>$this->getDefinition()
                                      ));
        $this->saveModelMethods();
        $this->generate();
    }

    function generateCode()
    {
        $this->widget->generateCode();
    }
    function hasForm()
    {
        return !$this->definition["NOFORM"];
    }

    function getAction()
    {
        return $this->action;
    }
    function setWidget($wid)
    {
        $this->widget=$wid;
    }
    function getWidget()
    {
        return $this->widget;
    }
    function getIndexFields()
    {
        if(!isset($this->definition["INDEXFIELDS"]))
            return array();
        return $this->definition["INDEXFIELDS"];
    }
    function getFields()
    {
        $def=$this->getDefinition();
        if($def["ACTION"]["INHERIT"]==1)
            return $this->action->getFields();
        return parent::getFields();
    }
    function getField($key)
    {
        $def=$this->getDefinition();
        if($def["ACTION"]["INHERIT"]==1)
            return $this->action->getField($key);
        return parent::getField($key);
    }
    static function getMetaData($objectName,$dsName)
    {
        include_once(__DIR__."/FormMetadata.php");
        return new \model\reflection\Html\forms\FormMetadata($objectName,$dsName);
    }
    static function getModelForms($className)
    {
        $objectName=new \model\reflection\Model\ModelName($className);
        $model=\model\reflection\ReflectorFactory::getModel($className);
        $forms=$objectName->getForms();
        $result=array();
        foreach($forms as $key=>$value)
        {
            $part=pathinfo($value, PATHINFO_FILENAME);

            $f=$objectName->getFormFileName($part);
            include_once($f);
            $class=$objectName->getNamespacedForm($f);
            $formDef=$class::$definition;
            $action=null;
            if(isset($formDef["ACTION"]))
            {
                $action=new \model\reflection\Action($formDef["ACTION"],$model);
            }
            $result[$part]=new FormDefinition($part,$action);
        }
        return $result;
    }
    
}
