<?php
namespace lib\php;
class ArrayTools {

    static function isVector($var) { return !ArrayTools::isAssociative($var);}

    static function isAssociative($arr)
    {
        return (is_array($arr) && count(array_filter(array_keys($arr),'is_string')) == count($arr)); }


	static function array_compare($array1, $array2) {
		$diff = false;
		// Left-to-right
		foreach ($array1 as $key => $value) {
			if (!array_key_exists($key,$array2)) {
				$diff[0][$key] = $value;
			} elseif (is_array($value)) {
				if (!is_array($array2[$key])) {
                    $diff[0][$key] = $value;
                    $diff[1][$key] = $array2[$key];
             } else {
                    $new = array_compare($value, $array2[$key]);
                    if ($new !== false) {
                         if (isset($new[0])) $diff[0][$key] = $new[0];
                         if (isset($new[1])) $diff[1][$key] = $new[1];
                    };
				};
			} elseif ($array2[$key] !== $value) {
             $diff[0][$key] = $value;
             $diff[1][$key] = $array2[$key];
			};
	};
	// Right-to-left
	foreach ($array2 as $key => $value) {
        if (!array_key_exists($key,$array1)) {
             $diff[1][$key] = $value;
        };
        // No direct comparsion because matching keys were compared in the
        // left-to-right loop earlier, recursively.
	};
	return $diff;
	}

	static function merge(& $arr1, & $arr2)
	{
	    if($arr2===null)
	        return $arr1;
		foreach($arr2 as $key=>$value)
		{
			if(!$arr1[$key])
			{
				$arr1[$key]=$value;
				continue;
			}
			if(is_array($value))
				CArrayTools::merge($arr1[$key],$arr2[$key]);
			else
				$arr1[$key]=$value;
		}
	}


    static function recurse_dump_array(& $val,$nestLevel=0)
    {
        $incKeys=false;
        if(\lib\php\ArrayTools::isVector($val))
        {
            $nItems=count($val);
        }
        else
        {
            $incKeys=true;
            $keys=array_keys($val);
            $nItems=count($keys);
        }
        $nestLevel++;
        $text="array(";
        for($k=0;$k<$nItems;$k++)
        {
            if($k>0)
                $text.=",";
            $text.="[#NL#][#TB#]";
            if($incKeys)
             $text.="'".$keys[$k]."'=>";

         if($incKeys)
             $curVal=& $val[$keys[$k]];
         else
         {
             if(is_string($val))
                 print_r($val);
             $curVal=& $val[$k];
         }

         if(is_array($curVal))
         {
            $output=ArrayTools::recurse_dump_array($curVal,$nestLevel+1);
            if(strlen($output)<60)
            {
                $output=trim($output);
                $replaces=array("","");
            }
            else
            {
                $replaces=array("\n",str_repeat(" ",($nestLevel+1)*3));
            }
            $text.=str_replace(array("[#NL#]","[#TB#]"),$replaces,$output);
         }
         elseif(is_string($curVal))
            $text.="'".str_replace("'","\\'",$curVal)."'";
         else
         {
             if(!isset($curVal))
                 $text.="null";
             else
             {
                 if($curVal===false)
                     $text.="false";
                 else
                     $text.=$curVal;
             }
         }
     }
     $nestLevel--;
     return trim($text."[#NL#][#TB#])");
 }
 static function dumpArray($arr,$initialNestLevel=0)
 {

     return str_replace(array("[#NL#]","[#TB#]"),array("\n",str_repeat(" ",$initialNestLevel*3)),ArrayTools::recurse_dump_array($arr,$initialNestLevel));
 }
 static function compare($op1, $op2)
 {
    if (count($op1) < count($op2)) {
        return -1; // $op1 < $op2
    } elseif (count($op1) > count($op2)) {
        return 1; // $op1 > $op2
    }
    foreach ($op1 as $key => $val) {
        if (!array_key_exists($key, $op2)) {
            return null; // uncomparable
        } elseif ($val < $op2[$key]) {
            return -1;
        } elseif ($val > $op2[$key]) {
            return 1;
        }
    }
    return 0; // $op1 == $op2
 }

}

