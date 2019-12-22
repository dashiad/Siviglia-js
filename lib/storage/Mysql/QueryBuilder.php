<?php
namespace lib\storage\Mysql;
class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{

    var $findRows;
    var $data;
    var $sqlData;

    function makeRegExp($val)
    {
        return "/@@" . mysqli_escape_string($val) . "@@/";
    }

    function build($onlyConditions = false)
    {
        global $registry;

        $curQuery = $this->definition;
        if ($onlyConditions == false) {
            if(!isset($curQuery["BASE"]))
            {
                $curQuery["BASE"]=[];
                $selectStr="SELECT " . ($this->findRows ? "SQL_CALC_FOUND_ROWS " : "") ." * FROM " . $curQuery["TABLE"];
            }
            else {
                if (is_array($curQuery["BASE"])) {
                    if (!isset($curQuery["TABLE"]))
                        throw new MysqlException(MysqlException::ERR_NO_TABLE);
                    $selectStr = "SELECT ". implode(",", $curQuery["BASE"]) . " FROM " . $curQuery["TABLE"];
                } else
                    $selectStr = trim($curQuery["BASE"]);
            }
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
        $orderBy = isset($curQuery["DEFAULT_ORDER"]) ? $curQuery["DEFAULT_ORDER"] : (isset($curQuery["ORDERBY"])?$curQuery["ORDERBY"]:null);
        $orderDirection = isset($curQuery["DEFAULT_ORDER_DIRECTION"]) ? $curQuery["DEFAULT_ORDER_DIRECTION"] : (isset($curQuery["ORDERTYPE"])?$curQuery["ORDERTYPE"]:null);
        if($orderBy!==null)
        {
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

        //Modificamos la query text para incorporar las partes interiores
        $queryText = $this->buildInnerParts($queryText);

        //Construimos las condiciones
        $queryText = $this->buildConditions($queryText);

        // Se prepara el serializado de todos los campos que esten "set"
        $paramsObj=[];
        $dsFields=$this->data->__getFields();
        if($dsFields!==null) {
            foreach ($dsFields as $key => $value) {
                $curField = $this->data->__getField($key);
                if ($curField->getType()->hasOwnValue()) {
                    $fdef = $curField->getDefinition();

                    if (isset($fdef["PARAMTYPE"]) && $fdef["PARAMTYPE"] == "DYNAMIC") {
                        $val = $curField->getValue() . '%';
                        if (isset($fdef["DYNAMICTYPE"]) && $fdef["DYNAMICTYPE"] == "BOTH") {
                            $val = '%' . $val;
                        }
                        $curField->setValue($val);
                    }
                    // Aqui tenemos un pequeño problema.
                    // Una cosa es serializar un valor para usarlo como campo a insertar (insert o update), y otra cosa
                    // es serializarlo para usarlo en el "where" de un select.
                    // Permitimos que un campo de filtro sea un array. Qué significa serializar un array?
                    // Si fuera para insertarlo, posiblemente seria un serializado json.
                    // Sin embargo, aqui, lo que necesitamos es una lista de elementos separados por comas,
                    // ya que lo estamos usando en un IN (...).
                    // Asi que vamos a manejar ese caso por separado.
                    $type = $curField->getType();
                    if (!is_a($type, '\lib\model\types\_Array'))
                        $serializedVal = $this->serializer->serializeType($key, $curField->getType());
                    else {
                        $n = $type->count();
                        $def = $type->getDefinition();
                        $elementType = $def["ELEMENTS"];
                        $subtype = \lib\model\types\TypeFactory::getType($this, $elementType);
                        $subVals = [];
                        for ($s = 0; $s < $n; $s++) {
                            $subtype->setValue($type[$s]);
                            $serialized = $this->serializer->serializeType($key, $subtype);
                            $subVals[] = $serialized[$key];
                        }
                        $serializedVal[$key] = implode(",", $subVals);
                    }

                    foreach ($serializedVal as $kk => $vv)
                        $paramsObj[$kk] = $vv;
                }

            }
        }
        return \lib\php\ParametrizableString::getParametrizedString($queryText,$paramsObj);
    }

    function buildInnerParts($base)
    {
        $curQuery = $this->definition;
        $conditions = $curQuery["CONDITIONS"];
        if (!$conditions) return $base;

        foreach ($conditions as $conditionName => $curCondition) {
            if (isset($curCondition["TRIGGER_VAR"])) {
                $tVar = $curCondition["TRIGGER_VAR"];
                try {
                    $curField = $this->data->__getField($tVar);
                } catch (\Exception $e) {
                    continue;
                }

                if ($curField->getType()->hasOwnValue()) {
                    //Tenemos que buscar si existe esa parte y substituirla
                    $val = $curField->getType()->getValue();

                    $inEnable = in_array($val, (array)$curCondition["ENABLE_IF"]);
                    $hasDisable = isset($curCondition["DISABLE_IF"]);
                    $hasEnable = isset($curCondition["ENABLE_IF"]);
                    if ($hasDisable && $this->checkDisableValue($val, (array)$curCondition["DISABLE_IF"]))
                        $addPart = false;
                    else {
                        if ($hasEnable)
                            $addPart = $inEnable;
                        else
                            $addPart = true;
                    }

                    if ($addPart) {
                        $replacement = '\\1';
                    }
                    else {
                        $replacement = '';
                    }
                }
                else {
                    $replacement = '';
                }

                $pattern = '/\[%'.$tVar.':(.*)%\]/';
                $matches = array();
                $base = preg_replace($pattern, $replacement, $base);
            }
        }

        return $base;
    }

    function removeUnusedParts($base)
    {
        return preg_replace('/{%.*%}/', '', $base);
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


                // Nota: no queremos valores por defecto heredados del tipo.
                try {
                    $curField = $this->data->__getField($tVar);
                    if (!$curField->getType()->hasOwnValue()) {
                        $notExisting[] = $pattern;
                        continue;
                    }
                } catch (\Exception $e) {
                    $notExisting[] = $pattern;
                    continue;
                }



                $val = $curField->getValue();


                $inEnable = in_array($val, (array)$curCondition["ENABLE_IF"]);

                $hasDisable = isset($curCondition["DISABLE_IF"]);

                $hasEnable = isset($curCondition["ENABLE_IF"]);
                if ($hasDisable && $this->checkDisableValue($val, (array)$curCondition["DISABLE_IF"]))
                    $addCondition = false;
                else {
                    if ($hasEnable)
                        $addCondition = $inEnable;
                    else
                        $addCondition = true;
                    }
            } else {
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
            }
        }

        // Se reemplazan las subcondiciones que se han encontrado.
        if (isset($subConditions) && is_array($subConditions)) {
            $base = str_replace(array_keys($subConditions), array_values($subConditions), $base);
        }
        if (count($notExisting) == 0) {
            return $base;
        }
        $base = str_replace($notExisting, "true", $base);
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
    function getSerializerType()
    {
        // TODO: Implement getSerializerType() method.
        return \lib\storage\Mysql\MysqlSerializer::MYSQL_SERIALIZER_TYPE;
    }
    function getDynamicParamValue($paramValue, $paramType)
    {

        $val=$paramValue.'%';
        if($paramType=="BOTH")
        {
            $val='%'.$val;
        }
        return $val;
    }

}
