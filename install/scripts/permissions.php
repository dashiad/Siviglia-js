<?php
include_once __DIR__.'/../config/localConfig.php';
include_once LIBPATH . '/startup.php';
Startup::initializeHTTPPage();
$perms=\Registry::getService("permissions");
$perms->uninstall();
$perms->install();

$sites=\model\web\Site::getAllSites();
$nSites=$sites->count();
for($k=0;$k<$nSites;$k++)
{
    echo $sites[$k]->namespace."\n";
    $curSite=$sites[$k]->namespace;
    $cSite=\model\web\Site::getSiteFromNamespace($curSite);
    $config=$cSite->getConfig()->getPermissions();

}
