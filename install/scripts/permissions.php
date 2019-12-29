<?php
include_once __DIR__.'/../config/localConfig.php';
include_once LIBPATH . '/startup.php';

$perms=\Registry::getService("permissions");
$perms->uninstall();
$perms->install();



$layers=\model\reflection\ReflectorFactory::getLayers();
array_walk($layers,function($val,$index) use ($perms){

    $objs=\model\reflection\ReflectorFactory::getObjectsByPackage($val["name"]);
    array_walk($objs,function($v1,$index) use ($perms,$val)
    {
        $perms->createGroup("/".$val["name"]."/".$v1->getClassName(),PermissionsManager::PERM_TYPE_MODULE);
    });
});



$perms->addToGroup(array(
    \PermissionsManager::PERMS_REFLECTION,
    \PermissionsManager::PERMS_ADMIN,
    \PermissionsManager::PERMS_EDIT,
    \PermissionsManager::PERMS_VIEW,
    \PermissionsManager::PERMS_CREATE,
    \PermissionsManager::PERMS_DESTROY,
    \PermissionsManager::PERMS_DISABLE,
    \PermissionsManager::PERMS_LIST,
    \PermissionsManager::PERMS_REPORT,
),"/default",\PermissionsManager::PERM_TYPE_PERMISSIONS,true);


$serializer=\lib\storage\StorageFactory::getSerializerByName("default");

$user=new \model\web\WebUser($serializer);
$user->LOGIN="admin";
try
{
    $user->loadFromFields();
}
catch(\Exception $e) {
    $user->PASSWORD = "admin";
    $user->EMAIL = "admin@admin.com";
    $user->active = true;
    $user->{"*last_passwd_gen"}->setAsNow();
    $user->save();
}


$perms->addToGroup(array($user->USER_ID),"/God",\PermissionsManager::PERM_TYPE_USER);

$perms->addPermissions(
    array("GROUP"=>"/God"),array("GROUP"=>"/AllModules","RAW"=>true),array("GROUP"=>"/AllPermissions","RAW"=>true)
);



$sites=\model\web\Site::getAllSites();
$nSites=$sites->count();
for($k=0;$k<$nSites;$k++)
{
    $cSite=$sites[$k];
    $perms->createGroup("/".$cSite->namespace,PermissionsManager::PERM_TYPE_MODULE);

    $curSite=$sites[$k]->namespace;
    $cSite=\model\web\Site::getSiteFromNamespace($curSite);
    $config=$cSite->getConfig()->getPermissions();
}

