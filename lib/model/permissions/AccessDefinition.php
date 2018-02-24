<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 30/01/2018
 * Time: 15:46
 */

namespace lib\model\permissions;

class AccessDefinitionException extends \lib\model\BaseException
{
    const ERR_UNAUTHORIZED=1;
    const TXT_UNAUTHORIZED="Permiso denegado";
}
class AccessDefinition
{
    var $definition;
    function __construct($definition)
    {
        $this->definition=$definition;
    }

    /*
     * $requiredPermission puede ser una string, o un array:
     * Si es una string, debe ser PUBLIC o LOGGED
     * Si es un array, puede tener varias formas:
     * 1) array("OWNER"=>true)  Solo requiere que el usuario sea propietario.
     * 2) array("OWNER"=>true,"MODEL"=>"xxx","PERMISSION"=>"yyy") Requiere que sea propietario y tenga el permiso indicado sobre el modelo indicado.
     * 3) array("MODEL"=>"xxx","FIELD"=>"yyy","PERMISSION"=>"zzz") Requiere el permiso zzz sobre el modelo al que pertenece el campo "yyy"
     * El campo "OWNER" es un requisito aniadido, no un requisito en si mismo.
     */
    function check($user,\lib\model\BaseModel $model=null,$definition=null) {

        $requiredPermission=$definition?$definition:$this->definition;
        $objName=$model->__getObjectName();
        if(!is_array($requiredPermission))
            $requiredPermission=array($requiredPermission);

        if(!$user)
        {
            $user=\Registry::getService("user")->getUser();
        }

        foreach($requiredPermission as $req)
        {
            if(!$req)
            {
                continue;
            }
            if($req==\PermissionsManager::PERM_SPEC_PUBLIC)
                return true;

            //continue;

            if($req==\PermissionsManager::PERM_SPEC_LOGGED && !$user->isLogged())
                return false;


            if(!is_array($req))
            {
                $permsService=\Registry::getService("permissions");
                $perms=$permsService->getPermissionsOverModel($user,$objName,$model);

                if(isset($perms[$req]) && $perms[$req])
                    return true;
            }
            // If $req is an array, it has the following keys:MODEL,FIELD,PERMISSION
            // Meaning this method will check for PERMISSIONS over MODEL, thru relation FIELD
            //. This is, if we have 2 objects, "a" and "b", and "a" has a relation with "b" thru the field "a1",
            //  a possible spec would be array(MODEL=>b, FIELD=>a1, PERMISSION=CREATE);In this case, this method
            // was called with an "a" instance.
            if(is_array($req))
            {
                if(io($req,\PermissionsManager::PERM_SPEC_OWNER,false))
                {
                    if(!$user)
                        return false;
                    if(!$model->__isOwner($user))
                        return false;
                    // Si solo se pedia ownership, y no PERMISSION, se devuelve aqui true.
                    if(!isset($req["PERMISSION"]))
                        return true;

                }
                $curModel=null;
                if(isset($req["MODEL"]))
                {
                    $curName=$req["MODEL"];
                    $curModel=\lib\model\BaseModel::getModelInstance($curName);
                }
                else
                {
                    if(isset($req["FIELD"]))
                    {
                        if($model->isLoaded())
                        {
                            // TODO: Excepcion si field no existe.
                            $field=$model->__getField($req["FIELD"]);
                            if(!$field)
                                continue;
                            if(!is_a($field,'\lib\model\ModelBaseRelation'))
                                continue;
                            $curModel=$model->{$req["FIELD"]};
                        }
                    }
                    else
                    {
                        $curModel=$model;
                    }
                }

                return $this->check($user,$curModel,is_array($req["PERMISSION"])?$req["PERMISSION"]:array($req["PERMISSION"]));

            }

        }  // Fin del foreach
        // Si no hay ninguna definicion de permisos, se retorna true
        throw new AccessDefinitionException(AccessDefinitionException::ERR_UNAUTHORIZED);
    }
}