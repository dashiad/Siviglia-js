<?php
namespace lib\storage\Mysql;
class QueryBuilder
{
    var $definition;
    var $findRows;
    var $data;
    var $sqlData;
    var $nonParams = array("__start", "__count", "__sort", "__sortDir", "__sort1", "__sortDir1");

    function __construct($definition, $params = null, $pagingParams = null)
    {
        $this->definition = $definition;
        $this->findRows = false;
        $this->data = $params ? $params : new \lib\model\BaseTypedObject(array("FIELDS" => array()));

        if (isset($definition["REFERENCE"])) {
            include_once(OBJECTSPATH . "/objects/" . $definition["REFERENCE"]["MODEL"] . "/SQL/Queries.php");
            global $Queries;
            $this->definition = $Queries[$definition["REFERENCE"]["MODEL"]][$definition["REFERENCE"]["NAME"]];
        }
        if ($pagingParams) {
            $fields = $pagingParams->__getFields();
            if ($fields) {
                foreach ($fields as $cKey => $val) {
                    switch ($cKey) {
                        case "__start":
                        {
                            if ($val->hasValue())
                                $this->setStartingRow($val->getValue());

                        }
                            break;
                        case "__count":
                        {
                            if ($val->hasValue())
                                $this->setPageSize($val->getValue());
                        }
                            break;
                        case "__sort":
                        {
                            if ($val->hasValue()) {
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

    function makeRegExp($val)
    {
        return "/@@" . mysql_escape_string($val) . "@@/";
    }

    function findFoundRows($doit = true)
    {
        $this->findRows = $doit;

    }

    function build($onlyConditions = false)
    {
        $curQuery = $this->definition;
        if ($onlyConditions == false) {
            if (is_array($curQuery["BASE"]))
                $selectStr = "SELECT " . ($this->findRows ? "SQL_CALC_FOUND_ROWS " : "") . implode(",", $curQuery["BASE"]) . " FROM " . $curQuery["TABLE"];
            else
                $selectStr = trim($curQuery["BASE"]);
            if ($this->findRows)
                $selectStr = "SELECT SQL_CALC_FOUND_ROWS " . substr($selectStr, 6);
        } else
            $selectStr = "";

        $queryText = $selectStr;

        /* Aqui se tiene que preparar el resto de la query , segun los parametros */
        $conditions = io($curQuery,"CONDITIONS",null);
        if ((is_array($curQuery["BASE"] ) || $onlyConditions )&& $conditions && count($conditions) > 0) {
            $params = "[%" . implode("%] AND [%", array_keys($conditions)) . "%]";

            if (!strstr($queryText, " WHERE "))
                $queryText .= " WHERE ";
            $queryText .= $params;
        }
        if (isset($curQuery["GROUPBY"]))
        {
            $queryText .= " GROUP BY " . $curQuery["GROUPBY"];
        }
        $orderBy=null;
        if(isset($curQuery["ORDERBY"]))
            $orderBy=$curQuery["ORDERBY"];
        else
        {
            if(isset($curQuery["DEFAULT_ORDER"]))
                $orderBy=$curQuery["DEFAULT_ORDER"];
        }
        if($orderBy)
        {
            $orderDirection="ASC";
            if(isset($curQuery["ORDERTYPE"]))
                $orderDirection=$curQuery["ORDERTYPE"];
            else
            {
                if(isset($curQuery["DEFAULT_ORDER_DIRECTION"]))
                    $orderDirection=$curQuery["DEFAULT_ORDER_DIRECTION"];
            }

            if(is_array($orderBy))
            {
                $orderExpression=" ORDER BY ";
                $n=0;
                foreach($orderBy as $key=>$value)
                {
                    $orderExpression.=($n?",":"").$key." ".$value;
                    $n++;
                }
                if(strpos($queryText,'@@ORDERBY@@')!==false)
                    $queryText=str_replace("@@ORDERBY@@",$orderExpression,$queryText);
                else
                    $queryText.=" ".$orderExpression;

            }
            else
                $queryText .= " ORDER BY " . $orderBy . " " . $orderDirection;
        }
        $limitExpression='';
        if (isset($curQuery["PAGESIZE"])) {
            $limitExpression= " LIMIT ";
            if ($curQuery["STARTINGROW"])
                $limitExpression .= $curQuery["STARTINGROW"] . ",";
            $limitExpression .= $curQuery["PAGESIZE"];
        } else {
            if (isset($curQuery["LIMIT"]))
                $limitExpression = " LIMIT " . $curQuery["LIMIT"];
        }
        if($limitExpression!='')
        {
            if(strpos($queryText,'@@LIMIT@@')!==false)
            {
                $queryText=str_replace('@@LIMIT@@',$limitExpression,$queryText);
            }
            else
                $queryText.=$limitExpression;
        }

        $queryText = $this->buildConditions($queryText);
        // Se hace el reemplazo final, obteniendo los campos serializados.
        preg_match_all('/{\%([^%]+)\%}/', $queryText, $matches);
        if (count($matches[1]) > 0 && !$this->data) {
            // TODO: throw exception
            return false;
        }
        $keys=array();
        $values=array();
        foreach ($matches[1] as $key => $value) {
            $curField=$this->data->__getField($value);

            $fdef=$curField->getDefinition();

            if(isset($fdef["PARAMTYPE"]) && $fdef["PARAMTYPE"]=="DYNAMIC")
            {
                $val=$curField->getValue().'%';
                if(isset($fdef["DYNAMICTYPE"]) && $fdef["DYNAMICTYPE"]=="BOTH")
                {
                    $val='%'.$val;
                }
                $curField->setValue($val);
            }
            $serializedVal = $curField->serialize("MYSQL");
            $keys[] = $matches[0][$key];
            $values[] = $serializedVal;
        }
        $qText = str_replace($keys, $values, $queryText);
        //echo $qText;
        // Se reemplazan las constantes
        preg_match_all("/\|\%([^%]*)\%\|/", $qText, $matches);
        if (isset($matches[1])) {

            $constants = get_defined_constants(true);
            for ($k = 0; $k < count($matches[1]); $k++) {
                if($matches[1][$k]=="currentUser")
                {
                    // TODO : Tirar excepcion cuando el usuario no esta logeado.
                    global $oCurrentUser;
                    if($oCurrentUser->isLogged())
                        $qText=str_replace("|%currentUser%|",$oCurrentUser->getId(),$qText);
                    else
                        $qText=str_replace("|%currentUser%|","false",$qText);
                    continue;
                }
                $c = $matches[1][$k];
                $qText = str_replace("|%" . $c . "%|", $constants["user"][$c], $qText);
            }
        }
        preg_match_all("/\|\|([^|]*)\|\|/", $qText, $matches);
        if (isset($matches[1])) {
            $replaces=array();
            global $SERIALIZERS;
            for ($k = 0; $k < count($matches[1]); $k++) {
                $db=$matches[1][$k];
                $dbName=$SERIALIZERS[$db]["ADDRESS"]["database"]["NAME"];
                $replaces["||".$db."||"]=$dbName;
            }
            $qText=str_replace(array_keys($replaces),array_values($replaces),$qText);
        }
        //echo $qText;


        return $qText;
    }

    function buildConditions($base)
    {
        $curQuery = $this->definition;
        $conditions = io($curQuery,"CONDITIONS",null);

        if (!$conditions)
            return $base;
        $notExisting = array();
        $nSubConditions = 0;
        //$this->buildParams();
        foreach ($conditions as $conditionName => $curCondition) {
            $addCondition = true;
            // Si la trigger var de esta condicion existe, y es distinta del valor de deshabilitacion, esta
            // condicion se usara.
            $pattern = "[%" . $conditionName . "%]";
            if (isset($curCondition["TRIGGER_VAR"])) {
                $tVar = $curCondition["TRIGGER_VAR"];
                $default = (isset($curCondition["DEFAULT"]) ? (empty($curCondition["DEFAULT"]) ? "false" : "true") : "true");

                $addCondition = false;
                try {
                    $curField = $this->data->__getField($tVar);
                } catch (\Exception $e) {
                    $notExisting[] = $pattern;
                    $defaultValue[] = $default;
                    continue;
                }

                if(!$curField->getType())
                {
                    $k=11;
                    $n=12;
                }
                if (!$curField->getType()->hasOwnValue()) {
                    $hasDefaultValue=$curField->getType()->getDefaultValue();
                    if(!$hasDefaultValue)
                    {
                        $defaultValue[] = $default;
                        $notExisting[] = $pattern;
                        continue;
                    }
                }


                $val = $curField->getValue();
                $fdef=$curField->getDefinition();





                $hasDisable = isset($curCondition["DISABLE_IF"]);

                $hasEnable = isset($curCondition["ENABLE_IF"]);
                if ($hasDisable && $this->checkDisableValue($val, (array)$curCondition["DISABLE_IF"])) {
                    $addCondition = false;
                }
                else {
                    if ($hasEnable) {
                        $inEnable = in_array($val, (array)$curCondition["ENABLE_IF"]);
                        $addCondition = $inEnable;
                    }
                    else {
                        $addCondition = true;
                    }
                }
            }
            else {
                $addCondition = true;
            }


            if ($addCondition) {
                // Se debe serializar el tipo de dato
                $nSubConditions++;

                if (is_array($curCondition["FILTER"])) {
                    // Si el filtro es "=", lo sustituimos siempre por "IN"
                    if ($curCondition["FILTER"]["OP"] == "=") {
                        $subConditions[$pattern] = $curCondition["FILTER"]["F"] . " IN (" . $curCondition["FILTER"]["V"] . ")";
                    }
                    else {
                        $subConditions[$pattern] = $curCondition["FILTER"]["F"] . " " . $curCondition["FILTER"]["OP"] . " " . $curCondition["FILTER"]["V"];
                    }
                } else {
                    $subConditions[$pattern] = $curCondition["FILTER"];
                }
            } else {
                $notExisting[] = $pattern;
                $defaultValue[] = $default;
            }
        }

        // Se reemplazan las subcondiciones que se han encontrado.
        if (isset($subConditions) && is_array($subConditions)) {
            $base = str_replace(array_keys($subConditions), array_values($subConditions), $base);
        }
        if (count($notExisting) == 0) {
            return $base;
        }
        $base = str_replace($notExisting, $defaultValue, $base);
        $base = str_replace(" AND true", "", $base);
        return $base;
    }

    function checkDisableValue($val, $conditions)
    {
        if (in_array($val, $conditions, true)) {
            return true;
        }

        return false;
    }

}
