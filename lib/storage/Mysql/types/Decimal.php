<?php namespace lib\storage\Mysql\types;

class Decimal extends BaseType
{
	function getSQLDefinition($name,$definition,$serializer)
	{
		$nDecimals=$definition["NDECIMALS"];
		$nIntegers=$definition["NINTEGERS"];
		return array("NAME"=>$name,"TYPE"=>"DECIMAL(".($nDecimals+$nIntegers).",".$nDecimals.")");
	}
}


?>
