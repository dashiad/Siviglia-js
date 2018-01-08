<?php namespace lib\model\types;
class Decimal extends BaseType
{
    function setValue($val)
    {
        if($val===null || !isset($val))
        {
            $this->valueSet=false;
            $this->value=null;
        }
        else
        {
            $this->valueSet=true;
            $this->value=$val;
        }
    }
}
class DecimalMYSQLSerializer extends BaseTypeMYSQLSerializer
{
	function getSQLDefinition($name,$def)
	{
		$nDecimals=$def["NDECIMALS"];
		$nIntegers=$def["NINTEGERS"];
		return array("NAME"=>$name,"TYPE"=>"DECIMAL(".($nDecimals+$nIntegers).",".$nDecimals.")");
	}
}
class DecimalHTMLSerializer extends BaseTypeHTMLSerializer{}

?>