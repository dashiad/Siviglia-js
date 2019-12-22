<?php
namespace lib\reflection\plugins;

class SysInstall extends \model\reflection\base\SystemPlugin
{

        function START_SYSTEM_REBUILD($level)
        {
            if($level!=1)
                return;
            printPhase("Inicializando Storage");
            // Inicializacion de serializadores
            $packages=\model\reflection\ReflectorFactory::getPackageNames();
            for($kk=0;$kk<count($packages);$kk++)
            {
                $pkg=new \model\reflection\Package($packages[$kk]);
                $pkg->rebuildStorage();
            }
            printSubPhase("Instalando soporte de permisos");
            include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");

            if(in_array("web",$packages))
                $permDB="web";
            else
                $permDB=$packages[0];
            $layer=\model\reflection\ReflectorFactory::getLayer($permDB);

            $oPerm=new \AclManager($layer->getSerializer());
            $oPerm->uninstall();

            $oPerm->install();
        }

        function runObjectSetup($layer,$name,$model)
        {
            $model->runSetup();
        }

        function END_SYSTEM_REBUILD($level)
        {
            if($level==2)
                $this->iterateOnModels("runObjectSetup");
        }
            // Execution of startup scripts, both for objects and data types




}

?>
