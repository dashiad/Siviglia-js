<?php
namespace lib\output\html;
include_once(LIBPATH."/model/types/BaseType.php");
class Form extends \lib\model\BaseTypedObject
{
    var $actionResult;
    var $formDefinition;
    var $srcModelInstance=null;
    var $srcModelKeys=null;
    var $srcModelName=null;
    
    function __construct($definition,& $actionResult)
    {
        Form::getFormDefinition($definition);
        $this->formDefinition=$definition;
        $this->srcModelName=$definition["MODEL"];
        if( $this->formDefinition["INDEXFIELDS"] )
        {
            foreach($this->formDefinition["INDEXFIELDS"] as $key=>$value)
                $this->formDefinition["FIELDS"][$key]=$value;
        }

        $this->actionResult=& $actionResult;
        // Aunque sea la misma accion, hay que resetear el resultado, ya que en caso de que este sea el resultado
        // de una action anterior, el resultado seguramente es "false", por lo que ni se reevaluaria.


        parent::__construct($this->formDefinition);
    }
    function resetResult()
    {
        $this->actionResult->reset();
    }
    static function getFormDefinition(& $definition)
    {
        if(isset($definition["ACTION"]) && isset($definition["ACTION"]["INHERIT"]) && $definition["ACTION"]["INHERIT"])
        {
            $obj=new \model\reflection\Model\ModelName($definition["ACTION"]["MODEL"]);
            $clName=$obj->getNamespacedAction($definition["ACTION"]["ACTION"]);
            include_once($obj->getActionFileName($definition["ACTION"]["ACTION"]));

            $actDef=$clName::$definition;
            if(!isset($definition["FIELDS"]))
                $definition["FIELDS"]=array();
            if(isset($actDef["FIELDS"]))
                $definition["FIELDS"]=array_merge($actDef["FIELDS"],$definition["FIELDS"]);
            if(isset($actDef["INDEXFIELDS"]))
                $definition["INDEXFIELDS"]=$actDef["INDEXFIELDS"];
        }
        return $definition;

    }
    private static function buildHashString($formName,$object,$siteName,$url,$keys=null,$sessionId=null)
    {
        $hash=$formName.$object.$siteName.$url;

        if($keys)
            $hash.=implode("",$keys);
        $hash.=$sessionId?$sessionId:"";
        return $hash;
    }
    static function getHash($formName,$object,$siteName,$url,$keys=null,$sessionId=null)
    {
        $hashString=Form::buildHashString($formName,$object,$siteName,$url,$keys,$sessionId);
        return password_hash($hashString,PASSWORD_DEFAULT);
    }
    static function checkHash($hash,$formName,$object,$siteName,$url,$keys=null,$sessionId)
    {
        $hashString=Form::buildHashString($formName,$object,$siteName,$url,$keys,$sessionId);
        return password_verify($hashString,$hash);
    }

    function initialize($keys)
    {
/*        if(isset($this->formDefinition["MODEL"]))
        {
            //$this->srcModelInstance=$this->getModelInstance($keys);
            if($this->actionResult->isOk())
            {
                $this->copy($this->srcModelInstance);
                $stateTarget=$this->srcModelInstance;
//                $this->__stateDef=new \lib\model\states\StatedDefinition($stateTarget);
            }
        }
        else
        {
            $this->__stateDef=new \lib\model\states\StatedDefinition($this);
        }
*/

    }

    static function getForm($object,$name,$keys,$modelInstance=null)
    {

        $instanceError=false;
        if(Form::isLast($object,$name,$keys))
        {
            $modelInstance=\lib\output\html\Form::getLastForm();
            return $modelInstance;
        }

        $objName=new \model\reflection\Model\ModelName(str_replace("/",'\\',$object));
        include_once($objName->getFormFileName($name));
        $formClass=$objName->getNamespacedForm($name);
        $actionResult=new \lib\action\ActionResult();
        $form=new $formClass($actionResult);
        $form->initialize($keys);
        return $form;
    }
    static function resolve($request)
    {
        $data=$request->getActionData();
        if(!$data)
        {
            throw new \Exception();
        }
        if(!isset($data["name"]) || !isset($data["object"]) || !isset($data["site"]) || !isset($data["validationCode"])  || !isset($data["page"]))
        {
            throw new \Exception();
        }
        $form=$data["name"];
        $model=$data["object"];
        $site=$data["site"];
        $seccode=$data["validationCode"];
        $page=$data["page"];
        $keys=isset($data["KEYS"])?array_values($data["KEYS"]):null;
        // Se comprueba el codigo de seguridad.
        if(!Form::checkHash($seccode,$form,$model,$site,$page,$keys,\Registry::$registry["session"]))
        {
            throw new \Exception();
        }
        $formInfo=\lib\output\html\Form::getFormPath(
            $model,
            $form
        );
        if(!$formInfo)
        {
            throw new \Exception();
        }
        $actionResult=new \lib\action\ActionResult();
        $className=$formInfo["CLASS"];
        $classPath=$formInfo["PATH"];

        // Se incluye la definicion del formulario.
        include_once($classPath);
        $curForm=new $className($actionResult);
        $curForm->initialize(\Registry::$registry["action"]["keys"]);
        $curForm->process();
    }

    // Se sobreescribe getField para que la definicion de campos tipo model/field, se creen con definiciones del tipo de dato,
    // sobre todo con especificaciones de path.
    function & __getField($fieldName)
    {
        if(!isset($this->__fields[$fieldName]))
        {
            if(!isset($this->__fieldDef[$fieldName]))
            {
                include_once(PROJECTPATH."/lib/model/BaseModel.php");
                throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));
            }

            $def=$this->__fieldDef[$fieldName];
            if(!$def["MODEL"])
            {
                $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$this->__fieldDef[$fieldName]);
                return $this->__fields[$fieldName];
            }
            $field=$def["FIELD"];
            $remDef=$this->__findRemoteType(explode("/",$field),$def["MODEL"]);
            $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$remDef);
            if($this->__fields[$fieldName]->isRelation())
            {
                $def=$this->__fields[$fieldName]->getType()->getRelationshipType()->getDefinition();
                $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$def);
            }

        }
        return $this->__fields[$fieldName];
    }
    function __findRemoteType($fName,$curModel)
    {
        $def=\lib\model\types\TypeFactory::getObjectField($curModel,$fName[0]);

        if(count($fName)==1)
        {

            return $def;
        }
        if(isset($def["MODEL"]) && isset($def["FIELDS"]))
        {
            array_splice($fName,0,1);
            return $this->__findRemoteType($fName,$def["MODEL"]);
        }
    }

    function getModelInstance($keys)
    {
/*        if($this->srcModelInstance!=null)
            return $this->srcModelInstance;
                
        $this->srcModelInstance=\lib\model\BaseModel::getModelInstance($this->srcModelName);        
        if($keys)
        {
            try
            {                                                   
                $serializer=$this->srcModelInstance->__getSerializer();
                $this->srcModelInstance->setId($keys);
                $this->srcModelInstance->unserialize($serializer);                
            }
            catch(\Exception $e)
            {
                 $this->actionResult->addGlobalError($e);
            }
        }
        return $this->srcModelInstance;*/
    }

    function process($doRedirect=true)
    {
        if (!$this->actionResult->isOk()) {
            return false;
        }

        include_once(LIBPATH . "/output/html/InputFactory.php");
        $formData = \Registry::$registry["action"];

        //$hasState=$this->__stateDef->hasState;
        foreach ($this->formDefinition["FIELDS"] as $key => $value) {
            /*if($hasState)
            {
                $editable=$this->isEditable($key);
                $fixed=$this->isFixed($key);
                if(!$editable || $fixed)
                    continue;
            }*/
            $inputName = isset($value["TARGET_RELATION"]) ? $value["TARGET_RELATION"] : $key;
            // Si no viene el tipo de input , se supone textField.
            if (!isset($formData["INPUTS"][$inputName]))
                $curInput = "DefaultInput";
            else
                $curInput = $formData["INPUTS"][$inputName];
            // Se obtiene el controlador.            
            $inputController = \lib\templating\html\inputs\InputFactory::getInputController($inputName, $curInput, $value, $this->formDefinition["INPUTS"][$inputName]);
            try {
                // Puede ser que formValues["FIELDS"][$field] no este "set",y, aun asi, el campo tenga un valor.
                // Por ejemplo, en los checkboxes.

                if (isset($formData["FIELDS"][$inputName])) {
                    $currentInputValue = $formData["FIELDS"][$inputName];
                    $inputController->unserialize($currentInputValue);
                    \Registry::$registry["action"]["FIELDS"][$key] = $inputController->getValue();
                    $this->unserializeValue($key, $inputController, $value, $formData["FIELDS"], $this->actionResult);
                } else {
                    $currentInputValue = null;
                    \Registry::$registry["action"]["FIELDS"][$key] = null;
                }


                // Al pasarlo al action, siempre va a ser con el nombre del campo, no con el nombre del input.


            } catch (\lib\output\html\inputs\InputException $e) {
                $this->actionResult->addFieldInputError($inputName, $input, $currentInputValue, $e);

                if ($e->fatal())
                    return;
            }


            /*try
            {
            if( $this->isRequired($key))
            {
                $this->actionResult->addFieldTypeError($inputName, $inputController, new \lib\model\types\BaseTypeException(\lib\model\types\BaseTypeException::ERR_UNSET));
            }
            else
                $this->__getField($inputName)->clear();
            }catch(\lib\model\BaseModelException $e)
            {
                // Se ha accedido a una relacion inversa que no estaba definida
                if($e->getCode()==\lib\model\BaseModelException::ERR_INVALID_OFFSET)
                {
                    $def=$this->__getField($key)->getDefinition();
                    if($def["REQUIRED"])
                        $this->actionResult->addFieldTypeError($inputName, $inputController, new \lib\model\types\BaseTypeException(\lib\model\types\BaseTypeException::ERR_UNSET));

                }
            }*/

        }

        // _d($this->actionResult);
        $errored=false;
        if ($this->actionResult->isOk()) {
            if ($this->processAction($this->actionResult)) {
                // Se destruye la informacion de LastForm del registro.
                unset(\Registry::$registry["lastForm"]);
                $session=\Registry::getService("session");

                unset($session["Registry/lastForm"]);
                unset(\Registry::$registry["newForm"]);
                unset(\Registry::$registry["lastAction"]);
                unset(\Registry::$registry["newAction"]);
                $this->onSuccess($this->actionResult);
            } else {

                $this->onError($this->actionResult);
                $errored = true;
            }
        } else {
            $this->onError($this->actionResult);
            $errored = true;

        }

        if ($errored) {
            \Registry::$registry["newForm"] = array(
                "MODEL" => $this->formDefinition["MODEL"],
                "NAME" => $this->formDefinition["NAME"],
                "DATA" => $formData["FIELDS"],
                "RESULT" => $this->actionResult->isOk()
            );
        }

        \Registry::$registry["newAction"] = $this->actionResult;
        if (!$doRedirect)
            return $this->actionResult;
        \Registry::save();

        global $request;
        $data=$request->getActionData();
        $page=$data["page"];


        /*if ($this->actionResult->isOk()) {
            $page->onFormSuccess($this);
        } else
            $page->onFormError($this);*/
        \lib\Router::routeToReferer();
    }

    function getResult()
    {
        return $this->actionResult;
    }
    
    function unserializeValue($field,$inputObj,$definition,$formValues,$actionResult)
    {

        // Hay que hacer un tratamiento especial para las relaciones multiples.Primero se comprueba si este campo
        // representa una relacion externa.      
          
        $fieldInstance=$this->__getField($field);

        if(isset($definition["TARGET_RELATION"]))
        {            
            $type=$inputObj->getDataSet();            
            // Ya tenemos el tipo.El problema es la interaccion de este tipo (DataSet), con el campo, que no tiene ese
            // tipo. 
            // Luego, la accion...Tiene que funcionar tambien para ella.
            // Finalmente, el guardado..Hay que hacer que RelationMxN borre todo su valor de la base de datos, e inserte los nuevos.         

            $fieldInstance->copy($type);
            return $fieldInstance;
        }
        else
        {

            $type=$fieldInstance->getType();

            try
            {
                $iVal=$inputObj->getValue();
                // Necesitaria chequeo de campo requerido.
                if($iVal!==null)
                {
                    \lib\model\types\TypeFactory::unserializeType($type,$iVal,"HTML");
                    $this->{$field}=$type->getValue();
                }
            }
            catch(\lib\model\types\BaseTypeException $e)
            {                            
                // Siempre se asigna el campo.Aunque no sea valido.Ya que lo necesitamos para repintarlo en el formulario.
                $this->actionResult->addFieldTypeError($field,$iVal,$e);
                if( $e->fatal() )
                    return;
            }
        }

        return $this->{$field};
    }
   
    function getDataSet($field,$definition,$values)
    {
        // Para obtener 1 dataset,necesitamos recrear el formato del array.
        
    }

    // Los siguientes metodos son para ser sobreescritos en las clases de formulario.
    function validate()
    {

    }

    function onError($actionResult)
    {
        return true;
    }

    function onSuccess()
    {
        return true;
    }

    function processAction()
    {
        if( $this->formDefinition["MODEL"] )
        {
             if($this->formDefinition["INDEXFIELDS"])
             {
                 foreach($this->formDefinition["INDEXFIELDS"] as $key=>$value)
                 {
                     if($this->{"*".$key}->hasOwnValue())
                        $keys[$key]=$this->{$key};
                 }
             }
             else
                 $keys=null;
             
            global $oCurrentUser;            
            $action=\lib\action\Action::getAction($this->formDefinition["ACTION"]["MODEL"],$this->formDefinition["ACTION"]["ACTION"]);
            $action->process($keys,$this,$this->actionResult,$oCurrentUser);
            return $this->actionResult->isOk();
        }
        return false;
    }

    // TODO: Filtrar por las keys.
    static function isLast($object,$name)
    {
        if(!isset(\Registry::$registry["lastForm"]))
            return false;
        $lastForm=\Registry::$registry["lastForm"];        
        if ( $lastForm && $lastForm["MODEL"]==$object && $lastForm["NAME"]==$name)
        {
            return true;
        }
        return false;
    }

    static function getLastForm()
    {
        
        $lastForm=\Registry::$registry["lastForm"];
        if(! $lastForm )
            return null;

        $formInfo=Form::getFormPath($lastForm["MODEL"],$lastForm["NAME"]);
        include_once($formInfo["PATH"]);
        $className=$formInfo["CLASS"];


        $formClass=new $className(\Registry::$registry["lastAction"]);
        $formClass->loadFromArray($lastForm["DATA"],"HTML",true);
        // Se almacenan las keys.
        if(isset($formClass->formDefinition["INDEXFIELDS"]))
        {
            foreach($formClass->formDefinition["INDEXFIELDS"] as $key=>$value)
                $formClass->srcModelKeys[$key]=$lastForm["DATA"][$key];
        }
        return $formClass;        
    }

    static function getFormPath($object,$name)
    {
        $objName=new \model\reflection\Model\ModelName($object);

        return array("CLASS"=>$objName->getNamespacedForm($name),
                     "PATH"=>$objName->getFormFileName($name));
    }
    function __getObjectName()
    {
       if($this->formDefinition["MODEL"])
           return $this->formDefinition["MODEL"];
       return null;
    }
    function __getSerializer()
    {
        $objName=$this->__getObjectName();
        if(!$objName)
            return null;
        
        $instance=\lib\model\BaseModel::getModelInstance($objName);
        return $instance->__getSerializer();
    }
    function __getInputParams($name)
    {
        if(isset($this->formDefinition["INPUTS"]) && isset($this->formDefinition["INPUTS"][$name]))
            return $this->formDefinition["INPUTS"][$name]["PARAMS"];
    }
    function copy(& $remoteObject)
    {
             $remFields=$remoteObject->__getFields();
             
             foreach($remFields as $key=>$value)
             {
                 // Preguntamos 2 cosas: si existe el campo, y si hemos accedido a el previamente, lo que
                 // significa que se ha establecido el valor.En $this->__fields solo estan los campos a los que
                 // se ha accedido.
                 if(isset($this->formDefinition["FIELDS"][$key]) && isset($this->__fields[$key]))
                 {
                     $types=$value->getTypes();
                     foreach($types as $tKey=>$tValue)
                     {
                         $field=$this->__getField($tKey);
                         $field->copyField($tValue);
                     }                 
                 }
             }
             $this->__dirtyFields=$remoteObject->__dirtyFields;             
             $this->__isDirty=$remoteObject->__isDirty;             
    }

}
?>
