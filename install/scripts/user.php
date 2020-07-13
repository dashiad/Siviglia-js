<?php

    include_once __DIR__.'/../config/localConfig.php';
    include_once LIBPATH . '/startup.php';
    Startup::initializeHTTPPage();
    $storage=\Registry::getService("storage");
    $serializer=$storage->getSerializerByName("default");

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
