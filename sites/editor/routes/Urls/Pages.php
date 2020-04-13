<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 27/07/15
 * Time: 19:22
 */

namespace sites\editor\routes\Urls;


class Pages
{
    static $definition = array(
        "/" => "index",
        "/Page/{id_page}/edit"=>"page",
        "/Jobs/{job_id}"=>"viewJob",
        "/Jobs/{job_id}/view"=>"viewJob",
        "/error"=>"error",
        "/Site/{namespace}"=>"site",
        "/login" => "login"
    );
}
