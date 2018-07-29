<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/02/2018
 * Time: 17:01
 */

namespace model\reflection\Model\autoui;

include_once(PROJECTPATH."/model/reflection/objects/base/AutoUIDefinition.php");
class Types extends \model\reflection\base\AutoUIDefinition
{
    static $definition = array(
        "DEFINITION" => array(
            "DataTypeSwitcher"=>[
                "TYPE"=>"TYPESWITCH",
                "LABEL"=>"Tipo",
                "TYPE_FIELD"=>"TYPE",
                "ALLOWED_TYPE_DEFINITIONS"=>[

                "_String"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"String",
                "FIELDS"=>array(
                    "MINLENGTH"=>array(
                        "LABEL"=>"Min length",
                        "TYPE"=>"INTEGER",
                        "MIN"=>0,
                        "HELP"=>"Min Length required for this field"
                    ),
                    "MAXLENGTH"=>array(
                        "LABEL"=>"Max length",
                        "TYPE"=>"INTEGER",
                        "MIN"=>0,
                        "HELP"=>"Max length of this field",
                        "REQUIRES"=>[
                            ["TYPE"=>"ERROR","/MAXLENGTH < /MINLENGTH"]
                        ]
                    ),
                    "REGEXP"=>array(
                        "LABEL"=>"Regular Expression",
                        "TYPE"=>"STRING",
                        "HELP"=>"Regular Expression for field validation"
                    ),
                    "TRIM"=>array(
                        "LABEL"=>"Trim",
                        "TYPE"=>"BOOLEAN",
                        "HELP"=>"Trim values"
                    ),
                    "DEFAULT"=>[
                        "LABEL"=>"Default value",
                        "TYPE"=>"STRING"
                    ]
                )
            ],
                "ArrayType"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"Array Type",
                "FIELDS"=>[
                    "ELEMENTS"=>[
                        "LABEL"=>"Elements",
                        "TYPE"=>"STRING",
                        "HELP"=>"Type of the elements contained in the array.This must be another type.",
                        "REQUIRED"=>true
                    ]
                ]
            ],
                "AutoIncrement"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"Auto Increment",
                "FIELDS"=>[]
            ],
                "BankAccount"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"Bank Account",
                "FIELDS"=>[
                    "DEFAULT"=>[
                        "LABEL"=>"Default",
                        "TYPE"=>"STRING"
                    ]
                ]
            ],
                "Boolean"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"Boolean",
                "FIELDS"=>[
                    "DEFAULT"=>["TYPE"=>"BOOLEAN","LABEL"=>"Default"]
                ]
            ],
                "City"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"City",
                "FIELDS"=>[
                    "DEFAULT"=>[
                        "LABEL"=>"Default",
                        "TYPE"=>"STRING"
                    ]
                ]
            ],
                "Color"=>[
                "TYPE"=>"CONTAINER",
                "LABEL"=>"Color",
                "FIELDS"=>[
                    "DEFAULT"=>[
                        "LABEL"=>"Default",
                        "TYPE"=>"STRING"
                    ]
                ]
            ],
                "Composite"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Composite Type",
                    "HELP"=>"Type composed of several subtypes.",
                    "FIELDS"=>[
                        "FIELDS"=>[
                            "TYPE"=>"DICTIONARY",
                            "LABEL"=>"Fields",
                            "VALUETYPE"=>"DataTypeSwitcher"
                        ]
                    ]
                ],
                "Date"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Date",
                    "FIELDS"=>[
                        "STARTYEAR"=>[
                            "TYPE"=>"INTEGER",
                            "LABEL"=>"Min year"
                        ],
                        "ENDYEAR"=>[
                            "TYPE"=>"INTEGER",
                            "LABEL"=>"Max year"
                        ],
                        "STRICTLYPAST"=>[
                            "TYPE"=>"BOOLEAN",
                            "LABEL"=>"Date in the past"
                        ],
                        "STRICTLYFUTURE"=>[
                            "TYPE"=>"BOOLEAN",
                            "LABEL"=>"Date in the future"
                        ],
                        "TIMEZONE"=>[
                            "TYPE"=>"SELECTOR",
                            "LABEL"=>"Timezone",
                            "VALUES"=>["UTC","SERVER","CLIENT"]
                        ],
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default",
                            "HELP"=>"In YYYY-DD-MM format, or 'NOW' to set it as the date when the type is created"
                        ]
                    ]
                ],
                "DateTime"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Date",
                    "FIELDS"=>[
                        "STARTYEAR"=>[
                            "TYPE"=>"INTEGER",
                            "LABEL"=>"Min year"
                        ],
                        "ENDYEAR"=>[
                            "TYPE"=>"INTEGER",
                            "LABEL"=>"Max year"
                        ],
                        "STRICTLYPAST"=>[
                            "TYPE"=>"BOOLEAN",
                            "LABEL"=>"Date in the past"
                        ],
                        "STRICTLYFUTURE"=>[
                            "TYPE"=>"BOOLEAN",
                            "LABEL"=>"Date in the future"
                        ],
                        "TIMEZONE"=>[
                            "TYPE"=>"SELECTOR",
                            "LABEL"=>"Timezone",
                            "VALUES"=>["UTC","SERVER","CLIENT"]
                        ],
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default",
                            "HELP"=>"In YYYY-DD-MM format, or 'NOW' to set it as the date when the type is created"
                        ]
                    ]
                ],
                "Decimal"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Decimal",
                    "FIELDS"=>[
                        "NINTEGERS"=>[
                            "LABEL"=>"Number of integers",
                            "TYPE"=>"INTEGER",
                            "REQUIRED"=>true,
                            "HELP"=>"Max number of digits before the decimal point"
                        ],
                        "NDECIMALS"=>[
                            "LABEL"=>"Number of decimals",
                            "TYPE"=>"INTEGER",
                            "REQUIRED"=>true,
                            "HELP"=>"Max number of digits after the decimal point"
                        ],
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"STRING"
                            ]
                    ]
                ],
                "Email"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Email",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"STRING"
                        ]
                    ]
                ],
                "Enum"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Enum",
                    "FIELDS"=>[
                        "VALUES"=>[
                            "LABEL"=>"Values",
                            "TYPE"=>"ARRAY"
                        ],
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"SELECTOR",
                            "SOURCE"=>"../VALUES"
                        ]
                    ]
                ],
                "File"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"File",
                    "FIELDS"=>[
                        "MINSIZE"=>[
                            "LABEL"=>"Min. size",
                            "TYPE"=>"INTEGER",
                            "HELP"=>"Min required size for the file"
                        ],
                        "MAXSIZE"=>[
                            "LABEL"=>"Max. size",
                            "TYPE"=>"INTEGER",
                            "HELP"=>"Max required size for the file"
                        ],
                        "EXTENSIONS"=>[
                            "LABEL"=>"Allowed extensions",
                            "TYPE"=>"ARRAY"
                        ],
                        "TARGET_FILENAME"=>[
                            "LABEL"=>"Target file Name",
                            "TYPE"=>"String"
                        ],
                        "TARGET_FILEPATH"=>[
                            "LABEL"=>"Target file Path",
                            "TYPE"=>"String",
                            "HELP"=>"Uses parseString method to generate the final path"

                        ],
                        "PATHTYPE"=>[
                            "LABEL"=>"Path type",
                            "TYPE"=>"SELECTOR",
                            "VALUES"=>["ABSOLUTE","RELATIVE"],
                            "HELP"=>"Absolute or relative to the PROJECTPATH variable",
                            "DEFAULT"=>"ABSOLUTE"
                        ],
                        "AUTODELETE"=>[
                            "LABEL"=>"Auto delete",
                            "TYPE"=>"BOOLEAN",
                            "HELP"=>"Delete the physical file when the type is set to null"
                        ]
                    ]
                ],
                "Image"=> [
                        "TYPE"=>"CONTAINER",
                        "LABEL"=>"Image",
                        "FIELDS"=>[
                            "EXTENSIONS"=>[
                                "LABEL"=>"Allowed extensions",
                                "TYPE"=>"ARRAY",
                                "DEFAULT"=>["jpg","gif","png"]
                            ],
                            "MINWIDTH"=>[
                                "LABEL"=>"Min width",
                                "TYPE"=>"INTEGER"
                            ],
                            "MAXWIDTH"=>[
                                "LABEL"=>"Max width",
                                "TYPE"=>"INTEGER"
                            ],
                            "MINHEIGHT"=>[
                                "LABEL"=>"Min height",
                                "TYPE"=>"INTEGER"
                            ],
                            "MAXHEIGHT"=>[
                                "LABEL"=>"Max height",
                                "TYPE"=>"INTEGER"
                            ],
                            "THUBNAIL"=>[
                                "TYPE"=>"CONTAINER",
                                "LABEL"=>"Thumbnail",
                                "FIELDS"=>[
                                    "WIDTH"=>[
                                        "TYPE"=>"INTEGER",
                                        "LABEL"=>"Width"
                                    ],
                                    "HEIGHT"=>[
                                        "TYPE"=>"INTEGER",
                                        "LABEL"=>"Height"
                                    ],
                                    "KEEPASPECT"=>[
                                        "TYPE"=>"BOOLEAN",
                                        "LABEL"=>"Keep aspect ratio"
                                    ],
                                    "PREFIX"=>[
                                        "TYPE"=>"STRING",
                                        "LABEL"=>"Thumbnail file prefix"
                                    ],
                                    "QUALITY"=>[
                                        "TYPE"=>"INTEGER",
                                        "LABEL"=>"Thumbnail quality"
                                    ]
                                ]
                            ],
                            "WATERMARK"=>[
                                "TYPE"=>"CONTAINER",
                                "LABEL"=>"Watermark",
                                "FIELDS"=>[
                                    "FILE"=>[
                                        "TYPE"=>"STRING",
                                        "LABEL"=>"File",
                                        "HELP"=>"File containing the watermark"
                                    ],
                                    "POSITION"=>[
                                        "TYPE"=>"SELECTOR",
                                        "LABEL"=>"Position",
                                        "VALUES"=>[
                                            "NW","NE","SE","SW","CENTER"
                                        ]
                                    ],
                                    "OFFSETX"=>[
                                        "TYPE"=>"INTEGER",
                                        "LABEL"=>"Offset X"
                                    ],
                                    "OFFSETY"=>[
                                        "TYPE"=>"INTEGER",
                                        "LABEL"=>"Offset Y"
                                    ]
                                ]
                            ]
                        ]
                    ],
                "Integer"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Integer",
                    "FIELDS"=>[
                        "MIN"=>[
                            "LABEL"=>"Min value",
                            "TYPE"=>"INTEGER"
                        ],
                        "MAX"=>[
                            "LABEL"=>"Max value",
                            "TYPE"=>"INTEGER"
                        ],
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"INTEGER"
                        ]
                    ]
                ],
                "IP"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Ip",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default"
                        ]
                    ]
                ],
                "Label"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Label",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default"
                        ]
                    ]
                ],
                "Link"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Link",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default"
                        ]
                    ]
                ],
                "Login"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Login",
                    "FIELDS"=>[

                    ]
                ],
                "Money"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Money",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "TYPE"=>"STRING",
                            "LABEL"=>"Default"
                        ]
                    ]
                ],
                "Name"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Name",
                    "FIELDS"=>[

                    ]
                ],
                "NIF"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"NIF",
                    "FIELDS"=>[]
                ],
                "Password"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Password",
                    "FIELDS"=>[
                        "PASSWORD_ENCODING"=>[
                            "TYPE"=>"SELECTOR",
                            "LABEL"=>"Crypt method",
                            "VALUES"=>["PLAINTEXT","BCRYPT","ARGON2I"],
                        ],
                        "COST"=>[
                            "TYPE"=>"INTEGER",
                            "LABEL"=>"Cost",
                            "DEFAULT"=>10
                        ]
                    ]
                ],
                "Phone"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Phone",
                    "FIELDS"=>[]
                ],
                "PHPVariable"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"PHP Variable",
                    "FIELDS"=>[
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"STRINNG"
                        ]
                    ]
                ],
                "Relationship"=>[
                    "CUSTOMTYPE"=>"Relationship"
                ],
                "State"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"State",
                    "FIELDS"=>[
                        "VALUES"=>[
                            "LABEL"=>"Values",
                            "TYPE"=>"ARRAY"
                        ],
                        "DEFAULT"=>[
                            "LABEL"=>"Default",
                            "TYPE"=>"STRING",
                            "SOURCE"=>"../VALUES"
                        ]
                    ]
                ],
                "Street"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Street",
                    "FIELDS"=>[]
                ],
                "Text"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Text",
                    "FIELDS"=>[]
                ],
                "Timestamp"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Timestamp",
                    "FIELDS"=>[]
                ],
                "TreePath"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Tree Path",
                    "HELP"=>"Represents the sequence of parent element ids in a tree-like structure",
                    "CONTENTS"=>[]
                ],
                "UserId"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"User Id",
                    "CONTENTS"=>[]
                ],
                "UUID"=>[
                    "TYPE"=>"CONTAINER",
                    "LABEL"=>"Unique ID",
                    "CONTENTS"=>[]
                ]



            ]],


        )
    );

}
