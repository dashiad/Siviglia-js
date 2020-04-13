<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 17/12/2017
 * Time: 12:22
 */

namespace sites\editor;
include_once(PROJECTPATH."/model/web/objects/Site/SiteConfig.php");


class Config extends \model\web\Site\SiteConfig
{
    static $definition=array(
        "DEFAULT_ISO"=>"es",
        "STATICS_SITE"=>"statics",
        "WIDGETPATH"=>array(
            "/sites/reflection/widgets/SKINS/GENTELELLA"
        ),
        "PERMISSIONS"=>[["TYPE"=>"Public"]],
        "services"=>array(
            "user"=>array(
                'REQUIRE_UNIQUE_EMAIL' => true,
                'PASSWORD_ENCODING' => 'BCRYPT', // PLAINTEXT, MD5
                'ATTEMPTS_BEFORE_LOCKOUT' => 0,
                'REQUIRE_ACCOUNT_VALIDATION' => false,
                'LOGIN_ON_CREATE' => true,
                'NOT_ALLOWED_NICKS' => ['*admin*']
            )
        )
    );
    function getDefinition()
    {
        return self::$definition;
    }
}