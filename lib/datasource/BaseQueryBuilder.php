<?php
/**
 * Class BaseQueryBuilder
 * @package lib\datasource
 *  (c) Smartclip
 */


namespace lib\datasource;


use lib\php\ParametrizableString;

abstract class BaseQueryBuilder
{
    var $definition;
    var $nonParams = array("__start", "__count", "__sort", "__sortDir", "__sort1", "__sortDir1");
    function __construct($serializer,$definition,$params=null,$pagingParams=null)
    {
        $this->serializer=$serializer;
        $this->definition = $definition;
        $this->findRows = false;
        $this->data = $params ? $params : new \lib\model\BaseTypedObject(array("FIELDS" => array()));

        if ($pagingParams) {
            $fields = $pagingParams->__getFields();
            if ($fields) {
                foreach ($fields as $cKey => $val) {
                    switch ($cKey) {
                        case "__start":
                            {
                                if ($val->__hasValue())
                                    $this->setStartingRow($val->getValue());

                            }
                            break;
                        case "__count":
                            {
                                if ($val->__hasValue())
                                    $this->setPageSize($val->getValue());
                            }
                            break;
                        case "__sort":
                            {
                                if ($val->__hasValue()) {
                                    $sortDir = $fields["__sortDir"] ? $fields["__sortDir"]->getLabel() : "ASC";

                                    $this->setDefaultOrder($val->getValue(), $sortDir);
                                }
                            }
                            break;
                    }
                }

            }
        }

    }
    function replaceParams($text)
    {

        $parameters=$this->data->getAsDictionary(false);
        $fields=$this->data->getFields();
        $plainParams=[];
        // Se examina si algun parametro es dinamico.Un parametro dinamico es, por ejemplo, en mysql, "A*", donde lo que se busca no es
        // que un valor coincida con "A*", sino que empiece por "A"
        if($fields) {
            foreach ($fields as $key => $curField) {
                $fdef = $curField->getDefinition();
                if (isset($parameters[$key]) && isset($fdef["PARAMTYPE"]) && $fdef["PARAMTYPE"] == "DYNAMIC") {
                    $curField->__rawSet($this->getDynamicParamValue($parameters[$key],
                        isset($fdef["DYNAMICTYPE"]) ? $fdef["DYNAMICTYPE"] : null
                    ));
                }
            }
            $allSerialized = $this->serializer->serializeToArray($this->data);
            $plainParams=$allSerialized[0];
            foreach($plainParams as $k=>$v)
                $plainParams[$k]=json_encode($v);
        }
        $stack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($plainParams,"/",$stack);
        // Se aniade el contexto global
        new \lib\model\BaseObjectContext(\lib\model\GlobalContext::getInstance(),"!",$stack);
        return ParametrizableString::getParametrizedString($text,$stack);
    }
    abstract function getDynamicParamValue($paramValue,$paramType);
    abstract function getSerializerType();
    abstract function build();

    function setStartingRow($row)
    {
        $this->definition["STARTINGROW"] = $row;
    }

    function setPageSize($pageSize)
    {
        $this->definition["PAGESIZE"] = $pageSize;
    }

    function setDefaultOrder($field, $direction = "ASC")
    {
        $this->definition["DEFAULT_ORDER"] = $field;
        $this->definition["DEFAULT_ORDER_DIRECTION"] = $direction;
    }
    function findFoundRows($doit = true)
    {
        $this->findRows = $doit;
    }
}
