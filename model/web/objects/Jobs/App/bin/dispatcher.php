<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->init();