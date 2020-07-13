<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/02/2018
 * Time: 17:01
 */

namespace model\reflection\Model\autoui;

include_once(PROJECTPATH."/model/reflection/objects/base/AutoUIDefinition.php");

class Model extends \model\reflection\base\AutoUIDefinition
{
    static $definition = array(
        "DEFINITION" => array(
            "ROOT" => array(
                "TYPE" => "FORMCONTAINER",
                "LABEL" => "Configuracion",
                "GROUPS" => array(
                    "GENERAL"=>array(
                        "LABEL"=>"GENERAL",
                         "CONTENTS"=>array(
                            "ROLE" => array(
                                "TYPE" => "SELECTOR",
                                "LABEL" => "Role",
                                "VALUES"=>array("ENTITY","MULTIPLE_RELATION","PROPERTY")
                            ),
                            "TABLE"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Table"
                            ),
                            "LABEL"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Label"
                            ),
                            "SHORTLABEL"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Short Label"
                            ),
                            "CARDINALITY"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Cardinality"
                            ),
                            "CARDINALITY_TYPE"=>array(
                                "TYPE"=>"SELECTOR",
                                "LABEL"=>"Cardinality Type",
                                "VALUES"=>array("FIXED","VARIABLE")
                            ),
                            "DEFAULT_SERIALIZER"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Default Serializer"
                            ),
                            "DEFAULT_WRITE_SERIALIZER"=>array(
                                "TYPE"=>"STRING",
                                "LABEL"=>"Write Serializer"
                            )
                         )
                    ),
                    "FIELDS"=>array(
                        "LABEL"=>"Fields",
                        "CONTENTS"=>[
                            "FIELDS"=>"@@Model|Fields@@"
                        ]),
                    /*"ALIASES"=>"@@Model|Aliases@@",
                    "STORAGE"=>"@@Storage|ModelStorageOptions@@",
                    "STATES"=>"@@Model|States@@",
                    "PERMISSIONS"=>"@@Permissions|Permissions@@"*/
                )
            )
        )
    );
}
