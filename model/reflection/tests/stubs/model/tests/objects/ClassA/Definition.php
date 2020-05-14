<?php
namespace model\tests\ClassA;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=[
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'ClassA',
        'LABEL'=>'ClassA',
        'SHORTLABEL'=>'ClassA',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>[
            "id"=>["TYPE"=>"AutoIncrement"],
            "StateField"=>[
                "TYPE"=>"State",
                "VALUES"=>["one","two","three"]
            ],
            "Field_1"=>["TYPE"=>"String"],
            "Field_2"=>["TYPE"=>"String"],
            "Field_3"=>["TYPE"=>"String"],
            "Field_4"=>["TYPE"=>"String"],
            "Field_5"=>["TYPE"=>"String"]
        ],
        'STATES' => [
            'FIELD'=>'StateField',
            'DEFAULT'=>'one',
            'LISTENER_TAGS'=>array(
                "CALLBACK_SIMPLE"=>"MetodoSimple",
                "CALLBACK_COMPLEX"=>array(
                    "METHOD"=>"MetodoComplex",
                    "PARAMS"=>["param1"]
                ),
                "TEST"=>"MetodoTest",
                "TEST2"=>"MetodoTest2",
                "LEAVE"=>"MetodoLeave",
                "ENTER"=>"MetodoEnter"
            ),
            "STATES"=>[
                "one"=>[
                    'LISTENERS'=>[
                        'TEST'=>["TEST"],
                        'ON_LEAVE'=>["LEAVE"],
                        'ON_ENTER'=>["ENTER"]
                    ],
                    'FIELDS' => [
                        'EDITABLE' => ['*'],
                        'REQUIRED'=>['Field_3']
                    ],
                    'PERMISSIONS'=>array(
                        "ADD"=>[["REQUIRES"=>"ADD","ON"=>"/model/web/Page"]],
                        "DELETE"=>[["REQUIRED"=>"DELETE","ON"=>"/model/web/Page"]],
                        "EDIT"=>[["REQUIRES"=>"ADMIN","ON"=>"/model/web/Page"]],
                        "VIEW"=>[["REQUIRES"=>"VIEW","ON"=>"/model/web/Page"]]
                    )
                ],
                "two"=>[
                    'ALLOW_FROM'=>['one'],
                    'LISTENERS'=>[
                        'TEST'=>["TEST"],
                        'ON_LEAVE'=>["LEAVE"],
                        'ON_ENTER'=>["ENTER"]
                    ],
                    'FIELDS' => array(
                        'EDITABLE' => array('Field_1','Field_2'),
                        'REQUIRED'=>array()
                    ),
                    'PERMISSIONS'=>array(
                        "ADD"=>[["TYPE"=>"ACL","REQUIRES"=>"ADD","ON"=>"/model/web/Page"]],
                        "DELETE"=>[["TYPE"=>"Role","ROLE"=>"Editor"]],
                        "EDIT"=>[["TYPE"=>"Public"]],
                        "VIEW"=>[["REQUIRES"=>"VIEW","ON"=>"/model/web/Page"]]
                    )
                ],
                "three"=>[
                    'ALLOW_FROM'=>['one','two'],
                    'LISTENERS'=>[
                        'TEST'=>["STATES"=>["one"=>"TEST","two"=>"TEST2"],
                            'ON_LEAVE'=>["MetodoLeave"],
                            'ON_ENTER'=>["MetodoEnter"]
                        ],
                        'FIELDS' => array(
                            'EDITABLE' => array('Field_3'),
                            'REQUIRED'=>array('Field_1','Field_2')
                        ),
                        'PERMISSIONS'=>array(
                            "ADD"=>[["TYPE"=>"ACL","REQUIRES"=>"ADD","ON"=>"/model/web/Page"]],
                            "DELETE"=>[["TYPE"=>"Role","ROLE"=>"Editor"]],
                            "EDIT"=>[["TYPE"=>"Public"]],
                            "VIEW"=>[["REQUIRES"=>"VIEW","ON"=>"/model/web/Page"]]
                        ),
                        "FINAL"=>true

                    ]
                ]
            ]
        ]
    ];
}
