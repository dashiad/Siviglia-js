<?php
namespace model\tests\Product;
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
        'TABLE'=>'product',
        'LABEL'=>'product',
        'SHORTLABEL'=>'Product',
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
                    'Created','Priced','Published', 'Sold','Returned'
                ),
                'DEFAULT' => 'Created'
            ),
            'dummy'=>[
                'TYPE'=>'String',
                'LABEL'=>'Name',
                'SHORTLABEL'=>'Name',
                'ISLABEL'=>'true'
            ],
            'name'=>[
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
            ]
        ),
        'ALIASES'=>array(
            'properties'=>array(
                'TYPE'=>'RelationMxN',
                'MODEL'=>'\model\tests\product\ProductProperties',
                'REMOTE_MODEL'=>'\model\tests\product\ProductProperty',
                'FIELDS'=>array('id'=>'id_product'),
                //'REMOTE_MODEL'=>'ps_feature_value',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICTY'=>'M:N',
                'CARDINALITY'=>100
            )
        ),

        'STATES' => array(
            "LISTENER_TAGS"=>array(
                "ON_CREATE"=>array("TYPE"=>"METHOD","METHOD"=>"onCreated"),
                "ON_PRICED"=>array("TYPE"=>"METHOD","METHOD"=>"onPriced"),
                "ON_PUBLISHED"=>array("TYPE"=>"METHOD","METHOD"=>"onPublished"),
                "WHEN_SELLING"=>array("TYPE"=>"METHOD","METHOD"=>"whenSelling"),
                "ON_SOLD"=>array("TYPE"=>"METHOD","METHOD"=>"onSold"),
                "WHEN_RETURNED"=>array("TYPE"=>"METHOD","METHOD"=>"whenReturned"),
                "ON_RETURNED"=>array("TYPE"=>"METHOD","METHOD"=>"onReturned")
            ),
            //'Created','Priced','Published', 'Sold','Returned'

            'STATES' => array(
                'Created' => array(
                    "LISTENERS"=>array(
                        "ON_ENTER"=>array(
                            "STATES"=>array(
                                "*"=>array("ON_CREATE")
                            ),
                        )
                    ),
                    'FIELDS' => array(
                        'EDITABLE' => array('dummy','name','price')
                    )
                ),
                'Priced' => array(
                    'ALLOW_FROM'=>array("Created","Published","Returned"),
                    "LISTENERS"=>array(
                        "ON_ENTER"=>array("ON_PRICED")
                    ),
                    'FIELDS' => array(
                        'EDITABLE' => array('dummy')
                    )
                ),
                'Published' => array(
                    'ALLOW_FROM'=>["Priced","Returned"],
                    "LISTENERS"=>array(
                        "ON_ENTER"=>array(
                            "ON_PUBLISHED"
                        ),
                        //"TESTS"=>array("FAIL_TEST")
                    ),
                    'FIELDS' => array(
                        'EDITABLE' => array('dummy'),
                        'REQUIRED' => array('name','price')
                    )
                ),
                'Sold' => array(
                    'ALLOW_FROM'=>["Published"],
                    'FIELDS' => array(
                        'REQUIRED' => array("[%lastSold/id_order/status%]=='Created'")
                    )
                ),
                'Returned' => array(
                    'ALLOW_FROM'=>["Sold"],
                    'FIELDS' => array(
                        'REQUIRED' => array("[%lastSold/status%]=='Returned'"),
                        'EDITABLE'=>['name','price','dummy']
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
