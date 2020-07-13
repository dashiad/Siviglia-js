<?php
  namespace lib\reflection\plugins;

  class PermissionsProcessor extends \model\reflection\base\SystemPlugin
  {

        function START_SYSTEM_REBUILD($level)
        {
            if($level!=2)
                return;

            printPhase("Inicializando permisos globales");
            global $Config;
            if(in_array("web",$Config["PACKAGES"]))
                $tdb="web";
            else
                $tdb=$Config["PACKAGES"][0];

            $layer=\model\reflection\ReflectorFactory::getLayer($tdb);
            $permissions=$layer->getPermissionsDefinition();
            if($permissions)
            {
                include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
                $this->permsManager=new \AclManager($layer->getSerializer());
                $oPerms=& $this->permsManager;
                $perms=$layer->getPermissionsDefinition();

                if($perms["Users"])
                    $oPerms->createPermissions($perms["Users"],0,0,\AclManager::ARO);
                if($perms["Permissions"])
                    $oPerms->createPermissions($perms["Permissions"],0,0,\AclManager::ACO);
                if($perms["Objects"])
                    $oPerms->createPermissions($perms["Objects"],0,0,\AclManager::AXO);

                if($perms["DefaultPermissions"])
                {
                    for($j=0;$j<count($perms["DefaultPermissions"]);$j++)
                    {
                        $curPerm=$perms["DefaultPermissions"][$j];
                        $oPerms->add_acl($curPerm[0],$curPerm[1],$curPerm[2],(!$curPerm[3]?1:0));
                    }
                }
            }

        }
        function REBUILD_MODELS($level)
        {
            if($level!=2)
                return;
            printPhase("Generando permisos para modelos");

            $packages=\model\reflection\ReflectorFactory::getPackageNames();
            if(in_array("web",$packages))
                $tdb="web";
            else
                $tdb=$packages[0];
            $layer=\model\reflection\ReflectorFactory::getLayer($tdb);
            $this->permsManager=new \AclManager($layer->getSerializer());

            $packages=\model\reflection\ReflectorFactory::getPackageNames();
            for($kk=0;$kk<count($packages);$kk++)
            {
                $package=$packages[$kk];
                $pkg=new \model\reflection\Package($package);
                $objList=$pkg->getModels();
                foreach($objList as $objName=>$objDef)
                {
                    $perms=$objDef->getPermissionsDefinition();

                    if(!$perms)
                        continue;
                    // permsManager se ha inicializado en el metodo START_SYSTEM_REBUILD

                    $perms->install($this->permsManager);
                }
            }
            // Se crea el usuario 0 dentro de Anonymous.
            $aclId=$this->permsManager->add_object("user","0");
            $groupId=$this->permsManager->get_group_id(null,'Anonymous',\AclManager::ARO);
            $this->permsManager->add_group_object($groupId,$aclId);
        }
   }

?>
