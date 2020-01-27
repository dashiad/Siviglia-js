<?php
namespace model\tests\Product\Shipment;
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
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'shipment',
        'LABEL'=>'shipment',
        'SHORTLABEL'=>'Shipment',
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
            'dummy'=>[
                'TYPE'=>'String',
                'LABEL'=>'Name',
                'SHORTLABEL'=>'Name',
                'ISLABEL'=>'true'
            ],
            "status"=>array('TYPE' => 'State',
                'VALUES' => array(
                    'Created','Shipped'
                ),
                'DEFAULT' => 'Created'
            ),
            'destination'=>[
                'TYPE'=>'String',
                'LABEL'=>'Destination',
                'SHORTLABEL'=>'Destination',
                'ISLABEL'=>'true'
            ]
        ),
        'ALIASES'=>array(
            'order'=>array(
                'TYPE'=>'InverseRelation',
                'MODEL'=>'\model\tests\Product\Order',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICITY'=>'1:N',
                'CARDINALITY'=>100,
                'FIELDS'=>array('id'=>'id_shipment')
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
