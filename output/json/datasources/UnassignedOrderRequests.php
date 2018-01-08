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

class UnassignedOrderRequests extends \JsonDataSource{
    function __construct()
    {
            parent::__construct(array(
                "orders"=>array(
                    "object"=>"Shipment",
                    "datasource"=>"UnassignedPayment"

                    )
                ),
                array(
                    "orders"=>array(
                        "source"=>array("PSOrders"),
                        "errCode"=>array("4","5","17"),
                        "sdate"=>mysql_escape_string('2012-12-31 23:00:00'),
                        "edate"=>mysql_escape_string($_GET["edate"]),
                        "order"=>mysql_escape_string($_GET["order"]),
                        "orderDirection"=>mysql_escape_string($_GET["orderDirection"])
                    )
                )
            );
    }
}