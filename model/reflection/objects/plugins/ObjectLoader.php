<?php
namespace lib\reflection\plugins;

class ObjectLoader extends \model\reflection\base\SystemPlugin
{
        function REBUILD_MODELS($level)
        {
            if($level!=1)
                return;
            printPhase("Cargando Modelos");
            for($kk=0;$kk<count($Config["PACKAGES"]);$kk++) {
              $package = $Config["PACKAGES"][$kk];

                printSubPhase("Cargando Modelos de ".$package);
                // Se mira si existe una quickDef
                $pkg=\model\reflection\ReflectorFactory::getPackage($package);
                $quickDef=$pkg->getQuickDefinitions();

                printSubPhase("Procesando QuickDefs");
                //$quickDef=$layerConf->definition["QuickDef"];
                if(!$quickDef)
                    continue;

                foreach($quickDef as $key=>$value)
                {
                    printItem("Procesando $key");
                    if(ReflectionFactory::getModel($key))
                        continue;
                    $instance=\model\reflection\Model\QuickModelGenerator::createFromQuick($key,$pkg,$value);
                    ReflectorFactory::addModel($pkg,$key,$instance);
                    $instance->saveDefinition("objects");
                }
            }
            printSubPhase("Generando Relaciones Inversas");
            $this->iterateOnModels("generateExtRelationships");
            printSubPhase("Generando clases modelo temporales");
            $this->iterateOnModels("generateTempModelClasses");
        }

        function generateExtRelationships($layer,$name,$instance)
        {
            $instance->createDerivedRelations();
        }


        function generateTempModelClasses($layer,$name,$instance)
        {
            echo "GENERANDO PARA $layer $name<br>";
            $modelClassFile=new \model\reflection\Model\ModelClass($instance);
            if($modelClassFile->mustRebuild())
                $modelClassFile->generate();
            $instance->saveDefinition(); // se guarda una primera version del objeto compilado.

        }
        function saveModel($layer,$name,$instance)
        {
            $instance->save();
        }

        function SAVE_SYSTEM($level)
        {
            if($level!=1)return;
            $this->iterateOnModels("saveModel");
        }

        function saveModelConfig($layer,$name,$instance)
        {
            $instance->config->save();
        }
        function END_SYSTEM_REBUILD($level)
        {
            if($level!=2)return;
            $this->iterateOnModels("saveModelConfig");
        }
}
?>
