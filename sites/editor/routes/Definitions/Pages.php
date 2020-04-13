<?php

namespace sites\editor\routes\Definitions;


class Pages
{
    static $definition = array(
        "index" => array("TYPE" => "PAGE", "PAGE" => "index"),
        "page"=>array("TYPE"=>"PAGE","PAGE"=>"Page"),
        "error"=>array("TYPE"=>"PAGE","PAGE"=>"error"),
        "site"=>array("TYPE"=>"PAGE","PAGE"=>"site"),
        "login" => array("TYPE" => "PAGE", "PAGE" => "Login")
    );
}
