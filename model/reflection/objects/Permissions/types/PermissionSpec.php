<?php


namespace model\reflection\Permissions\types;


class PermissionSpec extends \lib\model\types\_Array
{
    function __construct()
    {
        parent::__construct([
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
                                "LABEL"=>"Public",
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
                                "LABEL"=>"Public",
                                "TYPE"=>"String",
                                "FIXED"=>"Role"
                            ],
                            "ROLE"=>[
                                "LABEL" => "Role",
                                "TYPE" => "String",
                                "SOURCE" => [
                                    "TYPE" => "DataSource",
                                    "MODEL" => "/model/web/Permissions",
                                    "DATASOURCE" => "RoleList"
                                ]
                            ]
                        ]
                    ],
                    "ACL" => [
                        "LABEL" => "ACL",
                        "TYPE" => "Container",
                        "FIELDS" => [
                            "TYPE"=>[
                                "LABEL"=>"Public",
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
                                            "REQUIRES" => [
                                                "TYPE" => "String",
                                                "SOURCE" => [
                                                    "TYPE" => "DataSource",
                                                    "MODEL" => "/model/web/Permissions",
                                                    "DATASOURCE" => "ListItems",
                                                    "PARAMS" => [
                                                        "itemType" => 1
                                                    ]
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
                                            "REQUIRES" => [
                                                "TYPE" => "String",
                                                "SOURCE" => [
                                                    "TYPE" => "DataSource",
                                                    "MODEL" => "/model/web/Permissions",
                                                    "DATASOURCE" => "ListGroups",
                                                    "PARAMS" => [
                                                        "itemType" => 1
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
            ]
        ]);
    }
}