<?php
namespace web\objects\WebUser;
class Setup {
    function install()
    {
        echo "INSTALLING WEBUSER<br>";

        include_once(PROJECTPATH."/lib/storage/StorageFactory.php");
        include_once(PROJECTPATH."/web/objects/WebUser/WebUser.php");
        $ser=\Registry::getService("storage")->getSerializerByName('web');
        $oUser=new \web\objects\WebUser($ser);

        try
        {
            $oUser->loadByUsername("admin");
            $id=$oUser->USER_ID;
        }
        catch(\Exception $e)
        {
            $oUser->LOGIN='admin';
            $oUser->PASSWORD='admin123';
            $oUser->EMAIL='admin@localhost.com';
            $oUser->save();
            $id=$oUser->USER_ID;
        }



        include_once(LIBPATH."/model/permissions/AclManager.php");
        $manager=new \AclManager($ser);
        if(!$manager->get_object($id))
        {

            $aclId=$manager->add_object("user",$id);
            $groupId=$manager->get_group_id(null,'FullAdmin',\AclManager::ARO);
            $manager->add_group_object($groupId,$aclId);
        }

        try
        {
            $oUser->loadByUsername("test");
        }
        catch(\Exception $e)
        {
            $oUser->LOGIN='test';
            $oUser->PASSWORD='test123';
            $oUser->EMAIL='test@localhost.com';
            $oUser->save();
        }
    }
}
?>
