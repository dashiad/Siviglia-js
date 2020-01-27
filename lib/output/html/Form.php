<?php
namespace lib\output\html;
include_once(LIBPATH."/model/types/BaseType.php");
class FormException extends \lib\model\BaseException
{
    const ERR_NO_DATA_RECEIVED=1;
    const ERR_INVALID_IDENTIFYING_DATA=2;
    const ERR_INVALID_FORM_HASH=3;
    const ERR_FORM_NOT_FOUND=4;
    const ERR_INVALID=5;
}
class Form extends \lib\model\BaseTypedObject
{
    var $actionResult;
    var $formDefinition;
    var $srcModelInstance=null;
    var $srcModelKeys=null;
    var $srcModelName=null;
    var $formName=null;
    var $formModel=null;
    var $hash;
    function __construct($definition,& $actionResult)
    {
        Form::getFormDefinition($definition);
        $this->formDefinition=$definition;
        $this->srcModelName=$definition["MODEL"];
        $this->actionResult=& $actionResult;
        parent::__construct($this->formDefinition);

    }
    static function getFormDefinition(& $definition)
    {
        if(isset($definition["ACTION"]) && isset($definition["ACTION"]["INHERIT"]) && $definition["ACTION"]["INHERIT"])
        {
            $obj=\lib\model\ModelService::getModelDescriptor($definition["ACTION"]["MODEL"]);
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
    function buildHashString($siteName,$url,$keys=null,$sessionId=null)
    {
        $hash=$this->formName.$this->formModel.$siteName.$url;
        if($keys)
            $hash.=implode("",$keys);
        $hash.=$sessionId?$sessionId:"";
        return $hash;
    }
    function createHash($siteName,$url,$keys=null,$sessionId=null)
    {
        if($this->hash==null) {
            $hashString = $this->buildHashString($siteName, $url, $keys, $sessionId);
            $this->hash=password_hash($hashString, PASSWORD_DEFAULT);
        }
        return $this->hash;
    }
    function getHash()
    {
        return $this->hash;
    }
    function checkHash($hash,$siteName,$url,$keys=null,$sessionId=null)
    {
        $hashString=Form::buildHashString($siteName,$url,$keys,$sessionId);
        return password_verify($hashString,$hash);
    }

    function initialize($formName,$formModel,$keys,$baseTypedObject=null)
    {
        $this->formModel=$formModel;
        $this->formName=$formName;
        // Si tenemos un modelo, y no nos pasan un modelo ya construido
        if($baseTypedObject===null) {
            if (isset($this->formDefinition["MODEL"]) && $baseTypedObject === null)
                $this->srcModelInstance = $this->getModelInstance($keys);
        }
        else
            $this->srcModelInstance=$baseTypedObject;

        $remFields=$this->srcModelInstance->__getFields();

        foreach($this->formDefinition["FIELDS"] as $key=>$value)
        {
            if(isset($remFields[$key]) && $remFields[$key]->is_set())
                $this->{"*".$key}->copy($remFields[$key]->getType());

        }
    }

    static function getForm($object,$name,$keys,$baseTypedObject=null)
    {
        $instanceError=false;

        $objName=\lib\model\ModelService::getModelDescriptor(str_replace("/",'\\',$object));
        include_once($objName->getFormFileName($name));
        $formClass=$objName->getNamespacedForm($name);
        $actionResult=new \lib\action\ActionResult();
        $form=new $formClass($actionResult);
        $form->initialize($name,$object,$keys,$baseTypedObject);
        return $form;
    }
    static function resolve($request)
    {
        $data=$request->getActionData();
        if(!$data)
        {
            throw new FormException(FormException::ERR_NO_DATA_RECEIVED);
        }
        if(!isset($data["name"]) || !isset($data["object"]) || !isset($data["site"]) || !isset($data["validationCode"])  || !isset($data["page"]))
        {
            throw new FormException(FormException::ERR_INVALID_IDENTIFYING_DATA);
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
            throw new FormException(FormException::ERR_INVALID_FORM_HASH);
        }
        $formInfo=\lib\output\html\Form::getFormPath(
            $model,
            $form
        );
        if(!$formInfo)
        {
            throw new FormException(FormException::ERR_FORM_NOT_FOUND);
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
     /*       if($this->__fields[$fieldName]->isRelation())
            {
                $def=$this->__fields[$fieldName]->getType()->getRelationshipType()->getDefinition();
                $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$def);
            }*/
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
        if($this->srcModelInstance!=null)
            return $this->srcModelInstance;

        $service=\Registry::getService("model");

        $this->srcModelInstance=$service->getModel($this->srcModelName);
        if($keys)
        {
            try
            {
                $this->srcModelInstance=$service->loadModel($this->srcModelName,$keys);
            }
            catch(\Exception $e)
            {

                 $this->actionResult->addGlobalError($e);
            }
        }
        else
            $this->srcModelInstance=$service->getModel($this->srcModelName);
        return $this->srcModelInstance;
    }


    function process($doRedirect=true)
    {
        if (!$this->actionResult->isOk()) {
            throw new FormException(FormException::ERR_INVALID);
        }

        include_once(LIBPATH . "/output/html/InputFactory.php");
        $formData = \Registry::$registry["action"];

        //$hasState=$this->__stateDef->hasState;
        $unserializedFields=array();
        foreach ($this->formDefinition["FIELDS"] as $key => $value) {
            if($this->__getField($key)->isDirty())
                    continue;
            $inputName = isset($value["TARGET_RELATION"]) ? $value["TARGET_RELATION"] : $key;

                $mapped=$inputName;
            // Si no viene el tipo de input , se supone textField.
            if(!isset($formData["INPUTS"][$mapped]))
                $curInput = "DefaultInput";
            else
                $curInput=$formData["INPUTS"][$mapped];
            // Se obtiene el controlador.
            $inputController=\lib\templating\html\inputs\InputFactory::getInputController($mapped,$curInput,$value,$this->formDefinition["INPUTS"][$mapped]);
            try
            {
                // Puede ser que formValues["FIELDS"][$field] no este "set",y, aun asi, el campo tenga un valor.
                // Por ejemplo, en los checkboxes.

                if(isset($formData["FIELDS"][$mapped]))
                {
                    $currentInputValue=$formData["FIELDS"][$mapped];
                    $inputController->unserialize($currentInputValue);
                    $val=$inputController->getValue();
                    \Registry::$registry["action"]["FIELDS"][$key] = $val;
                    $unserializedFields[$key]=$val;
                }
                else
                {
                    $currentInputValue = null;
                    \Registry::$registry["action"]["FIELDS"][$key] = null;
                }


                // Al pasarlo al action, siempre va a ser con el nombre del campo, no con el nombre del input.


            }
            catch(\lib\output\html\inputs\InputException $e)
            {
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
        $errored=false;
        if($this->actionResult->isOk())
        {
            if(!$this->__validate($unserializedFields,$this->actionResult,"PHP"))
            {
                $this->onError($this->actionResult);
                $errored=true;
            }
            $this->__fields=$this->actionResult->getParsedFields();
            $this->__loaded=true;
        }
        if($this->actionResult->isOk())
            $this->validate($this->actionResult);
        // _d($this->actionResult);

        if ($this->actionResult->isOk()) {
            if ($this->processAction($this->actionResult)) {
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
        // gestion de la redireccion.
        if(isset($this->formDefinition["REDIRECT"]))
            {
            $redirect=$this->formDefinition["REDIRECT"][$this->actionResult->isOk()?"ON_SUCCESS":"ON_ERROR"];
            \Registry::save();
            // TODO : Hacer el redirect!!
        }
        else
            {

        global $request;
        $data=$request->getActionData();
        $page=$data["page"];


        /*if ($this->actionResult->isOk()) {
            $page->onFormSuccess($this);
        } else
            $page->onFormError($this);*/
        \lib\Router::routeToReferer();
        }
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


        return $this->{$field};
    }

    function getDataSet($field,$definition,$values)
    {
        // Para obtener 1 dataset,necesitamos recrear el formato del array.

    }

    // Los siguientes metodos son para ser sobreescritos en las clases de formulario.
    function validate( $actionResult)
    {
        return $actionResult->isOk();
    }

    function onError($actionResult)
    {
        return true;
    }

    function onSuccess($actinResult)
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


    static function getFormPath($object,$name)
    {
        $objName=\lib\model\ModelService::getModelDescriptor($object);

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
        $s=\Registry::getService("model");
        $instance=$s->getModel($objName);
        return $instance->__getSerializer();
    }
    function __getInputParams($name)
    {
        if(isset($this->formDefinition["INPUTS"]) && isset($this->formDefinition["INPUTS"][$name]))
            return $this->formDefinition["INPUTS"][$name]["PARAMS"];
    }

}
?>
