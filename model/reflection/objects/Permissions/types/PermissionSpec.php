<?php


namespace model\reflection\Permissions\types;


class PermissionSpec extends \lib\model\types\_Array
{
    function __construct($name, $parent=null, $value=null, $validationMode=null)
    {
        parent::__construct($name,[
            "LABEL" => "Permisos",
            "TYPE" => "Array",
            "ELEMENTS" => [
                "LABEL" => "Tipo de especificacion",
                "TYPE" => "TypeSwitcher",
                "TYPE_FIELD" => "TYPE",
                "ALLOWED_TYPES" => [
                    "Public" => [
                        "LABEL" => "Public",
                        "TYPE" => "Container",
                        "FIELDS" => [
                            "TYPE"=>[
                                "LABEL"=>"Public",
                                "TYPE"=>"String",
                                "FIXED"=>"Public"
                            ]
                        ]
                    ],
                    "Owner" => [
                        "LABEL" => "Owner",
                        "TYPE" => "Container",
                        "FIELDS" => [
                            "TYPE"=>[
                                "LABEL"=>"Public",
                                "TYPE"=>"String",
                                "FIXED"=>"Owner"
                            ]
                        ]
                    ],
                    "Logged" => [
                        "LABEL" => "Logged",
                        "TYPE" => "Container",
                        "FIELDS" => [
                            "TYPE"=>[
                                "LABEL"=>"Logged",
                                "TYPE"=>"String",
                                "FIXED"=>"Logged"
                            ]
                        ]
                    ],
                    "Role" => [
                        "LABEL"=>"Role-based",
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "TYPE"=>[
                                "LABEL"=>"Role",
                                "TYPE"=>"String",
                                "FIXED"=>"Role"
                            ],
                            "ROLE"=>[
                                "LABEL" => "Role",
                                "TYPE" => "String",
                                "SOURCE" => [
                                    "TYPE" => "DataSource",
                                    "MODEL" => "/model/web/Permissions",
                                    "DATASOURCE" => "ListRoles",
                                    "LABEL"=>"group_charPath",
                                    "VALUE"=>"group_charPath"

                                ]
                            ]
                        ]
                    ],
                    "ACL" => [
                        "LABEL" => "ACL",
                        "TYPE" => "Container",
                        "FIELDS" => [
                            "TYPE"=>[
                                "LABEL"=>"ACL",
                                "TYPE"=>"String",
                                "FIXED"=>"ACL"
                            ],
                            "REQUIRES" => [
                                "LABEL" => "Requiere",
                                "TYPE" => "TypeSwitcher",
                                "IMPLICIT_TYPE" => "ITEM",
                                "TYPE_FIELD" => "TYPE",
                                "ALLOWED_TYPES" => [
                                    "ITEM" => [
                                        "TYPE" => "Container",
                                        "LABEL" => "Permisos (Item)",
                                        "FIELDS" => [
                                            "TYPE"=>[
                                                "LABEL"=>"Tipo de permiso",
                                                "TYPE"=>"String",
                                                "FIXED"=>"ITEM"
                                            ],
                                            "ITEM" => [
                                                "TYPE" => "String",
                                                "SOURCE" => [
                                                    "TYPE" => "DataSource",
                                                    "MODEL" => "/model/web/Permissions",
                                                    "DATASOURCE" => "ListItems",
                                                    "PARAMS" => [
                                                        "item_type" => 1
                                                    ],
                                                    "LABEL"=>"item_value",
                                                    "VALUE"=>"item_value"
                                                ]
                                            ]
                                        ]
                                    ],
                                    "GROUP" => [
                                        "TYPE" => "Container",
                                        "LABEL" => "Permisos (Grupo)",
                                        "FIELDS" => [
                                            "TYPE"=>[
                                                "LABEL"=>"Tipo de permiso",
                                                "TYPE"=>"String",
                                                "FIXED"=>"GROUP"
                                            ],
                                            "GROUP" => [
                                                "TYPE" => "String",
                                                "SOURCE" => [
                                                    "TYPE" => "DataSource",
                                                    "MODEL" => "/model/web/Permissions",
                                                    "DATASOURCE" => "ListGroups",
                                                    "PARAMS" => [
                                                        "group_type" => 1
                                                    ],
                                                    "LABEL"=>"group_charPath",
                                                    "VALUE"=>"group_charPath"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],$parent, $value,$validationMode);
    }
}