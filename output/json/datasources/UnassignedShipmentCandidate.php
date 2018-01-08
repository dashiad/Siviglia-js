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

class UnassignedShipmentCandidate extends \JsonDataSource{
    function __construct()
    {
            parent::__construct(array(
                "candidates"=>array(
                    "object"=>"Shipment",
                    "datasource"=>"UnassignedShipmentCandidate"
                    )
                ),
                array(
                    "candidates"=>array(
                        "idSource"=>mysql_escape_string($_GET["idSource"]),
                        "relatedTo"=>mysql_escape_string($_GET["related"]),
                        "dayOffset"=>mysql_escape_string($_GET["dayOffset"])
                    )
                )
            );
    }
}