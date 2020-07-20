<?php

namespace model\tests\User\datasources;
/**
 * FILENAME:/var/www/percentil/backoffice//backoffice/objects/Bag/datasources/FullBag.php
 * CLASS:FullBag
 *
 *
 **/
class Multiple
{
    static  $definition=array(

                "ROLE" => "MULTIPLE",
                "PARAMS" => array(
                    "id" => array(
                        "MODEL" => "/model/tests/User",
                        "FIELD" => "id",
                    )
                ),
                "DATASOURCES" => array(
                    "FullList" => array(
                        "MODEL" => "/model/tests/User",
                        "DATASOURCE" => "FullList"
                    ),
                    "Posts" => array(
                        "MODEL" => "/model/tests/Post",
                        "DATASOURCE" => "FullList"
                    )
                )
    );

}

