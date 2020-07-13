<?php

namespace model\web;

class WebUserException extends \lib\model\BaseException
{
    const ERR_USER_NOT_LOADED = 1;
    const ERR_INVALID_USERNAME_PASSWORD=2;
    const ERR_FROZEN=3;
    const ERR_NOT_VALIDATED=4;
    const ERR_LOCKED_OUT=5;
    const TXT_USER_NOT_LOADED="Instancia no cargada.";
    const TXT_INVALID_USERNAME_PASSWORD="Usuario/Contraseña incorrecta.";
    const TXT_FROZEN="Usuario deshabilitado.";
    const TXT_NOT_VALIDATED="Usuario aún no validado.";
    const TXT_LOCKED_OUT="Ha superado el número máximo de intentos fallidos de login.";
}

class WebUser extends \lib\model\BaseModel
{
    var $isLogged = false;
    var $userId = 0;
    var $userData;
    var $dbLink;
    static $currentUser=null;

    function __construct($serializer=null, $userId = null)
    {

        \lib\model\BaseModel::__construct($serializer);
        if ($userId)
            $this->loadById($userId);
    }

    function getId()
    {
        $id= $this->USER_ID;
        return $id;
    }

    function getEffectiveUserId()
    {
        return $this->USER_ID;
    }

    function loadById($id)
    {
        $this->setId($id);
        $this->unserialize();
    }

    function loadByUsername($username)
    {

        $this->LOGIN = $username;
        $this->loadFromFields();
    }

    function setLogged($id = null)
    {
        if ($id)
        {
            $this->setId($id);
        }
        else
            $id = (string) $this->USER_ID;
        if (!$this->isLoaded())
        {
            try
            {
                $this->loadFromFields();
            }
            catch (\Exception $e)
            {
                unset(\Registry::$registry["userId"]);
                return;
            }
        }
        $this->isLogged = true;
        \Registry::$registry["userId"] = $id;
    }

    function isLogged()
    {
        return $this->isLogged;
    }

    function logout()
    {
        \Registry::$registry["userId"] = null;
        $this->isLogged = false;
    }

    function getUserPath()
    {
        if (!$this->isLoaded())
            throw new WebUserException(WebUserException::ERR_USER_NOT_LOADED);
        return WebUser::getUserPathById($this->USER_ID);
    }

    static function getUserPathById($userId)
    {
        return intval($userId / 100) . "/" . $userId . "/";
    }

    function createUserPath($basePath)
    {
        if (!$this->isLoaded())
            throw new WebUserException(WebUserException::ERR_USER_NOT_LOADED);
        return @mkdir($basePath . "/" . $this->getUserPath(), 0777, true);
    }


    static function login($userName,$password,$model=null)
    {
        if($model==null)
        {
            $model=new WebUser(null);
        }
        // If the User doesnt exist, we'll get an exception from the serializers
        try
        {
            $model->loadByUsername($userName);
        }catch(\Exception $e)
        {
            throw new WebUserException(WebUserException::ERR_INVALID_USERNAME_PASSWORD);
        }


        if($model->active!=1)
            throw new WebUserException(WebUserException::ERR_FROZEN);

        $site=\Registry::getService("site");
        $siteConfig=$site->getConfig();
        $userConfig=$siteConfig->getUserConfig();
        $requireValidation=io($userConfig,"REQUIRE_ACCOUNT_VALIDATION",false);
        if($requireValidation && !$model->VALIDATED)
            throw new WebUserException(WebUserException::ERR_NOT_VALIDATED);

        $attempts=io($userConfig,"ATTEMPTS_BEFORE_LOCKOUT",null);
        if($attempts && $model->FAILEDLOGINATTEMPTS >=$attempts )
            throw new WebUserException(WebUserException::ERR_LOCKED_OUT);
       if(!$model->{"*PASSWORD"}->check($password))
        {
                $model->FAILEDLOGINATTEMPTS = $model->FAILEDLOGINATTEMPTS + 1;
                $model->save();
            throw new WebUserException(WebUserException::ERR_INVALID_USERNAME_PASSWORD);
        }
        else
        {

            //$model->NLOGINS=$model->NLOGINS+1;
            $model->lastlogin=\lib\model\types\DateTime::getValueFromTimestamp();
            //$model->LASTIP=\Registry::$registry["client"]["ip"];
            //$model->FAILEDLOGINATTEMPTS=0;
            $model->save();
            $model->isLogged=true;
            global $oCurrentUser;
            $oCurrentUser=$model;
            WebUser::$currentUser=$model;
            return $model;
        }
    }
    static function getCurrentUser()
    {
        return WebUser::$currentUser;
    }
    static function createAdminUser($serializer=null)
    {
        $serializerService=\Registry::getService("storage");
        if($serializer===null)
            $serializer=$serializerService->getSerializerByName("default");
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
            $user->save($serializer);
        }
        return $user->USER_ID;

    }

    function save($serializer=null)
    {
        if($this->__isNew())
        {
            $this->{"*date_add"}->setAsNow();
            if($this->{"*PASSWORD"}->__hasValue())
            {
                $this->{"*PASSWORD"}->encode();
            }
        }
        parent::save($serializer);

    }

    function createUser($userFields){


        $this->cleanErrors();

        $params=array();
        $keys=array_keys($userFields);
        $nKeys=count($keys);
        $acceptedFields=array_values($this->dbMapping);
        for($k=0;$k<$nKeys;$k++)
        {
            if(in_array($keys[$k],$acceptedFields))
                $params[$keys[$k]]=$userFields[$keys[$k]]->getSQLValue();
        }

        $fields=array("username","password","email","extType","extId");
        $required=array(1,0,0,1,0);
        $result=array();

        // Array UserName not allowed - Note case sensitive at row 130
        $userNameNotAllowed = array("ulises","@tlmaco","ainhoa","@nhoa_89","estrella polar","@bestrellapolar","piti",
            "@esecachodepiti","burbuja","@yoburbuja","julia","@juliacwilson","vilma","@just_vilma",
            "palomares","@palomar_es","gamboa","@l_gmboa","ramiro","@ramlocke","capitán montero",
            "@cap_montero","estela","@stlademar","salomé","@salo_mar","de la cuadra","@dlcuadra",
            "leonor","dulce","tom","víctor","cristóbal","@cristobalmonsanto","@xtobalmonsanto");

        for($k=0;$k<count($fields);$k++)
        {
            $curField=$fields[$k];
            $mapping=$this->dbMapping[$curField];

            if(!$userFields[$mapping]->is_set() && $required[$k])
                return $this->setError(CUserManager::ERR_REQUIRED_FIELD,$mapping);

        }

        if (in_array(trim(strtolower($params[$this->dbMapping["username"]]),"'"), $userNameNotAllowed)) {
            return $this->setError(CUserManager::ERR_DUPLICATED_USERNAME,$fields["username"]);
        }

        if($this->getByUsername($params[$this->dbMapping["username"]])){
            return $this->setError(CUserManager::ERR_DUPLICATED_USERNAME,$fields["username"]);
        }

        if($this->config["REQUIRE_UNIQUE_EMAIL"])
        {
            if($this->getByEmail($params[$this->dbMapping['email']]))
                return $this->setError(CUserManager::ERR_DUPLICATED_EMAIL,$fields["email"]);
        }

        $mappingFields=array("creationdate","nlogins","frozen","failedloginattempts");
        $mappingDefaults=array("NOW()",0,0,0);
        for($k=0;$k<count($mappingFields);$k++)
        {
            if($this->dbMapping[$mappingFields[$k]])
                $params[$this->dbMapping[$mappingFields[$k]]]=$mappingDefaults[$k];
        }


        if(!$this->config["PLAINTEXT_PASSWORDS"])
        {
            $params[$this->dbMapping["password"]]="'".md5($userFields[$this->dbMapping["password"]]->getValue())."'";
        }


        $params[$this->dbMapping["lastlogin"]]="NOW()";
        $params[$this->dbMapping["lastip"]]="'".$_SERVER['REMOTE_ADDR']."'";

        $userId=$this->dblink->insertFromAssociative($this->userTable,$params);


        $this->onNewUser($userId);

        if(!$this->config["REQUIRE_ACCOUNT_VALIDATION"] &&
            $this->config["LOGIN_ON_CREATE"])
            $this->logUser($userId);

        return $userId;
    }

}

?>
