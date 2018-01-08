<?php
namespace {

    include_once(LIBPATH."/php/debug/Kint.class.php");

    function debug($obj,$trace=false,$exit=false)
    {        
        ob_start();
        Kint::dump($obj);
        if($trace)Kint::trace();
        $bug=ob_get_clean();
        echo $bug;
        if($exit)exit();
        
    }
    function debug_plain($obj,$trace=false)
    {
        Kint::dump($obj);
        if($trace)Kint::trace();
    }
    function _d($obj,$trace=false)
    {
        debug_plain($obj,$trace);
    }
    function _d2($text)
    {
        $op=fopen(PROJECTPATH."/debug.php","a");
        fwrite($op,$text."\n");
        fclose($op);
    }
    function debug_trace()
    {
        ob_start();
        Kint::trace();
        $bug=ob_get_clean();
        Console::log($bug);
    }
    function recurse_debug_backtrace($input,& $output,$curLevel,$maxLevel)
    {

        foreach($input as $key=>$value)
        {
            if(is_array($value))
            {
                if(($curLevel+1) >= $maxLevel)
                    $output[$key]="[MAX_LEVEL]";
                else
                    recurse_debug_backtrace($value,$output[$key],$curLevel+1,$maxLevel);
            }
            else
            {
                if(is_object($value))
                {
                    $output[$key]="<OBJECT>";
                }
                else
                $output[$key]=$value;
            }
        }
    }

    function clean_debug_backtrace($level=3)
    {
        echo "<pre>";
        $data=debug_backtrace();
        $result=array();
        foreach($data as $key=>$value)        
            recurse_debug_backtrace($value,$result[$key],0,$level);
        
        print_r($result);
        echo "</pre>";

    }
    function debug_trace_plain()
    {
        Kint::trace();
    }
    function skip_debug($data)
    {
        if(strtolower( strtolower($data['function']) === strtolower( "debug" ) ||
                    strtolower($data['function'] ) === strtolower( "debug_trace" )) ||
                    strtolower($data['class'])=='kint')
           
            return null;
        return $data;

    }
}




?>