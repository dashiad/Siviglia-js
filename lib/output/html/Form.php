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
    var $srcModelName=null;
    var $formName=null;
    var $formModel=null;
    var $keys;
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

    function initialize($formName,$formModel,$keys)
    {
        $this->formModel=$formModel;
        $this->formName=$formName;
        $this->keys=$keys;

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
    function resolve($request)
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
        if(!$this->checkHash($seccode,$site,$page,$keys,\Registry::$registry["session"]))
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
        $curForm->initialize($model,$form,$keys);
        $curForm->process($request);
    }

    // Se sobreescribe getField para que la definicion de campos tipo model/field, se creen con definiciones del tipo de dato,
    // sobre todo con especificaciones de path.



    function process($request)
    {
        if (!$this->actionResult->isOk()) {
            throw new FormException(FormException::ERR_INVALID);
        }

        include_once(LIBPATH . "/output/html/InputFactory.php");
        $formData = $request->getActionData();

        //$hasState=$this->__stateDef->hasState;
        $unserializedFields=array();
        $htmlSerializer=new \lib\storage\HTML\HTMLSerializer();
        foreach ($this->formDefinition["FIELDS"] as $key => $value) {
            if($this->__getField($key)->isDirty())
                    continue;
            
            $inputName=$key;
            // Si no viene el tipo de input , se supone textField.
            if(!isset($formData["INPUTS"][$key]))
                $curInput = "DefaultInput";
            else
                $curInput=$formData["INPUTS"][$key];
            // Se obtiene el controlador.
            $inputController=\lib\templating\html\inputs\InputFactory::getInputController($key,$curInput,$value,$this->formDefinition["INPUTS"][$key]);
            try
            {
                // Puede ser que formValues["FIELDS"][$field] no este "set",y, aun asi, el campo tenga un valor.
                // Por ejemplo, en los checkboxes.

                if(isset($formData["FIELDS"][$key]))
                {
                    $currentInputValue=$formData["FIELDS"][$key];
                    $inputController->unserialize($currentInputValue);
                    $val=$inputController->getValue();


                    // DESERIALIZAR HTML!!!
                    // function unserializeType($name,$mixedType,$value,$model)


                    $unserializedFields[$key]=$val;
                }
                else
                {
                    $currentInputValue = null;
                }

            }
            catch(\lib\output\html\inputs\InputException $e)
            {
                $this->actionResult->addFieldInputError($inputName, $input, $currentInputValue, $e);

                if ($e->fatal())
                    return;
            }
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

        return $this->actionResult;
    }

    function getResult()
    {
        return $this->actionResult;
    }

    function unserializeValue($field,$inputObj,$definition,$formValues,$actionResult)
    {

        $fieldInstance=$this->__getField($field);
        $type=$fieldInstance->getType();
        try
        {
            $iVal=$inputObj->getValue();
            // Necesitaria chequeo de campo requerido.
            if($iVal!==null) {
                \lib\model\types\TypeFactory::unserializeType($type, $iVal, "HTML");
                $this->{$field} = $type->getValue();
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
            $action->process($this,$this->actionResult,$oCurrentUser);
            return $this->actionResult->isOk();
        }
        return false;
    }


    static function getFormPath($object,$name)
    {
        $s=\Registry::getService("model");
        $objName=$s->getModelDescriptor($object);

        return array("CLASS"=>$objName->getNamespacedForm($name),
                     "PATH"=>$objName->getFormFileName($name));
    }
    function __getObjectName()
    {
       if($this->formDefinition["MODEL"])
           return $this->formDefinition["MODEL"];
       return null;
    }

    function __getInputParams($name)
    {
        if(isset($this->formDefinition["INPUTS"]) && isset($this->formDefinition["INPUTS"][$name]))
            return $this->formDefinition["INPUTS"][$name]["PARAMS"];
    }

}
?>
