<?php
require_once('localConfig.php');
require_once(LIBPATH."/startup.php");

use \model\web\Jobs\App\Jobs\Config;

define("JOBS_NAMESPACE", "model\\web\\Jobs\\App\\Jobs\\");
define("DEBUG", Config::get('main', 'debug'));