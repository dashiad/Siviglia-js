<?php
/* Copiar este fichero a /var/www/config, para que este en el path de inclusion de php, que esta definido en el nginx.conf */
ini_set('display_errors','On');
ini_set('html_errors','On');
define("PROJECTPATH","/var/www/adtopy/");
define("LIBPATH",PROJECTPATH."/lib");
$curServer=isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:"NoHost";
define("_WEBPATH_","http://".$curServer);
define("_COOKIE_KEY_",'nfxG9e88LHq5JWjNcnRV1orQPjaSs4QaRaVILf41FzusPPLn4pHJyJuQ');
define('_COOKIE_IV_', 'Bdno0qQD');
define("_COOKIE_DOMAIN_",$curServer);
define("DONT_ENCRYPT_COOKIE",1);

define("_DB_NAME_","adtopy");
/* BASE DE DATOS LOCAL */
define("_DB_USER_","root");
define("_DB_PASSWORD_","root");
#define("_DB_SERVER_","192.168.253.1");
//define("_DB_SERVER_","10.0.2.2");
define("_DB_SERVER_","192.168.99.1");

define("WEBLOCALPATH","/");

global $SERIALIZERS;

$SERIALIZERS=array(
    "default"=>array("NAME"=>"default","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>_DB_SERVER_,"user"=>_DB_USER_,"password"=>_DB_PASSWORD_,"database"=>array("NAME"=>_DB_NAME_))),
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
);
$SERIALIZERS["web"]=$SERIALIZERS["default"];
define("DEFAULT_NAMESPACE","adtopy");
global $APP_NAMESPACES;
$APP_NAMESPACES=array("adtopy","web");
define("DEFAULT_SERIALIZER","default");
define("DEVELOPMENT",1);
define("DEFAULT_LANGUAGE","es");
define("DEFAULT_LANGUAGE_ID",1);

/* DEFINICIONES DE EMAIL */
global $EMAILERS;
$EMAILERS=array(
    "default"=>array(
        "DOMAIN"=>"percentil.com",
        "MAIL_USER_NAME"=>"Percentil",
        "MAIL_SERVER"=>"mail1.alojamientotecnico.com",
        "MAIL_USER"=>"info@percentil.com",
        "MAIL_PASSWD"=>"923723iuydww",
        "MAIL_METHOD"=>"SMTP", //SMTP
        "USE_SMTP_ENCRIPTION"=>false,
        "SMTP_PORT"=>25,
        "MAIL_TYPE"=>1 // 1: HTML ,2 : TEXT, 3: Ambos
    )
);


$TESTS_SERIALIZERS=array(
    "default"=>array("NAME"=>"default","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"192.168.253.1","user"=>"root","password"=>"delea","database"=>array("NAME"=>"backoffice_ordertest"))),
);
$TESTS_MYSQLPATH='/usr/bin/mysql';
?>

