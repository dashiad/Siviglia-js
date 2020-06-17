<?php
namespace model\tests\Product\Order;
class Definition extends \lib\model\BaseModelDefinition
{
    /*
     * `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `created_on` datetime DEFAULT NULL,
     */
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'modeltests',
        'DEFAULT_WRITE_SERIALIZER'=>'modeltests',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'order',
        'LABEL'=>'order',
        'SHORTLABEL'=>'order',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'Post Id',
                'SHORTLABEL'=>'Id',
                'DESCRIPTIVE'=>'false',
                'ISLABEL'=>'false'
            ),
            "status"=>array('TYPE' => 'State',
                'VALUES' => array(
                    'Created','Paid','Shipped','Cancelled'
                ),
                'DEFAULT' => 'Created'
            ),
            'user_id'=>[

                'FIELDS'=>array('user_id'=>'id'),
                'MODEL'=> '\model\tests\User',
                'LABEL'=>'User',
                'SHORTLABEL'=>'User',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
            ],
            'dummy'=>[
                'TYPE'=>'String',
                'LABEL'=>'Name',
                'SHORTLABEL'=>'Name',
                'ISLABEL'=>'true'
            ],
            'shipment_id'=>[

                'FIELDS'=>array('shipment_id'=>'id'),
                'MODEL'=> '\model\tests\Product\Shipment',
                'LABEL'=>'Shipment',
                'SHORTLABEL'=>'Shipment',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
            ],
        ),
        'ALIASES'=>array(
            'products'=>array(
                'TYPE'=>'RelationMxN',
                'MODEL'=>'\model\tests\Product\OrderProduct',
                'REMOTE_MODEL'=>'\model\tests\Product',
                'FIELDS'=>array('id'=>'id_product'),
                //'REMOTE_MODEL'=>'ps_feature_value',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICTY'=>'M:N',
                'CARDINALITY'=>100
            )
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
