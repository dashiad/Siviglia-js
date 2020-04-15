<?php

namespace sites\reflection\routes\Definitions;


class Pages
{
    static $definition = array(
        "meta" => array("TYPE"=>"META"),
        "index" => array("TYPE" => "PAGE", "PAGE" => "Index"),
        "Datasources"=>array("TYPE"=>"DATASOURCE"),
        "login" => array("TYPE" => "PAGE", "PAGE" => "Login")
    );
}