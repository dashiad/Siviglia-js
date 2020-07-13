<?php namespace lib\storage\ES\types;

class Decimal extends BaseType
{
	function getSQLDefinition($name,$definition,$serializer)
	{
		$nDecimals=$definition["NDECIMALS"];
		$nIntegers=$definition["NINTEGERS"];
		return array("NAME"=>$name,"TYPE"=>["type"=>"float"]);
	}
}


?>
