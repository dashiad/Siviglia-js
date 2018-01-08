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

class UnassignedBagRequests extends \JsonDataSource{
    function __construct()
    {
            parent::__construct(array(
                "bags"=>array(
                    "object"=>"BagRequest",
                    "datasource"=>"Unassigned"

                    )
                ),
                array(
                    "bags"=>array(
                        "paymentLevel"=>intval($_GET["paymentLevel"]),
                        "sdate"=>mysql_escape_string($_GET["sdate"]),
                        "edate"=>mysql_escape_string($_GET["edate"]),
                        "order"=>mysql_escape_string($_GET["order"]),
                        "orderDirection"=>mysql_escape_string($_GET["orderDirection"])
                    )
                )
            );
    }
}