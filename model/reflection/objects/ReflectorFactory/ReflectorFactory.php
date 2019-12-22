<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 20/01/2018
 * Time: 15:54
 */

namespace model\reflection;
   // Eventos:
   // START_SYSTEM_REBUILD ($sys) // Initializes the system rebuilding
   // REBUILD_MODELS ($sys) // Models are rebuilt
   // REBUILD_STORAGES ($sys) // Storage systems are rebuilt
   // REBUILD_DATASOURCES ($sys)
   // REBUILD_ACTIONS ($sys)
   // REBUILD_VIEWS($sys)
   // END_SYSTEM_REBUILD($sys)

   // Ejemplos de otros eventos:
   // ADD_OBJECT(layer,name)
   // ADD_FIELD(layer,name,
include_once(PROJECTPATH."/model/reflection/objects/Model/Model.php");

   class ReflectorFactory
   {
       static $objectDefinitions;
       static $factoryLoaded=false;
       static $layers=array();
       static $relationMap=null;

       static function getModel($modelName,$layer=null)
       {
           //echo "MODEL:$modelName";
           $nameObj=\lib\model\ModelService::getModelDescriptor($modelName,$layer);
           $layer=$nameObj->layer;
           $className=$nameObj->className;
           if($nameObj->isPrivate())
               $className=$nameObj->getNamespaceModel().'\\'.$className;

           if(isset(ReflectorFactory::$objectDefinitions[$layer][$className]))
               return ReflectorFactory::$objectDefinitions[$layer][$className];
           if(ReflectorFactory::$factoryLoaded==true)
               return null;

           ReflectorFactory::loadFactory();
           return ReflectorFactory::$objectDefinitions[$layer][$className];
       }
       static function getObjectsByLayer($layer)
       {
           if(!ReflectorFactory::$factoryLoaded)
           {
               ReflectorFactory::loadFactory();
           }
           return io(ReflectorFactory::$objectDefinitions,$layer,array());
       }
       static function getLayers()
       {
           $modelPath=realpath(\Registry::getService("paths")->getModelPath());
           $dir = new \DirectoryIterator($modelPath);
           $layers=array();
           foreach ($dir as $fileinfo) {
               if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                   if($fileinfo->getFilename() != "reflection")
                       $layers[]=array(
                           "name"=>$fileinfo->getFilename(),
                           "path"=>$fileinfo->getRealPath()
                       );
               }
           }
           return $layers;
       }
       static function getPackageNames()
       {

       }





       private static function getObjectCache($pointer,$layer,& $existingModels,& $objectDefinitions)
        {
            for($k=0;$k<count($pointer);$k++)
            {
                $existingModels[$pointer[$k]["class"]]=$layer;
                $objectDefinitions[$layer][$pointer[$k]["class"]]=new \model\reflection\Model("model\\".$pointer[$k]["layer"].'\\'.$pointer[$k]["class"]);
                if(isset($pointer[$k]["subobjects"]))
                    ReflectorFactory::getObjectCache($pointer[$k]["subobjects"],$layer,$existingModels,$objectDefinitions);
            }

        }
       static function loadFactory()
       {
           $layers=ReflectorFactory::getLayers();
           $objectDefinitions=array();
           $existingModels=array();
           for($k=0;$k<count($layers);$k++) {
               $layers[$k]["objects"] = ReflectorFactory::getLayerObjects($layers[$k]["name"], $layers[$k]["path"],null);
               ReflectorFactory::getObjectCache($layers[$k]["objects"],$layers[$k]["name"],$existingModels,$objectDefinitions);
           }
           ReflectorFactory::$objectDefinitions=$objectDefinitions;


           // Hay que inicializar primero aquellos objetos que no extienden de nada, y, a partir de ahi,
           // los objetos que heredan de cualquier otro.
           $parsedModels=array();
           while(count($existingModels)>0)
           {
               $newModels=array();
               foreach($existingModels as $name=>$layer)
               {
                   $cur=ReflectorFactory::$objectDefinitions[$layer][$name];

                   if(isset($cur->definition["EXTENDS"]))
                   {
                       $objName=\lib\model\ModelService::getModelDescriptor($cur->definition["EXTENDS"]);
                       $normalized=$objName->getNormalizedName();
                       if(!$parsedModels[$normalized])
                       {
                           $newModels[$name]=$layer;
                           continue;
                       }
                   }
                   $parsedModels[$name]=1;
                   $cur->initialize();
               }
               $existingModels=$newModels;
           }
           // Lo siguiente no es tecnicamente cierto; la factoria no esta cargada.
           // Pero, si no la establecemos a cargada aqui, si algun alias intenta acceder a los modelos,
           // como aun no estarian cargados (factoryLoaded==false), comenzaria un bucle.Volverian a intentar
           // ser cargados (se volveria a llamar a esta funcion)
           ReflectorFactory::$factoryLoaded=true;
           global $APP_NAMESPACES;
           foreach($APP_NAMESPACES as $curLayer)
           {
               if(isset(ReflectorFactory::$objectDefinitions[$curLayer])) {
                   foreach (ReflectorFactory::$objectDefinitions[$curLayer] as $curObj => $curModel) {
                       $curModel->initializeAliases();
                   }
               }
           }
       }
       static function getRelationMap()
       {
           if(ReflectorFactory::$relationMap)
               return ReflectorFactory::$relationMap;
           global $APP_NAMESPACES;
           foreach($APP_NAMESPACES as $curLayer)
           {
               foreach(ReflectorFactory::$objectDefinitions[$curLayer] as $curObj => $curModel)
               {
                   $objects[]=$curObj;
                   $cK=array_keys($curModel->getIndexFields());
                   $keys[$curObj]=$cK[0];
                   $simpleRel=$curModel->getSimpleRelations();
                   foreach($simpleRel as $relName=>$relObj)
                       $relations[$curObj][$relObj->getRemoteModelName()]=$relName;
               }
           }
           $temp=array("objects"=>array_keys($relations),"relations"=>$relations,"keys"=>$keys);
           $temp["distances"]=ReflectorFactory::buildDistances($temp["objects"],$temp["relations"],$temp["keys"]);
           ReflectorFactory::$relationMap=$temp;
           return $temp;

       }
       static function buildDistances($objects,$relations,$oKeys)
       {
           $curDistance=0;
           while(1)
           {

               $cont=0;

               for($k=0;$k<count($objects);$k++)
               {
                   $curObject=$objects[$k];

                   if($curDistance==0)
                   {
                       foreach($relations[$curObject] as $key=>$value)
                       {
                           $distances[$curObject][$key]=1;
                           $paths[$curObject][$key]="/".$key."[$value";
                           $queries[$curObject][$value]=$curObject." INNER JOIN $key ON ".$curObject.".$value=$key.".$oKeys[$key];

                           if(!$relations[$key])
                           {
                               $relations[$key]=array();
                               $objects[]=$key;
                           }
                           $distances[$key][$curObject]=1;
                           $paths[$key][$curObject]="/".$curObject."|$value";
                           $queries[$key][$curObject]=$key." INNER JOIN $curObject ON ".$curObject.".$value=$key.".$oKeys[$key];

                       }
                       $cont=1;
                       continue;
                   }
                   $adist=& $distances[$curObject];


                   foreach($adist as $bName=>$bdist)
                   {
                       if($bdist==$curDistance)
                       {
                           foreach($distances[$bName] as $cName=>$cDist)
                           {
                               if($cName == $curObject)
                                   continue;
                               $fullDist=$cDist+$curDistance;
                               if(!$adist[$cName] || ($adist[$cName] > $fullDist))
                               {
                                   $cont++;
                                   $adist[$cName]=$fullDist;
                                   $paths[$curObject][$cName]=$paths[$curObject][$bName].$paths[$bName][$cName];
                                   $queries[$curObject][$cName]=$queries[$curObject][$bName]." ".substr($queries[$bName][$cName],strpos($queries[$bName][$cName]," ")+1);
                               }
                           }
                       }
                   }

               }
               $curDistance++;
               if($cont == 0)
                   break;
           }
           foreach($queries as $o1=>$val)
           {
               foreach($val as $o2=>$text)
                   $queries[$o1][$o2]="SELECT ".$o1.".*,".$o2.".* FROM ".$text;
           }
           return array($distances,$paths,$queries);
       }

       static function getLayer($layer)
       {
           if(!isset(ReflectorFactory::$layers[$layer]))
               ReflectorFactory::$layers[$layer]=new \model\reflection\base\Layer($layer);
           return ReflectorFactory::$layers[$layer];
       }
       static function addModel($layer,$name,$instance)
       {
           if(!ReflectorFactory::$factoryLoaded)
           {
               ReflectorFactory::loadFactory();
           }
           ReflectorFactory::$objectDefinitions[$layer][$name]=$instance;
       }
       static function getSerializer($layer)
       {
           return Registry::$registry["serializers"][$layer];
       }
   }

   class SystemReflector
   {
       var $plugins;
       function __construct()
       {
           $dir=opendir(LIBPATH."/reflection/plugins");
           while($filename=readdir($dir))
           {
               if($filename!="." && $filename!="..")
               {
                   // Se elimina la extension.
                   $className='\lib\reflection\plugins\\'.basename($filename,".php");
                   $instance=new $className();
                   $this->pluginList[]= new $className();
               }
           }
       }

       function __call($funcName,$args)
       {
           foreach($this->pluginList as $key=>$value)
               call_user_method_array($funcName,$value,$args);
       }

   }

?>
