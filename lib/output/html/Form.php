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
        {
            $keysSorted=array_keys($keys);
            sort($keysSorted);
            for($k=0;$k<count($keysSorted);$k++)
                $hash.=($keysSorted[$k].$keys[$keysSorted[$k]]);

        }
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

    function initialize($formName,$formModel)
    {
        $this->formModel=$formModel;
        $this->formName=$formName;

    }

    function load($keys=null)
    {
        $modelService=\Registry::getService("model");
        $instance=$modelService->getModel($this->formModel);
        if($keys==null)
            $keys=$this->keys;
        if($keys)
        {
            foreach($keys as $k=>$v)
            {
                $instance->{$k}=$v;
            }
            $instance->loadFromFields();
        }
        $curValue=[];
        foreach($this->__fieldDef as $key=>$value)
        {
            if(isset($value["MODEL"]))
                $this->{$key}=$instance->{"*".$key}->getValue();
        }
    }

    static function getForm($object,$name)
    {
        $instanceError=false;

        $objName=\lib\model\ModelService::getModelDescriptor(str_replace("/",'\\',$object));
        include_once($objName->getFormFileName($name));
        $formClass=$objName->getNamespacedForm($name);
        $actionResult=new \lib\action\ActionResult();
        $form=new $formClass($actionResult);
        $form->initialize($name,$object);
        return $form;
    }
    function resolve($request)
    {

        $this->actionResult=$this->process($request);
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
            $isKey=false;
            if(isset($this->formDefinition["INDEXFIELDS"]) && in_array($key,$this->formDefinition["INDEXFIELDS"]))
                $isKey=true;
            if($this->__getField($key)->isDirty() && !$isKey)
                    continue;

            $inputName=$key;

            // Si no viene el tipo de input , se supone textField.
            if(!isset($formData["INPUTS"][$key]))
                $curInput = "DefaultInput";
            else
                $curInput=$formData["INPUTS"][$key];
            // Se obtiene el controlador.
            $inputController=\lib\templating\html\inputs\InputFactory::getInputController($key,$curInput,$value,
                isset($this->formDefinition["INPUTS"][$key])?$this->formDefinition["INPUTS"][$key]:[]);
            try
            {
                // Puede ser que formValues["FIELDS"][$field] no este "set",y, aun asi, el campo tenga un valor.
                // Por ejemplo, en los checkboxes.
                $currentInputValue=null;
                if($isKey)
                {
                    $currentInputValue=$formData["keys"][$key];
                }
                else
                {
                    if(isset($formData["FIELDS"][$key]))
                    {
                        $currentInputValue=$formData["FIELDS"][$key];
                    }
                }
                    $inputController->unserialize($currentInputValue);
                    $val=$inputController->getValue();
                    $unserializedFields[$key]=$val;
            }
            catch(\lib\output\html\inputs\InputException $e)
            {
                $this->actionResult->addFieldInputError($inputName, $input, $currentInputValue, $e);

                if ($e->fatal())
                    return;
            }
        }
        foreach ($this->formDefinition["FIELDS"] as $key => $value) {
            if(isset($unserializedFields[$key])) {
                try {
                    $htmlSerializer->unserializeType($key, $this->{"*" . $key}, $unserializedFields, $this);
                }catch(\Exception $e)
                {
                    $this->actionResult->addFieldInputError($key,$unserializedFields[$key],$e);
                }
            }
        }
        /*if(isset($this->formDefinition["INDEXFIELDS"])) {
            for ($k = 0; $k < count($this->formDefinition["INDEXFIELDS"]); $k++) {
                $this->{$this->formDefinition["INDEXFIELDS"][$k]} = $value;
            }
        }
        $errored=false;*/
        if($this->actionResult->isOk())
        {
            if(!$this->__validate($unserializedFields,$this->actionResult,"PHP"))
            {
                $this->onError($this->actionResult);
            }
            $this->__loaded=true;
        }
        if($this->actionResult->isOk())
            $this->validate($this->actionResult);

        if ($this->actionResult->isOk()) {
            if ($this->processAction($this->actionResult)) {
                $this->onSuccess($this->actionResult);
            } else {

                $this->onError($this->actionResult);
                $errored = true;
            }
        } else {
            $this->onError($this->actionResult);
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

    function onSuccess($actionResult)
    {
        return true;
    }

    function processAction()
    {
        if( $this->formDefinition["MODEL"] )
        {
             if($this->formDefinition["INDEXFIELDS"])
             {
                 for($k=0;$k<count($this->formDefinition["INDEXFIELDS"]);$k++)
                 {
                     $c=$this->formDefinition["INDEXFIELDS"][$k];
                     if($this->{"*".$c}->hasOwnValue())
                        $keys[$c]=$this->{$c};
                 }
             }
             else
                 $keys=null;

            $user=\Registry::getService("user");
            $action=\lib\action\Action::getAction($this->formDefinition["ACTION"]["MODEL"],$this->formDefinition["ACTION"]["ACTION"]);
            $action->process($this,$this->actionResult,$user);
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

    function __getInputParams($name,$path=null)
    {
        if(isset($this->formDefinition["INPUTS"]) && isset($this->formDefinition["INPUTS"][$name]))
            return $this->formDefinition["INPUTS"][$name]["PARAMS"];
    }
}
?>
