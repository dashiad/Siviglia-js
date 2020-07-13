<?php
include_once __DIR__.'/../config/localConfig.php';
include_once LIBPATH . '/startup.php';

$perms=\Registry::getService("permissions");

$perms->uninstall();
$perms->install();
