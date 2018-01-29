<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 20/01/2018
 * Time: 15:49
 */

namespace model\reflection\Storage\Mysql;

class MysqlProcessor extends \model\reflection\Processor {


    function REBUILD_MODELS($step)
    {
        if($step!=2)
            return;
        printPhase("Incializando Storage de Modelos [Mysql]");

        // Creacion del soporte en base de datos de las tablas.

        global $APP_NAMESPACES;
        foreach($APP_NAMESPACES as $layer)
        {
            // $layer=\model\reflection\ReflectorFactory::getLayer($layer);
            $objects=\model\reflection\ReflectorFactory::getObjectsByLayer($layer);
            foreach($objects as $objName=>$modelDef)
            {
                printItem("Generando $objName");
                $this->generateStorage($objName,$modelDef,$layer);
            }
        }

    }
    function generateStorage($objName,$modelDef,$layer)
    {
        $storageConfig=$modelDef->getStorageConfiguration("MYSQL");
        if(!$storageConfig)
        {
            $optionsDefinition=\model\reflection\Storage\Mysql\MysqlOptionsDefinition::createDefault($modelDef);
        }
        else
        {
            $optionsDefinition=new \model\reflection\Storage\Mysql\MysqlOptionsDefinition($modelDef,$storageConfig);
        }

        $layerObj=\model\reflection\ReflectorFactory::getLayer($layer);
        $curSerializer=$layerObj->getSerializer();
        if($curSerializer->getSerializerType()!="MYSQL")
        {
            return;
        }
        $modelDef->addStorageConfiguration("MYSQL",$optionsDefinition->getDefinition());
        printItem("<b>Creando Tablas Mysql para relaciones multiples</b>");
        $curSerializer->createStorage($modelDef,$optionsDefinition->getDefinition());


    }

    function REBUILD_DATASOURCES($step)
    {

        if($step!=2)
            return;

        printPhase("Generando Datasources Mysql");

        // Creacion del soporte en base de datos de las tablas.
        $this->iterateOnModels("rebuildDataSources");
    }
    function rebuildDataSources($layer,$objName,$modelDef)
    {

        $curSerializer=$modelDef->getSerializer();
        if($curSerializer->getSerializerType()!="MYSQL")
            return;

        // Se obtienen los datasources generados via el editor.Estan en el fichero Definition.js, key "Queries"
        $modelPath=$modelDef->objectName->getDestinationFile();
        $processedDs=array();
        if(is_file($modelPath."/Definition.json"))
        {
            $fcontents=file_get_contents($modelPath."/Definition.json");
            $def=json_decode($fcontents,true);
            if($def["Queries"])
            {
                foreach($def["Queries"] as $key=>$value)
                {
                    // Se anota este datasource como procesado.
                    $processedDs[]=$key;
                    $msQ=\model\reflection\Storage\Mysql\MysqlDefinition::createDatasourceFromQuery($modelDef,$key,$value);
                }
            }

        }

        foreach($modelDef->datasources as $dsKey=>$dsValue)
        {
            // Si es un datasource que ya hemos procesado (estaba en las definiciones generadas por el editor), no lo re-procesamos.
            if(in_array($dsKey,$processedDs))
                continue;
            $newDef=new \model\reflection\Storage\Mysql\MysqlDefinition($modelDef,$dsKey,$dsValue);
            $srcRelation=$dsValue->getSourceRelation();

            $type="normal";
            if($srcRelation)
            {
                if(!is_array($srcRelation))
                {
                    $field=$modelDef->getFieldOrAlias($srcRelation);
                    $type="inverse";
                }
                else
                    $type="mxn";
            }

            switch($type)
            {
                case "mxn":
                {
                    $newDef->createMxNDsDefinition(0);
                }break;
                case "inverse":
                {
                    $newDef->createInverseDsDefinition($srcRelation);
                }break;
                default:
                {
                    $newDef->create();
                }
            }

        }
    }
}