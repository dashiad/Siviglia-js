<?php


namespace model\reflection\Model\types;

class ModelType extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null)
    {
        parent::__construct($name,[
            "TYPE"=>"Container",
            "FIELDS" => [
                "ROLE" => [
                    "LABEL" => "Role",
                    "TYPE" => "String",
                    "MAXLENGTH" => 40,
                    "HELP" => "Rol del Modelo",
                    "REQUIRED" => true,
                    "SOURCE" => [
                        "TYPE" => "Array",
                        "DATA" => [
                            ["Id" =>1,"Label"=>"ENTITY", "VALUE" => "ENTITY"],
                            ["Id"=>2,"Label" => "PROPERTY", "VALUE" => "PROPERTY"],
                            ["Id" =>3,"Label"=> "MULTIPLE_RELATIONSHIP", "VALUE" => "MULTIPLE_RELATIONSHIP"],
                        ],
                        "LABEL" => "Label",
                        "VALUE" => "Label"
                    ],
                    "DEFAULT" => "ENTITY"
                ],
                "DEFAULT_SERIALIZER" => [
                    "LABEL" => "Serializador por defecto",
                    "TYPE" => "String",
                    "MAXLENGTH" => 40,
                    "HELP" => "Serializador por defecto",
                    "SOURCE" => [
                        "TYPE" => "DataSource",
                        "MODEL" => "/model/reflection/Storage",
                        "DATASOURCE" => "SerializerList",
                        "LABEL_EXPRESSION" => "[%name%] ([%type%])",
                        "VALUE" => "name"
                    ]
                ],
                       "DEFAULT_WRITE_SERIALIZER" => [
                           "LABEL" => "Serializador de escritura",
                           "TYPE" => "String",
                           "MAXLENGTH" => 40,
                           "HELP" => "Serializador por defecto para escritura",
                           "SOURCE" => [
                               "TYPE" => "DataSource",
                               "MODEL" => "/model/reflection/Storage",
                               "DATASOURCE" => "SerializerList",
                               "LABEL_EXPRESSION" => "[%name%] ([%type%])",
                               "VALUE" => "name"
                           ]
                       ],
                       "INDEXFIELDS" => [
                           "LABEL" => "Campos indice",
                           "TYPE" => "Array",
                           "HELP" => "Lista de campos indice (normalmente, sólo uno)",
                           "ELEMENTS" => ["TYPE" => "String",
                                    "SOURCE" => [
                                        "TYPE" => "Path",
                                        "PATH" => "#../../FIELDS/[[KEYS]]",
                                        "LABEL" => "LABEL",
                                        "VALUE" => "VALUE"
                                    ]
                               ]
                       ],
                       "TABLE" => [
                           "LABEL" => "Tabla",
                           "TYPE" => "String",
                           "MAXLENGTH" => 40,
                           "HELP" => "Nombre de tabla para Storage"
                       ],
                       "LABEL" => [
                           "LABEL" => "Label",
                           "TYPE" => "String",
                           "MAXLENGTH" => 25,
                           "HELP" => "Etiqueta para este modelo"
                       ],
                       "SHORTLABEL" => [
                           "LABEL" => "Label corta",
                           "TYPE" => "String",
                           "MAXLENGTH" => 10,
                           "HELP" => "Etiqueta corta para este modelo"
                       ],
                       "CARDINALITY" => [
                           "LABEL" => "Cardinalidad",
                           "TYPE" => "Integer",
                           "HELP" => "Numero aproximado de lineas esperables (o instancias) para este objeto"
                       ],
                       "CARDINALITY_TYPE" =>
                           [
                               "LABEL" => "Tipo de cardinalidad",
                               "TYPE" => "Enum",
                               "HELP" => "Estimacion de si el numero de lineas de este objeto es variable.",
                               "VALUES" => ["FIXED","VARIABLE"],
                               "DEFAULT" => "FIXED"
                           ],

                       "FIELDS" => [
                           "LABEL" => "Campos",
                           "TYPE" => "Dictionary",
                           "VALUETYPE" => "BaseType",
                           "HELP" => "Campos existentes en el modelo"
                       ],

                       "ALIASES" => [
                           "LABEL" => "Aliases",
                           "TYPE" => "Dictionary",
                           "VALUETYPE" => [
                               "TYPE" => "TypeSwitcher",
                               "LABEL" => "Tipo de Alias",
                               "TYPE_FIELD" => "TYPE",
                               "ALLOWED_TYPES" => [
                                   "InverseRelation" => [
                                       "LABEL" => "Relacion inversa",
                                       "TYPE"=>"Container",
                                       "FIELDS"=>[
                                       "TYPE" => ["LABEL" => "Type", "TYPE" => "String", "FIXED" => "InverseRelation"],
                                       "MODEL" => [
                                           "LABEL" => "Model",
                                           "TYPE" => "String",
                                           "REQUIRED" => true,
                                           "SOURCE" => [
                                               "TYPE" => "DataSource",
                                               "MODEL" => '/model/reflection/Model',
                                               "DATASOURCE" => 'ModelList',
                                               "LABEL_EXPRESSION" => "[%/package%] > [%/smallName%]",
                                               "VALUE" => "fullName"
                                           ]],
                                       "FIELDS" => [
                                           "LABEL" => "Campos",
                                           "HELP" => "Campos que definen esta relacion (local=>remoto)",
                                           "TYPE" => "Dictionary",
                                           "SOURCE" => [
                                               "TYPE" => "Path",
                                               // Este path va:
                                               // El primer ".." sale del campo "FIELDS" actual (un par de lineas mas arriba)
                                               // El segundo ".." sale del campo "FIELDS" de este tipo (al principio de la definicion del tipo)
                                               // El tercer ".." sale de este tipo
                                               // El cuarto llega al diccionario padre.
                                               "PATH" => "#../../../FIELDS/[[KEYS]]",
                                               "LABEL" => "LABEL",
                                               "VALUE" => "LABEL"

                                           ],
                                           "VALUETYPE" => [
                                               "LABEL" => "Campo remoto",
                                               "TYPE" => "String",
                                               "SOURCE" => [
                                                   "TYPE" => "DataSource",
                                                   "MODEL" => '/model/reflection/Model',
                                                   "DATASOURCE" => 'FieldList',
                                                   "PARAMS" => [
                                                       "model" => "[%#../MODEL%]"
                                                   ],
                                                   "LABEL" => "NAME",
                                                   "VALUE" => "NAME"
                                               ]
                                           ],
                                           "REQUIRED" => true

                                       ],
                                       "MULTIPLICITY" => ["LABEL" => "Multiplicidad", "TYPE" => "Enum", "VALUES" => ["1:N", "0-1:N", "M:N"]],
                                       "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "KEEP_KEY_ON_EMPTY" => false],
                                       "CARDINALITY" => ["LABEL" => "Cardinalidad", "TYPE" => "Integer", "HELP" => "Numero aproximado de elementos del modelo remoto que apuntan a 1 elemento del modelo actual."],
                                       "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                                       "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false],
                                       "DEFAULT" => ["TYPE" => "String", "LABEL" => "Valor por defecto", "KEEP_KEY_ON_EMPTY" => false]
                                       ]
                                   ],
                                   "RelationMxN" => [
                                       "LABEL" => "Relacion Multiple",
                                       "HELP" => "Una relación múltiple requiere que exita un modelo intermedio, con  ROLE tipo MULTIPLE_RELATIONSHIP, que almacena los campos relacionados.",
                                       "TYPE"=>"Container",
                                       "FIELDS"=>[
                                       "TYPE" => ["LABEL" => "Type", "TYPE" => "String", "FIXED" => "RelationMxN"],
                                       "MODEL" => [
                                           "LABEL" => "Modelo intermedio",
                                           "TYPE" => "String",
                                           "HELP" => "Modelo que contiene la relación",
                                           "REQUIRED" => true,
                                           "SOURCE" => [
                                               "TYPE" => "DataSource",
                                               "MODEL" => '/model/reflection/Model',
                                               "DATASOURCE" => 'ModelList',
                                               "LABEL_EXPRESSION" => "[%/package%] > [%/smallName%]",
                                               "VALUE" => "fullName"
                                           ]],
                                       "REMOTE_MODEL" => [
                                           "LABEL" => "Modelo remoto",
                                           "HELP" => "Modelo que está en el otro extremo de la relación, con el que se relaciona a traves del modelo intermedio",
                                           "TYPE" => "String",
                                           "REQUIRED" => true,
                                           "SOURCE" => [
                                               "TYPE" => "DataSource",
                                               "MODEL" => '/model/reflection/Model',
                                               "DATASOURCE" => 'ModelList',
                                               "LABEL_EXPRESSION" => "[%/package%] > [%/smallName%]",
                                               "VALUE" => "fullName"
                                           ]],
                                       "FIELDS" => [
                                           "LABEL" => "Campos",
                                           "HELP" => "Campos que definen esta relacion (local=>remoto)",
                                           "TYPE" => "Dictionary",
                                           "SOURCE" => [
                                               "TYPE" => "Path",
                                               // Este path va:
                                               // El primer ".." sale del campo "FIELDS" actual (un par de lineas mas arriba)
                                               // El segundo ".." sale del campo "FIELDS" de este tipo (al principio de la definicion del tipo)
                                               // El tercer ".." sale de este tipo
                                               // El cuarto llega al diccionario padre.
                                               "PATH" => "#../../../../FIELDS/[[KEYS]]",
                                               "LABEL" => "LABEL",
                                               "VALUE" => "LABEL"

                                           ],
                                           "VALUETYPE" => [
                                               "LABEL" => "Campo remoto (en la tabla intermedia)",
                                               "TYPE" => "String",
                                               "SOURCE" => [
                                                   "TYPE" => "DataSource",
                                                   "MODEL" => '/model/reflection/Model',
                                                   "DATASOURCE" => 'FieldList',
                                                   "PARAMS" => [
                                                       "model" => "[%#../../../MODEL%]"
                                                   ],
                                                   "LABEL" => "NAME",
                                                   "VALUE" => "NAME"
                                               ]
                                           ],
                                           "REQUIRED" => true

                                       ],
                                       "MULTIPLICITY" => ["LABEL" => "Multiplicidad", "TYPE" => "Enum", "VALUES" => ["N:1", "N:0-1", "M:N"]],
                                       "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                                       "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false],
                                       "DEFAULT" => ["TYPE" => "String", "LABEL" => "Valor por defecto", "KEEP_KEY_ON_EMPTY" => false]
                                           ]
                                   ]
                               ]
                           ],
                           "HELP" => "Aliases (relaciones inversas y multiples)"
                       ],

                "PERMISSIONS" => [
                    "LABEL" => "Permissions",
                    "TYPE" => "/model/reflection/Permissions/types/PermissionSpec",
                    "HELP" => "Especificacion de permisos"
                ],
                "SOURCE" => [
                    "LABEL" => "Sources",
                    "HELP" => "Opciones para el almacenamiento de este tipo de modelos",
                    "TYPE" => "Container",
                    "FIELDS" => [
                        "STORAGE" => [
                            "LABEL" => "Storage",
                            "HELP" => "Opciones para el almacenamiento en StorageEngines",
                            "TYPE" => "Dictionary",
                            "VALUETYPE"=>[
                                "TYPE"=>"PHPVariable"
                            ],
                            "SOURCE" => [
                                "TYPE" => "Datasource",
                                "MODEL" => "/model/reflection/Storage/SerializerTypes"
                            ]
                        ]
                    ]
                ],
                "STATES" => [
                    "LABEL" => "Estados",
                    "TYPE" => "/model/reflection/Model/types/StateSpec",
                    "KEEP_ON_EMPTY" => false
                ],
                "OWNERSHIP" => [
                    "LABEL" => "Path al campo que define la propiedad de las instancias",
                    "HELP" => "Indica el path, a partir de este modelo, hasta el modelo/campo que contiene el propietario de este modelo.",
                    "TYPE" => "String"
                ],
                "CREATE_PERMISSIONS" => [
                    "LABEL" => "Crear permisos",
                    "HELP" => "Si este campo está a true, se crea un grupo de permisos asociado a este modelo.",
                    "TYPE" => "Boolean",
                    "DEFAULT" => true
                ]
            ]
        ],$parentType, $value,$validationMode);
    }

}