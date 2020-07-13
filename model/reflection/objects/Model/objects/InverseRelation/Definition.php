<?php
namespace model\reflection\Model\InverseRelation;
class Definition extends \lib\model\BaseModelDefinition
{
    static $definition = array(

        'FIELDS' => array(
            "TYPE"=>array(
                'LABEL'=>"Tipo",
                "TYPE"=>"String",
                "FIXED"=>"InverseRelation"
            ),
            'ROLE' => array(
                'LABEL'=>"Role",
                'DEFAULT'=>"HAS_MANY",
                "HELP"=>"Campo solo descriptivo.",
                'TYPE' => 'String',
                "SOURCE"=>[
                    "TYPE"=>"Array",
                    "VALUES"=>["HAS_ONE","HAS_MANY"]
                ]
            ),
            'MULTIPLICTY'=>[
                'LABEL'=>"Role",
                'DEFAULT'=>"1:N",
                "HELP"=>"Campo solo descriptivo.",
                'TYPE'=>'String',
                'SOURCE'=>[
                    "TYPE"=>"Array",
                    "VALUES"=>["1:1","1:N"]
                ]
            ],
            'CARDINALITY'=>[
                'LABEL'=>'Cardinalidad',
                'DEFAULT'=>100,
                'TYPE'=>'Integer',
            ]
        )
    );

}





