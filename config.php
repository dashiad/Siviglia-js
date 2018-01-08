<?php
define("PROJECTNAME","Backoffice");
define("PROJECT_ENVIRONMENT","percentil"); //Posible values: kirondo percentil
define("PROJECTPATH",realpath(CUSTOMPATH."../backoffice")."/");
define("WEBROOT",PROJECTPATH."/html/Application/");
define("LIBPATH",PROJECTPATH."lib/");
define("WEBPATH","http://backoffice.com/");
define("WEBLOCALPATH","Application/");
define("INTERNAL_WEBPATH","http://percentil.com/backoffice/");

//define("CURRENT_SITE",1);
global $SERIALIZERS;
$SERIALIZERS=array(
    "backoffice"=>array("NAME"=>"backoffice","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"mysql-master","user"=>"us_percentil","password"=>"BnO4Lbvc","database"=>array("NAME"=>"backoffice"))),
    "default"=>array("NAME"=>"default","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"mysql-master","user"=>"us_percentil","password"=>"BnO4Lbvc","database"=>array("NAME"=>"bdpercentil"))),
);
define("DEFAULT_NAMESPACE","backoffice");
global $APP_NAMESPACES;
$APP_NAMESPACES=array("backoffice");
define("DEFAULT_SERIALIZER","backoffice");
//define("DEVELOPMENT",1);
//define("DEVELOPMENT_EMAIL","esteban@percentil.com");
define("DEFAULT_LANGUAGE","percentil");
if (!defined("IS_FRONT"))
{
    define("DEFAULT_LANGUAGE_ID",3);
}


global $EMAILERS;
$EMAILERS=array(
    "default"=>array(
        "MAIL_DOMAIN"=>"percentil.com",
        "MAIL_USER_NAME"=>"Percentil",
        "MAIL_SERVER"=>"mail1.alojamientotecnico.com",
        "MAIL_USER"=>"info@percentil.com",
        "MAIL_PASSWD"=>"923723iuydww",
        //"USE_SMTP_ENCRIPTION"=>"off",
        "SMTP_PORT"=>25,
        "MAIL_METHOD"=>"SMTP", //SMTP
        "MAIL_TYPE"=>1
    ),
    "contactoweb"=>array(
        "MAIL_DOMAIN"=>"percentil.com",
        "MAIL_USER_NAME"=>"Percentil",
        "MAIL_SERVER"=>"mail1.alojamientotecnico.com",
        "MAIL_USER"=>"contacto-web@percentil.com",
        "MAIL_PASSWD"=>"kkWPkunmRff3ZG6D",
        //"USE_SMTP_ENCRIPTION"=>"off",
        "SMTP_PORT"=>25,
        "MAIL_METHOD"=>"SMTP", //SMTP
        "MAIL_TYPE"=>1
    ),
    "acumba"=>array(
        "DOMAIN"=>"percentil.com",
        "MAIL_USER_NAME"=>"Percentil",
        "MAIL_SERVER"=>"smtp.acumbamail.com",
        "MAIL_USER"=>"info@percentil.com",
        "MAIL_PASSWD"=>"PXG3G1wK",
        "MAIL_METHOD"=>"SMTP", //SMTP
        "USE_SMTP_ENCRIPTION"=>false,
        "SMTP_PORT"=>25,
        "MAIL_TYPE"=>1 // 1: HTML ,2 : TEXT, 3: Ambos
    )
);

//define("DEVELOPMENT_CC_EMAIL", "rafa@percentil.com");

//Esta variable controla si realmente se envían los emails
global $ALLOW_OUTBOUND_EMAILS;
$ALLOW_OUTBOUND_EMAILS = true;

/** DEFINICIONES EXTRA */
define("CURRENT_SITE",1);
define("BACKOFFICE_PHOTO_TEMPDIR",'c:\\\\tmp');
define("BACKOFFICE_PHOTO_SERVERDIR","/mnt/foto/");
define("BACKOFFICE_PHOTO_TMPDIR","/tmp");
define("BACKOFFICE_PHOTO_FTPHOST","ftp.percentil.com");
define("BACKOFFICE_PHOTO_FTPUSER","p3rc3nt1l");
define("BACKOFFICE_PHOTO_FTPPASS","qSk*cnhyqBV&.~dB");
define("BACKOFFICE_PHOTO_FTPDIR","/img/p/");
define("BACKOFFICE_PHOTO_PHOTOSERVER","Y:\\\\");

/** DEFINICIONES RELACIONADAS CON PRESTASHOP */
define("PRESTASHOP_DEFAULT_CATEGORY",201);
define("DEFAULT_BAG_RETURN_PENALTY",6);
define("DEFAULT_BAG_RECLAIM_DAYS",5);
define("DEFAULT_LOST_PRODUCT_PRICE",2.0);
define("DEFAULT_FREE_BAG_RETURN_ORDER_AMOUNT",20);
define("DEFAULT_ADJUST_DISCOUNT",15);
define("DEFAULT_ORDERRETURN_DAYS",30);
define("PRESTASHOP_SITE","http://percentil.com");
define("_PRESTASHOP_KEY_","ab7aefe88cfbf185814a0fd72c02f806");
define("_COOKIE_KEY_",'nfxG9e88LHq5JWjNcnRV1orQPjaSs4QaRaVILf41FzusPPLn4pHJyJuQ');


define("KIALA_USERNAME","ES-PERCENTIL-Admin");
define("KIALA_DSPID","34600160");
define("CELERITAS_USERNAME","percentil");
define("CELERITAS_PASSWORD","PSA7z0pEr0sSwkDQ");

define("DEFAULT_CURRENCY_ISO_CODE","EUR");

/*
define("_PAYPAL_API_PASSWORD_","PGXTLE2V2MYTH4KK");
define("_PAYPAL_API_SIGNATURE_","AFcWxV21C7fd0v3bYYYRCpSSRl31AZDXyDC2e2wnFUFaYnzmFnqXbxlu");
define("_PAYPAL_API_USER_","info_api1.percentil.com");
define("_PAYPAL_BUSINESS_","info@percentil.com");
define("_PAYPAL_CAPTURE_","0");
define("_PAYPAL_DEBUG_MODE_","0");
define("_PAYPAL_EXPRESS_CHECKOUT_","1");
define("_PAYPAL_HEADER_","");
define("_PAYPAL_MODE_DEBUG_","0");
define("_PAYPAL_NEW_","1");
define("_PAYPAL_OS_AUTHORIZATION_","13");
define("_PAYPAL_PAYMENT_METHOD_","0");
define("_PAYPAL_SANDBOX_","0");
define("_PAYPAL_LOCAL_SANDBOX_","1");
//define("_PAYPAL_LOCAL_SANDBOX_URL_","www.mipaypal.com");
//define("_PAYPAL_LOCAL_SANDBOX_API_URL_","api-3t.mipaypal.com");
define("_PAYPAL_LOCAL_SANDBOX_URL_","www.paypal.com");
define("_PAYPAL_LOCAL_SANDBOX_API_URL_","api-3t.paypal.com");
define("_PAYPAL_TEMPLATE_","B");
define("_PAYPAL_NOTIFICATION_ENDPOINT_",_WEBPATH_."/payment/paypal/notification");
define("_PAYPAL_CANCEL_ENDPOINT_",_WEBPATH_."/payment/paypal/cancel");
define("_PAYPAL_RESULT_ENDPOINT_",_WEBPATH_."/payment/paypal/result");
*/

/** DEFINICIONES RELACIONADAS CON EL SISTEMA DE TESTING **/
$TESTS_SERIALIZERS=array(
    "backoffice"=>array("NAME"=>"backoffice","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"127.0.0.1","user"=>"rafa","password"=>"unrandom","database"=>array("NAME"=>"backoffice_test"))),
    "default"=>array("NAME"=>"default","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"127.0.0.1","user"=>"rafa","password"=>"unrandom","database"=>array("NAME"=>"bdpercentil_test"))),
    "default"=>array("NAME"=>"default","TYPE"=>"MYSQL","ADDRESS"=>array("host"=>"127.0.0.1","user"=>"rafa","password"=>"unrandom","database"=>array("NAME"=>"bdpercentil_test"))),
);

$TESTS_MYSQLPATH='/usr/local/bin/mysql';
