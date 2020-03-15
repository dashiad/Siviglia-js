<?php

namespace sites\backend\routes\Definitions\Jobs;


class Pages
{
    static $definition = array(
        "jobs" => array("TYPE" => "PAGE", "PAGE" => "JobList"),
        "viewJob"=> array("TYPE" => "PAGE", "PAGE" => "JobDetail"),
    );
}