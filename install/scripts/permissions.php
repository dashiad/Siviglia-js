<?php
include_once __DIR__.'/../config/localConfig.php';
include_once LIBPATH . '/startup.php';

$perms=\Registry::getService("permissions");
$perms->uninstall();
$perms->install();

$layers=\model\reflection\ReflectorFactory::getLayers();
array_walk($layers,function($val,$index) use ($perms){
    $objs=\model\reflection\ReflectorFactory::getObjectsByLayer($val["name"]);
    array_walk($objs,function($v1,$index) use ($perms)
    {
        $perms->addModule($v1->getClassName());
    });
});
$perms->addPermissionsToGroup(array(
    \PermissionsManager::PERMS_REFLECTION,
    \PermissionsManager::PERMS_ADMIN,
    \PermissionsManager::PERMS_EDIT,
    \PermissionsManager::PERMS_VIEW,
    \PermissionsManager::PERMS_CREATE,
    \PermissionsManager::PERMS_DESTROY,
    \PermissionsManager::PERMS_DISABLE
));

$perms->createUserGroup("God");
$perms->a
die();


$sites=\model\web\Site::getAllSites();
$nSites=$sites->count();
for($k=0;$k<$nSites;$k++)
{
    echo $sites[$k]->namespace."\n";
    $curSite=$sites[$k]->namespace;
    $cSite=\model\web\Site::getSiteFromNamespace($curSite);
    $config=$cSite->getConfig()->getPermissions();
}

