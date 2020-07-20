<?php


namespace model\reflection\Model\types;


class StateSpec extends \lib\model\types\Container
{

    /*
     * * array(
               'STATES' => array(
                'LISTENER_TAGS'=>array(
                "APPLY_WALLET"=>"applyWallet",
                "PRODUCTS_ARE_PUBLISHED"=>array(
                    "METHOD"=>"changeProducts",
                    "PARAMS"=>\backoffice\ps_product\PercentilProduct::STATE_PUBLISHED
                ),
                "SEND_PAID_EMAIL"=>array("METHOD"=>"sendEmail","PARAMS"=>array("paidEmail")),
                "REMOVE_FROM_PICKING"=>"removeFromPicking",
                "RETURN_PAYMENT"=>"returnPayment",
                "ORDER_IS_RETURNED"=>"doFullReturn",
                "ORDER_IS_RETURNED_WITHOUT_PAYMENT"=>array("METHOD"=>"fullReturn","PARAMS"=>array(\backoffice\ps_orders\OrderDetailReturnReasons::REASON_REJECTED_COD)),
                "ORDER_IS_LOST"=>"onLost",
                "SET_AS_PAID"=>"setAsPaid",
                "SET_AS_NOT_PAID"=>"setAsNotPaid",

                "TEST_PRODUCTS_AVAILABLE"=>"productsAreAvailable",
                "TEST_PAYMENT_OK"=>"hasPaymentOk",
                "TEST_NO_PICKING_MISSES"=>"hasNoPickingMisses",
                "TEST_NOT_PAID"=>"hasNoPayment",
                "TEST_NO_RETURNS"=>"hasNoReturns",
                "TEST_PAID"=>"isPaid"
            ),
            "STATES"=>array(
            ps_order_state::STATE_ORDER_NONE=>array(
                    'ALLOW_FROM'=>[]
                    'LISTENERS'=>array('TEST'=>array(),'ON_LEAVE'=>array(),'ON_ENTER'=>array()),
                    'FIELDS' => array('EDITABLE' => array('*'),'REQUIRED'=>array(),'FIXED'=>array(),'SET'=>array()),
                    'PERMISSIONS'=>array(
                        "ADD"=>array(array("REQUIRES"=>"ADD","ON"=>"/model/web/Page")),
                        "DELETE"=>array(array("REQUIRED"=>"DELETE","ON"=>"/model/web/Page")),
                        "EDIT"=>[["REQUIRES"=>"ADMIN","ON"=>"/model/web/Page")),
                        "VIEW"=>[["REQUIREs"=>"VIEW","ON"=>"/model/web/Page"
                    )
            ),
            ps_order_state::STATE_ORDER_PAID=>array(
                    'ALLOW_FROM'=>array( ps_order_state::STATE_ORDER_NONE,
                            ps_order_state::STATE_ORDER_WAITING_PAYMENT,
                            ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR,
                            ps_order_state::STATE_PAYMENT_ERROR ),
                    'LISTENERS'=>array(
                        'TESTS'=>array("TEST_PRODUCTS_AVAILABLE","TEST_PAYMENT_OK"),
                        'ON_ENTER'=>array_merge(array("APPLY_WALLET"),$paidAct,array("SEND_PAID_EMAIL", "CREATE_INVOICE"))
        )
    ),

            ps_order_state::STATE_ORDER_WAITING_PAYMENT=>array(
                'ALLOW_FROM'=>array(ps_order_state::STATE_ORDER_NONE),
                'LISTENERS'=>array('TESTS'=>array("TEST_PRODUCTS_AVAILABLE"),
                    'ON_ENTER'=>$paidAct
                )
            ),
    ps_order_state::STATE_ORDER_CANCELLED=>array(
        'FINAL'=>1,
        'ALLOW_FROM'=>array(ps_order_state::STATE_ORDER_PAID,ps_order_state::STATE_ORDER_PICKED,
            ps_order_state::STATE_ORDER_PROCESSED,ps_order_state::STATE_ORDER_WAITING_PAYMENT,
            ps_order_state::STATE_PAYMENT_ERROR,ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR,
            ps_order_state::STATE_ORDER_REVIEWED,ps_order_state::STATE_ORDER_OK,ps_order_state::STATE_ORDER_NONE
        ),
        'LISTENERS'=>array(
            'TESTS'=>array("TEST_PAID"),

            'ON_ENTER'=>array(
                'STATES'=>array(
                    // ORDER CANCELLED DESDE ORDER PAID
                    ps_order_state::STATE_ORDER_PAID=>$cancelledAct,
                    ps_order_state::STATE_ORDER_WAITING_PAYMENT=>array_merge($cancelledAct,array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL")),
                    ps_order_state::STATE_PAYMENT_ERROR=>array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL","ADD_HISTORY"),
                    ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR=>array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL","ADD_HISTORY"),
                    ps_order_state::STATE_ORDER_REVIEWED=>$cancelledAct,
                    ps_order_state::STATE_ORDER_OK=>$cancelledAct,
                    ps_order_state::STATE_ORDER_PICKED=>array_merge($cancelledAct,array("REMOVE_FROM_PICKING")),
                    ps_order_state::STATE_ORDER_PROCESSED=>array_merge($cancelledAct,array("REMOVE_FROM_PICKING")),
                    ps_order_state::STATE_ORDER_NONE=>array()
                )
            ),
            // Hay que tener un REJECT_TO ya que puede ser que nos llegue una notificacion asincrona de pago, para un pedido que ya esta cancelado.
            'REJECT_TO'=>array(
                'STATES'=>array(
                    ps_order_state::STATE_ORDER_PAID=>$cancelledAct
                )
            )
        )
    ),

               )
            );
     */
    function __construct($name,$parentType=null, $value=null,$validationMode=null)
    {
        $callbackDef=[
            "LABEL"=>"Callbacks de entrada al estado",
            "TYPE"=>"TypeSwitcher",
            "ON"=>[
                [
                "FIELD"=>"STATES",
                "IS"=>"Present",
                "THEN"=>"ByState"
                ]
            ],
            "IMPLICIT_TYPE"=>"Always",
            "ALLOWED_TYPES"=>[
                "Always"=>[
                    "LABEL"=>"Callbacks",
                    "TYPE"=>"Array",
                    "HELP"=>"Callbacks llamados independientemente del estado anterior/próximo",
                    "ELEMENTS"=>[
                        "LABEL"=>"Listeners",
                        "TYPE"=>"String",
                        "SOURCE"=>[
                            "TYPE"=>"Path",
                            "PATH"=> "#../../../../../LISTENER_TAGS/[[KEYS]]"
                        ]
                    ]
                ],
                "ByState"=>[
                    "LABEL"=>"Callbacks según estado",
                    "TYPE"=>"Dictionary",
                    "HELP"=>"Callbacks llamados dependiendo del estado anterior/próximo",
                    "VALUETYPE"=>[
                        "LABEL"=>"Listeners",
                        "TYPE"=>"String",
                        "SOURCE"=>[
                            "TYPE"=>"Path",
                            "PATH"=> "#../../../../../LISTENER_TAGS/[[KEYS]]"
                        ]
                    ],
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../../../../[[KEYS]]"
                    ]
                ]
            ]
        ];

        $testCallbacks=$callbackDef;
        $leaveCallbacks=$callbackDef;
        $testCallbacks["LABEL"]="Callbacks de test de estado";
        $testCallbacks["HELP"]="Callbacks a ejecutar antes de permitir el cambio de estado";
        $leaveCallbacks["LABEL"]="Callbacks de salida de estado";
        $leaveCallbacks["HELP"]="Callbacks a ejecutar al salir de este estado";
        $rejectToCallbacks=$callbackDef;
        $rejectToCallbacks["LABEL"]="Callbacks de rechazo";
        $rejectToCallbacks["HELP"]="Callbacks llamados al intentar transicionar a un estado no valido";

        parent::__construct($name,[
            "LABEL"=>"Definicion de estados",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "FIELD"=>[
                    "LABEL"=>"Campo estado",
                    "TYPE"=>"String",
                    "SOURCE"=>[
                        // Nota : el source "se sale" de este tipo..La fuente para este campo son los campos del modelo en que está
                        "TYPE"=>"Path",
                        "PATH"=>"#../../FIELDS/[[KEYS]]"
                    ]
                ],
                "DEFAULT"=>[
                    "LABEL"=>"Estado por defecto",
                    "TYPE"=>"String",
                    "REQUIRED"=>true,
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../STATES/[[KEYS]]"
                    ]
                ],
                "LISTENER_TAGS"=>[
                    "LABEL"=>"Listeners",
                    "HELP"=>"Los listeners son callbacks utilizados para test,entrada y salida de estados",
                    "TYPE"=>"Dictionary",
                    "KEEP_KEY_ON_EMPTY"=>"FALSE",
                    "VALUETYPE"=>[
                        "LABEL"=>"Callback",
                        "TYPE"=>"TypeSwitcher",
                        "ON"=>[
                            ["IS"=>"String", "THEN"=>"Method"],
                            ["IS"=>"Object", "THEN"=>"ParametrizedMethod"]
                        ],
                        "ALLOWED_TYPES"=>[
                            "Method"=>["LABEL"=>"Metodo","TYPE"=>"String"],
                            "ParametrizedMethod"=>[
                                "LABEL"=>"Metodo con parametros",
                                "TYPE"=>"Container",
                                "FIELDS"=>[
                                    "METHOD"=>[
                                        "LABEL"=>"Metodo",
                                        "TYPE"=>"String"
                                    ],
                                    "PARAMS"=>[
                                        "LABEL"=>"Parametros",
                                        "TYPE"=>"Array",
                                        "ELEMENTS"=>[
                                            "LABEL"=>"Valor",
                                            "TYPE"=>"String"
                                        ]
                                    ]
                                ]
                                ]
                            ]
                    ]
                ],
                "STATES"=>[
                    "LABEL"=>"Estados",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>[
                        "LABEL"=>"Estado",
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "LISTENERS"=>[
                                "LABEL"=>"Listeners",
                                "TYPE"=>"Container",
                                "FIELDS"=>[
                                    "TEST"=>$testCallbacks,
                                    "ON_ENTER"=>$callbackDef,
                                    "ON_EXIT"=>$leaveCallbacks
                                ]
                            ],
                            "ALLOW_FROM"=>[
                                "LABEL"=>"Permitir desde",
                                "TYPE"=>"Array",
                                "ELEMENTS"=>[
                                    "LABEL"=>"Estados",
                                    "TYPE"=>"String",
                                    "SOURCE"=>[
                                        "TYPE"=>"Path",
                                        "PATH"=>"#../../../[[KEYS]]"
                                    ]
                                ]
                            ],
                            "FIELDS"=>[
                                "LABEL"=>"Estado de los campos",
                                "TYPE"=>"Container",
                                "FIELDS"=>[
                                    "EDITABLE"=>[
                                        "LABEL"=>"Campos Editables",
                                        "TYPE"=>"Array",
                                        "ELEMENTS"=>[
                                            "LABEL"=>"Campo",
                                            "TYPE"=>"String",
                                            "SOURCE"=>[
                                                "TYPE"=>"Path",
                                                // De nuevo, esto hace referencia a los campos del modelo padre.
                                                "PATH"=>"#../../../../../../FIELDS/[[KEYS]]",
                                                "PREPEND"=>[["LABEL"=>"*","VALUE"=>"*"]]
                                            ]
                                        ]
                                    ],
                                    "REQUIRED"=>[
                                        "LABEL"=>"Requeridos",
                                        "TYPE"=>"Array",
                                        "ELEMENTS"=>[
                                            "LABEL"=>"Campo",
                                            "TYPE"=>"String",
                                            "SOURCE"=>[
                                                "TYPE"=>"Path",
                                                // De nuevo, esto hace referencia a los campos del modelo padre.
                                                "PATH"=>"#../../../../../../FIELDS/[[KEYS]]",
                                                "PREPEND"=>[["LABEL"=>"*","VALUE"=>"*"]]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            "PERMISSIONS"=>[
                                "LABEL"=>"Permisos",
                                "TYPE"=>"Container",
                                "FIELDS"=>[
                                    "ADD"=>"/model/reflection/Permissions/types/PermissionSpec",
                                    "EDIT"=>"/model/reflection/Permissions/types/PermissionSpec",
                                    "VIEW"=>"/model/reflection/Permissions/types/PermissionSpec",
                                    "DELETE"=>"/model/reflection/Permissions/types/PermissionSpec"

                                ]
                            ],
                            "FINAL"=>[
                                "LABEL"=>"Es estado terminal",
                                "TYPE"=>"Boolean"
                            ],
                            "REJECT_TO"=>$rejectToCallbacks
                        ]

                    ]
                ]
            ]

        ],$parentType, $value,$validationMode);



    }
}
/*
'REJECT_TO'=>array(
                'STATES'=>array(
                    ps_order_state::STATE_ORDER_PAID=>$cancelledAct
                )
            )
 */
