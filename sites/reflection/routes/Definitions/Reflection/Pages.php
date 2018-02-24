<?php

namespace sites\reflection\routes\Definitions\Reflection;


class Pages
{
    static $definition = array(

        "EditArrayDefinition"=>array("TYPE"=>"PAGE","PAGE"=>"Reflection/ArrayDefinitions"),
        "Namespaces"=>array("TYPE"=>"PAGE","PAGE"=>"Reflection/Namespaces"),
        "GetArrayDefinition"=>array("TYPE"=>"DATASOURCE"),
        "ListArrayDefinitions"=>array("TYPE"=>"DATASOURCE")
    );
}