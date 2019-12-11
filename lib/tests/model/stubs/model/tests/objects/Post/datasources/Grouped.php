<?php
namespace backoffice\ps_orders\datasources;
/**
FILENAME:/var/www/percentil/backoffice//backoffice/objects/ps_orders/datasources/OrdersReport.php
CLASS:OrdersReport
 *
 *
 **/

class OrdersReport
{
    public static $baseQuery;
    static  $definition = array();

    public function __construct()
    {
        self::$baseQuery = self::getTransformedQuery();
        self::$definition = array(
            'ROLE'=>'list',
            'DATAFORMAT'=>'Table',
            'PARAMS'=>array(
                'id_customer'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_customer',
                    'TRIGGER_VAR'=>'id_customer'
                ),
                'start_date'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'date_add',
                    'TRIGGER_VAR'=>'start_date'
                ),
                'end_date'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'date_add',
                    'TRIGGER_VAR'=>'end_date'
                ),
                'module'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'module',
                    'TRIGGER_VAR'=>'module',
                    'PARAMTYPE'=>'DYNAMIC'
                ),
                'status'=>array(
                    'TYPE'=>'ArrayType',
                    'ELEMENTS'=>array(
                        'MODEL'=>'\backoffice\ps_orders\ps_order_history',
                        'FIELD'=>'id_order_state'
                    ),
                    'TRIGGER_VAR'=>'status'
                ),
                'min_price'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_paid_real',
                    'TRIGGER_VAR'=>'min_price'
                ),
                'max_price'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_paid_real',
                    'TRIGGER_VAR'=>'max_price'
                ),
                'id_order'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_order',
                    'TRIGGER_VAR'=>'id_order'
                ),
                'id_site'=>array(
                    'MODEL'=>'\backoffice\ps_customer\customer_extra_info',
                    'FIELD'=>'id_site',
                ),
                'min_id_order'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_order',
                    'TRIGGER_VAR'=>'min_id_order'
                ),
                'max_id_order'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_order',
                    'TRIGGER_VAR'=>'max_id_order'
                ),
                'id_carrier'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_carrier',
                    'TRIGGER_VAR'=>'id_carrier'
                ),
                'min_date_delivery'=>array(
                    'MODEL'=>'\backoffice\ps_cart',
                    'FIELD'=>'date_delivery',
                    'TRIGGER_VAR'=>'min_date_delivery'
                ),
                'max_date_delivery'=>array(
                    'MODEL'=>'\backoffice\ps_cart',
                    'FIELD'=>'date_delivery',
                    'TRIGGER_VAR'=>'min_date_delivery'
                )
            ),
            'IS_ADMIN'=>0,
            'FIELDS'=>array(
                'id_order'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'id_order'
                ),
                'ncompras'=>array(
                    'TYPE'=>'String',
                    'MAXLENGTH'=>0
                ),
                'id_customer'=>array(
                    'MODEL'=>'\backoffice\ps_customer',
                    'FIELD'=>'id_customer',
                ),
                'name'=>array(
                    'TYPE'=>'String',
                    'MAXLENGTH'=>0
                ),
                'email'=>array(
                    'MODEL'=>'\backoffice\ps_customer',
                    'FIELD'=>'email',
                ),
                'module'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'module',
                    'GROUPING'=>'DISCRETE'
                ),
                'total'=>array(
                    'TYPE'=>'Decimal',
                    'MAXLENGTH'=>0,
                    'GROUPING'=>'CONTINUOUS',
                    'DEFAULTGROUPING'=>5,
                    'GROUP_UNTIL'=>200
                ),
                'total_discounts'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_discounts',
                    'ALLOW_SUM'=>true,
                    'GROUPING'=>'CONTINUOUS',
                    'DEFAULTGROUPING'=>5,
                    'GROUP_UNTIL'=>200
                ),
                'total_paid_real'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_paid_real',
                    'ALLOW_SUM'=>true,
                ),
                'total_products'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_products',
                    'ALLOW_SUM'=>true,
                ),
                'total_products_wt'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_products_wt',
                    'ALLOW_SUM'=>true,
                    'GROUPING'=>'CONTINUOUS',
                    'DEFAULTGROUPING'=>5
                ),
                'total_shipping'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'total_shipping',
                    'ALLOW_SUM'=>true
                ),
                'id_order_state'=>array(
                    'MODEL'=>'\backoffice\ps_orders\ps_order_state',
                    'FIELD'=>'id_order_state'
                ),
                'state'=>array(
                    'TYPE'=>'String',
                    'MAXLENGTH'=>0
                ),
                'COD_fee'=>array(
                    'TYPE'=>'String',
                    'MAXLENGTH'=>0,
                    'ALLOW_SUM'=>true
                ),
                'date_add'=>array(
                    'MODEL'=>'\backoffice\ps_orders',
                    'FIELD'=>'date_add',
                    'GROUPING'=>'DATETIME'
                ),
                'date_delivery'=>array(
                    'TYPE'=>'DateTime',
                    'MAXLENGTH'=>0
                ),
                'color'=>array(
                    'MODEL'=>'\backoffice\ps_orders\ps_order_state',
                    'FIELD'=>'color'
                )
            ),
            'PERMISSIONS'=>array('_PUBLIC_'),
            'STORAGE'=>array(
                'MYSQL'=>array(
                    'DEFINITION'=>array(
                        'TABLE'=>'ps_orders',
                        'DEFAULT_ORDER'=>'o.date_add',
                        'DEFAULT_ORDER_DIRECTION'=>'DESC',
                        'BASE'=>self::$baseQuery,
                        'CONDITIONS'=>array(
                            array(
                                'FILTER'=>'o.id_customer={%id_customer%}',
                                'TRIGGER_VAR'=>'id_customer',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.date_add>={%start_date%}',
                                'TRIGGER_VAR'=>'start_date',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.date_add<{%end_date%}',
                                'TRIGGER_VAR'=>'end_date',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'module LIKE {%module%}',
                                'TRIGGER_VAR'=>'module',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>array("F"=>'stl.id_order_state','OP'=>'=',"V"=>'{%status%}'),
                                'TRIGGER_VAR'=>'status',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.total_paid_real>={%min_price%}',
                                'TRIGGER_VAR'=>'min_price',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.total_paid_real<={%max_price%}',
                                'TRIGGER_VAR'=>'max_price',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.id_order={%id_order%}',
                                'TRIGGER_VAR'=>'id_order',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'cei.id_site={%id_site%}',
                                'TRIGGER_VAR'=>'id_site',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.id_order>={%min_id_order%}',
                                'TRIGGER_VAR'=>'min_id_order',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.id_order<={%max_id_order%}',
                                'TRIGGER_VAR'=>'max_id_order',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'o.id_carrier={%id_carrier%}',
                                'TRIGGER_VAR'=>'id_carrier',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'cart.date_delivery>={%min_date_delivery%',
                                'TRIGGER_VAR'=>'min_date_delivery',
                                'DISABLE_IF'=>''
                            ),
                            array(
                                'FILTER'=>'cart.date_delivery<{%max_date_delivery%}',
                                'TRIGGER_VAR'=>'max_date_delivery',
                                'DISABLE_IF'=>''
                            ),
                        )
                    )
                )
            )
        );
    }

    public static function getBaseQuery()
    {
        $reportStates = \backoffice\ps_orders\ps_order_state::getOrderReportStates();
        $lstReportStates = implode(',', $reportStates);

        $sql = "SELECT [%FIELD_LIST%]
                FROM
                (
                    SELECT id_customer,COUNT(*) AS ncompras
                    FROM ps_orders o
                    INNER JOIN PercentilOrder po ON o.id_order = po.id_order
                    WHERE po.id_order_state IN (".$lstReportStates.")
                    GROUP BY id_customer
                ) cn, ps_orders o
                LEFT JOIN ps_customer c ON o.id_customer=c.id_customer
                LEFT JOIN PercentilOrder po ON o.id_order=po.id_order
                LEFT JOIN customer_extra_info AS cei ON c.id_customer=cei.id_customer
                LEFT JOIN ps_cart cart ON o.id_cart = cart.id_cart
                LEFT JOIN ps_order_state_lang stl ON stl.id_order_state=po.id_order_state AND stl.id_lang=|%DEFAULT_LANGUAGE_ID%|
                LEFT JOIN ps_order_state st ON st.`id_order_state`=po.id_order_state
                WHERE cn.id_customer=o.id_customer AND [%0%] and [%1%] and [%2%] and [%3%] and [%4%] and [%5%]
                and [%6%] and [%7%] and [%8%] and [%9%] and [%10%] and [%11%] AND [%12%] AND [%13%]";

        return $sql;
    }

    public static function getTransformedQuery()
    {
        $aux = self::getBaseQuery();
        $aux = str_replace('[%FIELD_LIST%]', "o.id_order, cn.ncompras, c.id_customer, CONCAT(c.firstname,' ',c.lastname) AS name,
                c.email, o.module, (o.total_products_wt+total_shipping+IF(po.COD_fee IS NULL,0,COD_fee)) AS total,
                o.total_discounts, o.total_paid_real, o.total_products, o.total_products_wt, o.total_shipping,
                stl.name AS state, stl.id_order_state, IF(COD_fee IS NULL,'--',COD_fee) AS COD_fee,
                o.date_add, cart.date_delivery, color", $aux);

        return $aux;
    }
}