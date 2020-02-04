<?php
require(__DIR__.'/bootstrap.php');

global $argc, $argv;

if ($argc!=3) {
    echo "Sintaxis: worker.php <classname> <json_params>".PHP_EOL;
    exit(1);
}

$className = $argv[1];
$params    = json_decode($argv[2], true);
$worker = new $className($params);
$worker->run();