<?php

namespace sites\editor\routes\Definitions;


class Pages
{
    static $definition = array(
        "index" => array("TYPE" => "PAGE", "PAGE" => "index"),
        "editor"=>array("TYPE"=>"PAGE","PAGE"=>"editor"),
        "error"=>array("TYPE"=>"PAGE","PAGE"=>"error"),
        "site"=>array("TYPE"=>"PAGE","PAGE"=>"site")
    );
}