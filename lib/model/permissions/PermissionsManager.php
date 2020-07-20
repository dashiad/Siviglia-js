<?php
namespace lib\model\permissions;
include_once(LIBPATH."/model/permissions/AclManager.php");
class PermissionManagerException extends \lib\model\BaseException
{
    const ERR_NO_SUCH_ITEM=1;
    const TXT_NO_SUCH_ITEM="El item de tipo [%type%] con id [%id%] no existe,";
}
class PermissionsManager {
    static $aclManager;

    const PERMS_REFLECTION="Reflection";
    const PERMS_ADMIN="Admin";
    const PERMS_EDIT="Edit";
    const PERMS_VIEW="View";
    const PERMS_CREATE="Create";
    const PERMS_LIST="List";
    const PERMS_DESTROY="Destroy";
    const PERMS_DISABLE="Disable";
    const PERMS_ACCESS="Access";
    const PERMS_CUSTOM1="Custom1";
    const PERMS_CUSTOM2="Custom2";
    const PERMS_CUSTOM3="Custom3";

    const DEFAULT_USER_GROUP="Users";
    const DEFAULT_ADMIN_GROUP="Admins";
    const DEFAULT_EDITORS_GROUP="Editors";
    const DEFAULT_REFLECTION_GROUP="Reflection";

    const PERMISSIONSPEC_PUBLIC="Public";
    const PERMISSIONSPEC_OWNER="Owner";
    const PERMISSIONSPEC_LOGGED="Logged";
    const PERMISSIONSPEC_ACL="ACL";
    const PERMISSIONSPEC_ROLE="Role";

    const PERM_TYPE_USER=\lib\model\permissions\AclManager::ARO;
    const PERM_TYPE_PERMISSIONS=\lib\model\permissions\AclManager::ACO;
    const PERM_TYPE_MODULE=\lib\model\permissions\AclManager::AXO;

    const PERM_SPEC_PUBLIC="PUBLIC";
    const PERM_SPEC_OWNER="OWNER";
    const PERM_SPEC_LOGGED="LOGGED";



    var $currentUserProfiles;
    static $permCache=array();
    var $effectiveProfiles;
    var $userService;
    var $siteService;
    var $serializer;

    function __construct($serializer) {

        $this->serializer=$serializer;
        if(!PermissionsManager::$aclManager)
            PermissionsManager::$aclManager=new \lib\model\permissions\AclManager($serializer);
        }

    function getPermissionsOverModel($user,$objName,$model)
    {
        if($model)
            $loaded=$model->isLoaded();
        else
            $loaded=false;

        if($loaded)
            $keyPart=implode("_",$model->__getKeys()->get());
        else
            $keyPart="NOKEYS";

        if($user->getId()) {
            if (isset(PermissionsManager::$permCache[$user->getId()][$objName][$keyPart])) {

                $cached = PermissionsManager::$permCache[$user->getId()][$objName][$keyPart];
                if (isset($cached))
                    return $cached;
            }
        }

        $permissions=PermissionsManager::$aclManager->getUserPermissions(
            PermissionsManager::$aclManager->getModulePath($model),
            ($loaded?PermissionsManager::$aclManager->getModelId($model):null),
            $user->getId());


        PermissionsManager::$permCache[$user->getId()][$objName][$keyPart]=$permissions;
        return $permissions;
    }





    function getFilteringCondition($model,$user=null,$requiredPerm="READ",$prefix="") {

        global $oCurrentUser;
        if($oCurrentUser->hasFullPermissions())
            return '';
        if(!is_object($model))
        {
            include_once(PROJECTPATH."/objects/".$model."/".$model.".php");
            $model=new $model();
        }

        // Se obtiene una lista completa de los perfiles.
        $effectiveProfiles=array("ANONYMOUS","LOGGED","OWNER");

        $modelDef=$model->getDefinition();

        // El permiso implicito para que un elemento salga en una lista, es que el usuario
        // tenga permisos $requiredPerm sobre los items.
        // Por lo tanto, hay que obtener todos los perfiles posibles del usuario, ver cuales de
        // ellos le dan permiso $requiredPermission, y crear una expresion SQL con ellos.
        $permsDefinition=$model->getDefaultPermissions();
        $states=$model->getStates();
        $usedStates=null;
        global $website;
        $userPermissions=array();
        $this->loadRolePermissions($website["DEFAULT_USER_PROFILES"],$effectiveProfiles,$userPermissions);
        if($website["USER_PROFILES"])
            $this->loadRolePermissions($website["USER_PROFILES"],$effectiveProfiles,$userPermissions);

        $modelDefaultPermissions=$model->getDefaultPermissions();
        if($modelDefaultPermissions)
            $this->loadRolePermissions($modelDefaultPermissions,$effectiveProfiles,$userPermissions);

        $defaultExpr=$this->getSQLSubExpression($userPermissions,$requiredPerm,$ownerProfiles,$modelDef,$prefix);

        // Hasta aqui, tenemos un array constante de permisos.
        // En caso de que haya estados, el array anterior hay que seguir procesandolo con cada uno de los estados.
        if(!$states)
            return $defaultExpr;

        $usedStates=array();
        $stateField=$states["FIELD"];
        $permValue=0;
        foreach($states["STATES"] as $name=>$definition) {
            if($definition["PERMISSIONS"]) {
                $statePermissions=$userPermissions;
                $this->loadRolePermissions($definition["PERMISSIONS"],$effectiveProfiles,$statePermissions);

                $expr=$this->getSQLSubExpression($statePermissions,$requiredPerm,$ownerProfiles,$modelDef,$prefix);
                if($expr!=="") {
                    if($expr==null) {
                        // Este estado no se puede ver en absoluto.
                        // Por ello, se indica en $usedStates que este estado ha sido utilizado,
                        // pero no se incluye ninguna expresion que permita seleccionarlo.
                    }
                    else
                        $queryParts[]="($stateField=".$permValue." AND (".$expr."))";

                    $usedStates[]=$permValue;
                }
            }
            $permValue++;
        }
        if(count($usedStates)==0)
            $expr=$defaultExpr;
        else {
            $expr="($defaultExpr AND $stateField NOT IN (".implode(",",$usedStates)."))";
            if(is_array($queryParts))
                $expr.=" OR ".implode(" OR ",$queryParts);
        }
        return $expr;

    }
    private function getSQLSubExpression(& $perms,$requiredPerm,& $ownerProfileType,& $modelDef,$prefix="") {

        global $oCurrentUser;
        $subParts=array();
        $possibleOwners=array();
        $noOwners="";

        foreach($perms as $key=>$value) {
            if(!in_array($requiredPerm,$value["ALLOW"]))
                continue;
            switch($key) {
                case "OWNER": {
                        if($oCurrentUser->isLogged()) {
                            $possibleOwners[]=$oCurrentUser->getId();
                        }
                        else
                            $noOwners="1 = -1";

                    }break;
                case "LOGGED": {
                        return "";
                    }break;
                case "ANONYMOUS": {
                        return "";
                    }break;
                case "ROOT": {
                        return "";
                    }break;
                default: {

                        // Para el resto de los roles, o es un USER_TYPE, o es un perfil basado en propiedad indirecta.
                        if($ownerProfileType[$key])
                        {
                            $parentUser=$oCurrentUser->data[$ownerProfileType[$key]["PARENT_USER"]];
                            if($parentUser)
                            $possibleOwners[]=$parentUser;
                        }
                        else {
                            if($oCurrentUser->isUserType($key))
                                return "";
                        }
                    }break

                    ;
            }
        }

        if(count($possibleOwners)>0)
            $subParts[]=$this->getSQLOwnershipExpression($modelDef,$possibleOwners,$prefix);
        else
        {
            if($noOwners!="")
                $subParts[]=$noOwners;
        }
        if(count($subParts)==0)
            return null;

        return implode(" OR ",$subParts);
    }
    function getSQLOwnershipExpression(& $modelDef,$possibleOwners,$prefix="") {
        if($modelDef["OWNERSHIP"]) {
            if(!is_array($modelDef["OWNERSHIP"]))
            {
                return ($prefix==""?"":$prefix.".").$modelDef["OWNERSHIP"]." IN (".implode(",",$possibleOwners).")";
            }
            else {
                // Esta solucion solo permitira que OWNERSHIP se refiera, como maximo,
                // a una tabla que esta a 1 salto de nosotros.Es decir, teniendo A con referencia
                // a B, A podra declarar que el campo que contiene al owner, es un campo de B.
                $remoteObject=$modelDef["OWNERSHIP"]["MODEL"];
                $localField=$modelDef["OWNERSHIP"]["LOCALFIELD"];
                // Se crea un modelo dummy para obtener informacion sobre ese objeto.
                include_once(PROJECTPATH."/objects/".$remoteObject."/".$remoteObject.".php");
                $dummyModel=new $remoteObject();
                $ownershipField=$dummyModel->getOwnershipField();
                $indexFields=$dummyModel->getIndexFields();

                return ($prefix==""?"":$prefix.".").$localField." IN (SELECT ".$indexFields." FROM ".$dummyModel->getTableName()." WHERE $ownershipField IN (".implode(",",$possibleOwners)."))";
            }
            // Falta la parte de los permisos heredados!!
        }
    }
    function getDefaultPrefixes($type)
    {
        $fgroup=null;
        $name=null;
        switch ($type) {
            case PermissionsManager::PERM_TYPE_MODULE: {
                $fgroup = "/AllModules/Sys";
                $name="MODULE";
            }
                break;
            case PermissionsManager::PERM_TYPE_PERMISSIONS: {
                $fgroup = "/AllPermissions/Sys";
                $name="PERMISSION";
            }
                break;
            case PermissionsManager::PERM_TYPE_USER: {
                $fgroup = "/AllUsers/Sys";
                $name="";
            }
                break;
        }
        return array("group"=>$fgroup,"name"=>$name);
    }
    function normalizeGroupPath($groupPath)
    {
        return str_replace('\\',"/",$groupPath);

    }
    function createGroup($groupPath,$type,$raw=false,$autocreate=true)
    {
        $prefix=$this->getDefaultPrefixes($type);
        $groupPath=$this->normalizeGroupPath($groupPath);
        if($raw==false)
            $groupPath=$prefix["group"].$groupPath;

        return PermissionsManager::$aclManager->getGroupFromPath($groupPath,$type,true);
    }
    function getGroup($groupPath,$type,$raw=false)
    {
        return $this->createGroup($groupPath,$type,false);
    }

    // Permissions debe ser un array
    function addToGroup($items,$group,$type,$create=true,$raw=false)
    {
        if(!is_array($items))
            $items=array($items);

        $fgroup=$this->normalizeGroupPath($group);
        $name="";
        if($raw==false) {
            $info=$this->getDefaultPrefixes($type);
            $fgroup=$info["group"].$fgroup;
            $name=$info["name"];
        }

        $gid=PermissionsManager::$aclManager->getGroupFromPath($fgroup,$type,true);

        for($k=0;$k<count($items);$k++) {
            $it=$items[$k];
            switch($type)
            {
                case PermissionsManager::PERM_TYPE_MODULE:{
                      $it=str_replace('\\','_',$it);
                }break;
                case PermissionsManager::PERM_TYPE_USER:{
                    $it=$it;
                }break;
            }
            try
            {
                $id=PermissionsManager::$aclManager->get_object($it,$type);
            }
            catch(\Exception $e)
            {
                if($create==true)
                    $id=PermissionsManager::$aclManager->add_object($name, $it,$type);
                else
                    throw new PermissionManagerException(PermissionManagerException::ERR_NO_SUCH_ITEM,array("type"=>$name,"id"=>$id));
            }
            PermissionsManager::$aclManager->add_group_object($gid, $id);
        }
    }

    function addPermissions($user,$module,$permissions,$allow=1)
    {
        if(isset($user["ITEM"])) {
            $uid = $user["ITEM"]->getId();
            $user["ITEM"]=$uid;
        }
        else
        {
            if(!isset($user["RAW"]))
            {
                $user["GROUP"]="/AllUsers/Sys".$user["GROUP"];
            }
        }
        if(isset($module["ITEM"]))
        {
            $mod=str_replace('\\',"_",$module);
            $module["ITEM"]=$mod;
        }
        else
        {
            if(!isset($module["RAW"]))
            {
                $module["GROUP"]="/AllModules/Sys".$module["GROUP"];
            }
        }
        if(!isset($permissions["RAW"]))
        {

            $permissions["GROUP"]="/AllPermissions/Sys/".$permissions["GROUP"];
        }

        $result=PermissionsManager::$aclManager->resolveAccessIds($user,$permissions,$module);
        PermissionsManager::$aclManager->add_acl_by_id($result["aco"],$result["aro"],$result["axo"],$allow,1);
    }

    function install()
    {
        PermissionsManager::$aclManager->install();
        $this->createGroup("/AllModules/Sys",PermissionsManager::PERM_TYPE_MODULE,true);
        $this->createGroup("/AllUsers/Sys",PermissionsManager::PERM_TYPE_USER,true);
        $this->createGroup("/AllPermissions/Sys",PermissionsManager::PERM_TYPE_PERMISSIONS,true);
        $this->createGroup("/CRUD",PermissionsManager::PERM_TYPE_PERMISSIONS,false);
        $this->addToGroup(array(
            PermissionsManager::PERMS_EDIT,
            PermissionsManager::PERMS_VIEW,
            PermissionsManager::PERMS_CREATE,
            PermissionsManager::PERMS_DESTROY,
        ),"/CRUD",PermissionsManager::PERM_TYPE_PERMISSIONS,true);
        $this->addToGroup(array(
            PermissionsManager::PERMS_REFLECTION,
            PermissionsManager::PERMS_ADMIN,
            PermissionsManager::PERMS_DISABLE,
            PermissionsManager::PERMS_LIST,
            PermissionsManager::PERMS_ACCESS,
        ),"/AllPermissions/Sys",PermissionsManager::PERM_TYPE_PERMISSIONS,true,true);


        $AdminUserId=\model\web\WebUser::createAdminUser($this->serializer);
        $this->addToGroup(array($AdminUserId),"/AllUsers",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER,true,true);
        $this->addPermissions(array("GROUP"=>"/AllUsers","RAW"=>true),array("GROUP"=>"/AllModules","RAW"=>true),array("GROUP"=>"/AllPermissions","RAW"=>true));

        $modelService=\Registry::getService("model");
        $packages=$modelService->getPackages();
        $m=$this;
        array_walk($packages,function($val,$index) use ($m){
            $val["instance"]->installPermissions($m);
        });

        $sites=\model\web\Site::getAllSites();
        $nSites=$sites->count();
        for($k=0;$k<$nSites;$k++)
        {
            $cSite=$sites[$k];
            $id_site=$cSite->id_site;
            $instance=$modelService->loadModel("/model/web/Site",array("id_site"=>$id_site));

            $instance->installPermissions($this);
        }
    }

    /*
     *  Las especificaciones de permisos de modelos, son para todas las operaciones de tipo CRUD.
     *  Cada operacion, tiene sus propios permisos:
     *   'PERMISSIONS'=>array(
                        "ADD"=>array(array("REQUIRES"=>"ADD","ON"=>"/model/web/Page")),
                        "DELETE"=>array(array("REQUIRED"=>"DELETE","ON"=>"/model/web/Page")),
                        "EDIT"=>[["REQUIRES"=>"ADMIN","ON"=>"/model/web/Page")),
                        "VIEW"=>[["REQUIREs"=>"VIEW","ON"=>"/model/web/Page"
                    )
         A esta funcion, se le pasa la operacion (ADD,DELETE,EDIT,VIEW), el modelo, y el usuario.
         Si el modelo es una string,se obtiene una instancia del modelo, y se le preguntan los permisos.

        ESTE ES UNO DE LOS PUNTOS DE ENTRADA PRINCIPALES, PARA PEDIR PERMISOS SOBRE MODELOS.

     */
    function canExecuteCRUD($CRUDOp, $model,$user=null)
    {
        if($user==null)
            $user=\Registry::getService("user");
        if(is_string($model))
        {
            // Si es una cadena, se crea una instancia, para poder preguntar los permisos.
            // Pero eso significa que no se nos ha pasado una instancia, por lo que a canAccess se le pasara
            // un null en model.
            $service=\Registry::getService("model");
            $instance=$service-getModel($model);
            $spec=$instance->getRequiredPermission($CRUDOp);
            $model=null;
        }
        else
            $spec=$model->getRequiredPermission($CRUDOp);

        return $this->canAccess($spec,$user,$model);
    }
    /*
     *
     *   ESTE ES EL PUNTO DE ENTRADA GENERAL, PARA CUALQUIER COSA QUE NO SEA UN MODELO (PAGINAS,DATASOURCES,ETC).
     *   LA ESPECIFICACION DEBE SER:
     *   "TYPE": (public,owner,logged,permission,role)
     *   Si type=="permission"=["REQUIRES"=>xx, "ON"=>"yy"]
     *   Si type=="role"=["ROLE"=>""]
     *
     */

    function canAccess($permsDefinition, $user=null, $model = null,$skipLoggedTest=false)
    {
        if($user==null)
            $user=\Registry::getService("user");
        for($k=0;$k<count($permsDefinition);$k++)
        {
            $curDef=$permsDefinition[$k];

            switch($curDef["TYPE"])
            {
                case PermissionsManager::PERMISSIONSPEC_PUBLIC:{
                    return true;
                }break;
                case PermissionsManager::PERMISSIONSPEC_OWNER:{
                    if(!$user->isLogged() && !$skipLoggedTest)
                        return false;
                    $owner = $model->getOwnershipField();
                    if ($owner->getValue() == $user->getId())
                        return true;
                    // Ya que el permiso "owner" es independiente de ACL, el usuario
                    // "admin" no tendria permisos sobre algo declarado con permisos "OWNER".
                    // Por lo tanto, se comprueba aqui directamente
                    $prefix=$this->getDefaultPrefixes(PermissionsManager::PERM_TYPE_USER);
                    return PermissionsManager::$aclManager->userBelongsToGroup("/AllUsers",$user->getId());
                }break;
                case PermissionsManager::PERMISSIONSPEC_LOGGED:{
                    return ($user->isLogged() && !$skipLoggedTest);
                }break;
                case PermissionsManager::PERMISSIONSPEC_ACL:
                    {
                        if(!$user->isLogged() && !$skipLoggedTest)
                            return false;
                        $axoPrefix=$this->getDefaultPrefixes(PermissionsManager::PERM_TYPE_MODULE);

                        $axoParam=["GROUP"=>$axoPrefix["group"].$curDef["ON"]];
                        if ($model)
                        {
                            $keys=$model->__getKeys();
                            $aKeys=$keys->get();
                            $axoParam["ITEM"]=implode("_", $aKeys);
                        }
                        if(!is_array($curDef["REQUIRES"]))
                            $curDef["REQUIRES"]=["ITEM"=>$curDef["REQUIRES"]];
                        else {
                            if (isset($curDef["REQUIRES"]["GROUP"]) && !isset($curDef["REQUIRES"]["RAW"])) {
                                $prefix = $this->getDefaultPrefixes(PermissionsManager::PERM_TYPE_PERMISSIONS);
                                $curDef["REQUIRES"]["GROUP"] = $prefix["group"] . $curDef["REQUIRES"]["GROUP"];
                            }
                        }

                        return PermissionsManager::$aclManager->acl_check($curDef["REQUIRES"], array("ITEM" => $user->getId()), $axoParam);

                    }break;
                case PermissionsManager::PERMISSIONSPEC_ROLE:
                    {
                        if(!$user->isLogged() && !$skipLoggedTest)
                            return false;
                        $role=$curDef["ROLE"];
                        $prefix=$this->getDefaultPrefixes(PermissionsManager::PERM_TYPE_USER);
                        if(!isset($curDef["RAW"]) || $curDef["RAW"]==true)
                        {

                            $role=$prefix["group"].$role;
                        }
                        return PermissionsManager::$aclManager->userBelongsToGroup($role,$user->getId());
                    }break;
            }
        }
        return false;
    }
    function uninstall()
    {
        return PermissionsManager::$aclManager->uninstall();
    }
    function addModule($moduleClass)
    {
        return PermissionsManager::$aclManager->addModule($moduleClass);
    }

}


?>
