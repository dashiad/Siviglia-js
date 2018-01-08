<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 22/07/13
 * Time: 18:18
 * To change this template use File | Settings | File Templates.
 */

namespace json\datasources;
include_once(PROJECTPATH."/backoffice/lib/output/json/JsonDataSource.php");

class UnassignedRefundShipmentRequests extends \JsonDataSource{
    function __construct()
    {
            parent::__construct(array(
                "refunds"=>array(
                    "object"=>"Shipment",
                    "datasource"=>"UnassignedPayment"
                    )
                ),
                array(
                    "refunds"=>array("source"=>"Refunds")
                )
            );
    }
}