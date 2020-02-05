<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\Server;

$server = new Server();
$server->init();