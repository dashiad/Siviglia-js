<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 27/07/15
 * Time: 19:22
 */

namespace sites\reflection\routes\Urls\Reflection;


class Pages
{
    static $definition = array(
        "/ArrayDefinitions/{name}"=>"EditArrayDefinition",
        "/ArrayDefinitions"=>"EditArrayDefinition",
        "/ArrayDefinitions/ListAll"=>"ListArrayDefinitions",
        "/ArrayDefinitions/Get"=>"GetArrayDefinition",
        "/Namespace/{namespace}"=>"Namespaces",

    );
}