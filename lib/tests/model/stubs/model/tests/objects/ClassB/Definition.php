<?php
namespace model\tests\ClassB;
/**
FILENAME:/var/www/adtopy/model/web/objects/Page/Definition.php
CLASS:Definition
 *
 *
 **/

class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'default',
        'DEFAULT_WRITE_SERIALIZER'=>'default',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'Complex',
        'LABEL'=>'Complex',
        'SHORTLABEL'=>'Page',
        'CARDINALITY'=>'300',
        'CARDINALITY_TYPE'=>'FIXED',
        "FIELDS"=>[
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'Post Id',
                'SHORTLABEL'=>'Id',
                'DESCRIPTIVE'=>'false',
                'ISLABEL'=>'false'
            ),
            "C1"=>[
                "LABEL"=>"Container1",
                "TYPE"=>"Container",
                "FIELDS"=>[
                    "a1"=>["TYPE"=>"Container",
                        "LABEL"=>"C1-a1",
                        "FIELDS"=>[
                            "a2"=>["TYPE"=>"String","MINLENGTH"=>3],

                            "b2"=>[
                                "TYPE"=>"Relationship",
                                "MODEL"=>"\\model\\tests\\User",
                                "FIELDS"=>["b2"=>"id"]
                            ]
                        ]
                    ]
                ]

                ],

            "C2"=>[
                "TYPE" => "Container",
                "FIELDS" => [
                    "one" => ["TYPE" => "String", "MINLENGTH" => 2, "MAXLENGTH" => 10],
                    "two" => ["TYPE" => "String", "DEFAULT" => "Hola"],
                    "three" => ["TYPE" => "String"],
                    "C3" => ["TYPE" => "Container",
                              "FIELDS"=>[
                                  "four"=>["TYPE"=>"String","MINLENGTH"=>3],
                                  "position"=>["TYPE"=>"Container",
                                      "FIELDS"=>[
                                          "LAT"=>["TYPE"=>"Decimal","REQUIRED"=>true],
                                          "LON"=>["TYPE"=>"Decimal","REQUIRED"=>true],
                                      ]]
                                  ]
                    ],
                    "state" => ["TYPE" => "State", "VALUES" => ["E1", "E2", "E3"], "DEFAULT" => "E1"]
                ],
                'STATES' => [
                    "LISTENER_TAGS"=>array(
                        "ONE"=>array("TYPE"=>"METHOD","METHOD"=>"callback_one"),
                        "TWO"=>array("TYPE"=>"METHOD","METHOD"=>"callback_two","PARAMS"=>array("set")),
                        "TEST_OK"=>array("TYPE"=>"METHOD","METHOD"=>"test_ok")
                    ),
                    'STATES' => [
                        'E1' => [
                            'FIELDS' => ['EDITABLE' => ['one','two','C3']],
                            'LISTENERS'=>[
                            "ON_LEAVE"=>array(
                                "STATES"=>array("E2"=>array("ONE")),
                            )
                                ]

                        ],
                        'E2' => [
                            'ALLOW_FROM'=>["E1"],
                            'FIELDS' => ['EDITABLE' => ['two','three','C3']],
                            'LISTENERS'=>[
                            "TESTS"=>array("TEST_OK"),
                            "ON_ENTER"=>array(
                                "STATES"=>array(
                                    "E1"=>array("TWO"),
                                )
                            )
                                ]
                        ],
                        'E3' => [
                            'ALLOW_FROM'=>["E2"],
                            'FINAL'=>true,
                            'FIELDS' => ['REQUIRED' => ['three']]]
                    ],
                    'FIELD' => 'state'
                ]
            ]
        ],
        'PERMISSIONS'=>array(),
        'SOURCE'=>[
        'STORAGE'=>array(
            'MYSQL'=>array(
                'ENGINE'=>'InnoDb',
                'CHARACTER SET'=>'utf8',
                'COLLATE'=>'utf8_general_ci',
                'TABLE_OPTIONS'=>array('ROW_FORMAT'=>'FIXED'),
                "FIELD_MAP"=>[
                    "/C2/C3/position/LAT"=>"lat",
                    "/C2/C3/position/LON"=>"lon"
                ]
            )
        )
            ]
    );
}
