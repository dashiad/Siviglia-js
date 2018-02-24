<?php
namespace lib\output\html;
class  WebPageException extends \lib\model\BaseException
{
    const ERR_REQUIRED_PARAM = 1;
    const ERR_INVALID_PARAM = 2;
    const ERR_UNAUTHORIZED = 3;
}

/*

*/

class WebPage extends \lib\model\BaseTypedObject
{
    function __construct($request, $parameters)
    {
        if (!$this->definition["FIELDS"]) {
            if ($this->definition["INHERIT_PARAMS_FROM_URL"])
                $this->definition["FIELDS"] = $request->urlCandidate["PARAMS"];
            else
                $this->definition["FIELDS"] = array();
        }

        $getData = \Registry::$registry["params"];
        if ($this->definition["ROLE"] == "action") {
            if (!is_array(\Registry::$registry["action"])) {
                \Registry::$registry["action"] = array("FIELDS" => \Registry::$registry["params"]);
            } else {
                foreach (\Registry::$registry["params"] as $key => $value)
                    \Registry::$registry["action"]["FIELDS"][$key] = $value->getValue();
                //\Registry::$registry["action"]["FIELDS"]=array_merge(\Registry::$registry["action"]["FIELDS"],\Registry::$registry["params"]);
            }
        }

        if (is_array($parameters)) {
            $fullData = array_merge($getData, $parameters);
        } else
            $fullData = $getData;

        \lib\model\BaseTypedObject::__construct($this->definition);

        foreach ($this->definition["FIELDS"] as $getKey => $getDef) {
            if (!isset($fullData[$getKey])) {
                if ($getDef["REQUIRED"]) {

                    throw new WebPageException(WebPageException::ERR_REQUIRED_PARAM, array("name" => $getKey));
                } else
                    unset($this->definition["FIELDS"][$getKey]);
            } else {

                if (is_object($fullData[$getKey])) {
                    $curVal = $fullData[$getKey]->getValue();
                } else
                    $curVal = $fullData[$getKey];

                \lib\model\types\TypeFactory::unserializeType($this->{"*" . $getKey}, $curVal, "HTML");
            }
        }
        $instance = null;
        if ($this->definition["MODELIDS"]) {

            foreach ($this->definition["MODELIDS"] as $key => $value) {
                $modelName = $key;
                $curKey = array();
                $instance = \lib\model\BaseModel::getModelInstance($modelName);

                $instance->disableStateChecks();
                foreach ($value as $fieldKey) {
                    $curField = $this->__getField($fieldKey);

                    $instance->{$fieldKey} = $curField->getType()->getValue();
                }
                try {
                    $instance->loadFromFields();
                    $instance->enableStateChecks();
                    \Registry::$registry["currentModel"] = $instance;
                } catch (\lib\model\BaseModelException $e) {
                }


            }

        } else {
            if ($this->definition["MODEL"])
                $instance = \lib\model\BaseModel::getModelInstance($this->definition["MODEL"]);
        }

        if ($instance) {
            global $oCurrentUser;
            try {
                $this->checkPermissions($oCurrentUser, $instance);

            } catch (\Exception $e) {
                echo "Sin permisos";
                exit();
            }
        }
    }

    function render($renderType, $requestedPath, $outputParams)
    {
        $fileType = ucfirst($renderType) . 'Renderer';
        include_once(LIBPATH . '/output/html/renderers/' . $fileType . '.php');
        $className = "\\lib\\output\\html\\renderers\\" . $fileType;
        $renderer = new $className();
        $renderer->render($this, $requestedPath, $outputParams);
    }

    // Este metodo puede ser sobreescrito por las clases Page
    function onUserNotLogged($outputType)
    {
        switch ($outputType) {
            case "html": {
                header("Location: /");
                die();
            }
                break;
            case "json": {
                die(json_encode(array("result" => 0, "error" => 2)));
            }
        }
    }

}
