<?php

namespace sites\reflection\routes\Definitions;


class Pages
{
    static $definition = array(
        "meta" => array("TYPE"=>"META"),
        "index" => array("TYPE" => "PAGE", "PAGE" => "index"),
        "Datasources"=>array("TYPE"=>"DATASOURCE"),
        "EditDefinition"=>array("TYPE"=>"PAGE","PAGE"=>"EditDefinition")

    );
}