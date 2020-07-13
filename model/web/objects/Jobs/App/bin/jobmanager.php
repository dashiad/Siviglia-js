<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\JobManager;

global $argv;

$id = $argv[1] ?? null;

$manager = new JobManager($id);
$manager->init();
