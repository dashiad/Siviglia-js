<?php
/* Copiar este fichero a /var/www/config, para que este en el path de inclusion de php, que esta definido en el nginx.conf */
ini_set('display_errors','On');
ini_set('html_errors','On');
define("PROJECTPATH",__DIR__."/../../");
define("LIBPATH",PROJECTPATH."/lib/");
$curServer=isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:"NoHost";
define("_WEBPATH_","http://".$curServer);
define("_COOKIE_KEY_",'nfxG9e88LHq5JWjNcnRV1orQPjaSs4QaRaVILf41FzusPPLn4pHJyJuQ');
define('_COOKIE_IV_', 'Bdno0qQD');
define("_COOKIE_DOMAIN_",$curServer);
define("DONT_ENCRYPT_COOKIE",1);
define("WEBLOCALPATH","/");


global $Config;
$Config=[
    "PACKAGES"=>[
        "ads"=>["path"=>'',"namespace"=>'\model\ads'],
        "backoffice"=>["path"=>'',"namespace"=>'\model\backoffice'],
        "reflection"=>["path"=>'',"namespace"=>'\model\reflection'],
        "web"=>["path"=>'',"namespace"=>'\model\web']
    ],

     "SERIALIZERS"=>array(
         "default"=>array("NAME"=>"default","TYPE"=>"Mysql","ADDRESS"=>array("host"=>"editor.adtopy.com","user"=>"adtopy","password"=>"adtopy","database"=>"modeltests")),
         "web"=>array("NAME"=>"web","TYPE"=>"Mysql","ADDRESS"=>array("host"=>"editor.adtopy.com","user"=>"adtopy","password"=>"adtopy","database"=>"adtopy")),
         "test"=>array("NAME"=>"test","TYPE"=>"Mysql","ADDRESS"=>array("host"=>"editor.adtopy.com","user"=>"adtopy","password"=>"adtopy","database"=>"test")),
         "modeltests"=>array("NAME"=>"test","TYPE"=>"Mysql","ADDRESS"=>array("host"=>"editor.adtopy.com","user"=>"adtopy","password"=>"adtopy","database"=>"modeltests")),
         "comscore"=>["NAME"=>"Comscore","MODEL"=>"/model/web/Comscore","CLASS"=>"ComscoreSerializer"],
        "es"=>["NAME"=>"MAIN_ES","TYPE"=>"ES","ES"=>["servers"=>["127.0.0.1"],"port"=>9200,"index"=>"testIndex"]],
        "cookie"=>array(
            "NAME"=>"cookie",
            "TYPE"=>"Cookie",
            "ADDRESS"=>array(
            "NAME"=>"psCookie",
            "PATH"=>"/",
            "DOMAIN"=>"",
            //"EXPIRES"=>"1000",
            "CYPHER"=>array("TYPE"=>"Blowfish", "KEY"=>_COOKIE_KEY_,"IV"=>_COOKIE_IV_),
            "CHECKSUM"=>1,
            "FIELDSEPARATOR"=>'¤',
            "KEYSEPARATOR"=>"|",
            "SECURE"=>0)

        )
    ),
    "DEFAULT_SITE"=>"reflection",
    "DEFAULT_SERIALIZER"=>"default",
    "DEVELOPMENT"=>1,
    "DEFAULT_LANGUAGE"=>"es",
    "DEFAULT_LANGUAGE_ID"=>1

];


global $APP_NAMESPACES;
$APP_NAMESPACES=array("adtopy","web");

define("DEVELOPMENT",1);
define("DEFAULT_LANGUAGE","es");
define("DEFAULT_LANGUAGE_ID",1);
