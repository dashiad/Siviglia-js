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
        "services"=>array(
            "user"=>array(
                'REQUIRE_UNIQUE_EMAIL' => true,
                'PASSWORD_ENCODING' => 'BCRYPT', // PLAINTEXT, MD5
                'ATTEMPTS_BEFORE_LOCKOUT' => 0,
                'REQUIRE_ACCOUNT_VALIDATION' => false,
                'LOGIN_ON_CREATE' => true,
                'NOT_ALLOWED_NICKS' => ['*admin*']
            ),
            "permissions"=>array(
                "axos"=>array(
                    "Sites"=>
                        array("Pages"=>array("Widget","Properties")
                        ),
                    "Translations"
                ),

                "acos"=>array(
                    "Site"=>array("create","edit","destroy","view"),
                    "Page"=>array("create","edit","destroy","view"),
                    "Translations"=>array("create","edit","destroy","view")
                ),

                "aros"=>array(
                    "Workers"=>array("Departments"=>
                        array("CEO"=>"Alberto",
                            "marketing"=>array("Ana","Luis","Pablo"),
                            "finances"=>array(
                                "accounting"=>array("Juan","Antonio"),
                                "assessment"=>array("Juan","Pedro")
                            ),
                            "projects"=>array("Luisa","Manuel"),
                            "secretary"=>array(
                                "executives"=>array("Pilar"),
                                "staff"=>array("Mercedes"),
                                "Marivi")
                        )),
                    "Providers"=>array("Uralita","Pilar","Luisa"),
                    "Clients"=>array(
                        "golden"=>array("Corte_ingles","Repsol","Pilar"),
                        "silver"=>array("Fnac",
                            "normal"=>array("Marivi"),
                            "pro"=>array("Abengoa")),
                        "Renfe",
                        "Tussam"
                    )

                )
            )
        )
    );
    function getDefinition()
    {
        return self::$definition;
    }
}