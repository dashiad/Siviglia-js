<?php
namespace model\tests\Product\OrderProduct;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'MULTIPLE_RELATION',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'MULTIPLE_RELATION'=>array(
            'FIELDS'=>array(
                'id_product',
                'id_order'
            )
        ),
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'orderproduct',
        'LABEL'=>'Order - Product',
        'SHORTLABEL'=>'Order - Product',
        'CARDINALITY'=>'100000',
        'CARDINALITY_TYPE'=>'VARIABLE',
        'FIELDS'=>array(
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'id',
                'SHORTLABEL'=>'id',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'dummy'=>[
                'TYPE'=>'String',
                'LABEL'=>'Name',
                'SHORTLABEL'=>'Name',
                'ISLABEL'=>'true'
            ],
            'price'=>[
                'TYPE'=>'Decimal',
                'LABEL'=>'Price',
                'NDECIMALS'=>2,
                'NINTEGERS'=>5
            ],
            'id_product'=>array(

                'FIELDS'=>array('id_product'=>'id'),
                'MODEL'=>'\model\tests\Product',
                'LABEL'=>'id_product',
                'SHORTLABEL'=>'id_product',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1,
                'DESCRIPTIVE'=>'true'
            ),
            'id_order'=>array(

                'FIELDS'=>array('id_order'=>'id'),
                'MODEL'=>'\model\tests\Product\Order',
                'LABEL'=>'id_order',
                'SHORTLABEL'=>'id_order',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1,
                'DESCRIPTIVE'=>'true'
            ),
            "status"=>array('TYPE' => 'State',
                'VALUES' => array(
                    'Ordered','Returned'
                ),
                'DEFAULT' => 'Ordered'
            ),
        ),
        'STATES' => array(
            "LISTENER_TAGS"=>array(
                "ON_RETURNED"=>array("TYPE"=>"EXPRESSION","EXPRESSION"=>"[%id_product/status%]='Returned'"),
                "ON_ORDERED"=>array("TYPE"=>"EXPRESSION","EXPRESSION"=>"[%id_product/status%]='Sold'")
            ),
            //'Created','Priced','Published', 'Sold','Returned'

            'STATES' => array(
                'Ordered' => array(
                    'FIELDS' => array(
                        'REQUIRED'=>array('id_product','id_order'),
                        'EDITABLE' => array('dummy')
                    )
                ),
                'Returned' => array(
                    "LISTENERS"=>array(
                        "ON_ENTER"=>array("ON_RETURNED")
                    )
                )
            ),
            'FIELD' => 'status',
            'DEFAULT' => 'Created'
        ),
        'PERMISSIONS'=>array(),
        'SOURCE'=>[
            'STORAGE'=>array(
                'MYSQL'=>array(
                    'ENGINE'=>'InnoDb',
                    'CHARACTER SET'=>'utf8',
                    'COLLATE'=>'utf8_general_ci',
                    'TABLE_OPTIONS'=>array('ROW_FORMAT'=>'FIXED')
                )
            )
        ]
    );
}
