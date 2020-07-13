<?php
include_once(LIBPATH."/base/CErrored.php");
abstract class CUserManager extends CErrored {
    var $dblink;
    var $dbMapping;
    var $sessionObject;
    var $config;
    var $lang;
    var $user_errors = array();
    var $data;
    var $loginManagers;
    var $userTable;
    /**
    
    **/
    const NO_ERR=0;
    const ERR_DATABASE=1;
    const ERR_REQUIRED_FIELD=2;
    const ERR_DUPLICATED_USERNAME=3;
    const ERR_DUPLICATED_EMAIL=4;
    const ERR_NO_SUCH_USER=5;
    const ERR_FROZEN=6;
    const ERR_NOT_VALIDATED=7;
    const ERR_LOCKED_OUT=8;
    const ERR_INVALID_LOGIN=9;
    const ERR_INVALID_LOGIN_AND_FROZEN=10;
    const ERR_EMAIL_NO_EXIST=11;
    const ERR_EMAIL_NO_COINCIDE=12;
    const ERR_PASSWORD_NO_COINCIDE=13;

    /**
    

    *  Campos de dbMapping:
    * "userid" : Campo que almacena el id de usuario
     * "username": Campo que almacena el nombre de usuario.
     * "password": Campo que almacena el password.
     * "email": Campo que almacena el email.
     * "extType": Campo que almacena el loginManager a traves del cual el usuario
     *            se da de alta.
     * "extId": Campo que almacena el id de usuario en el loginManager remoto.
     *        
     * "nlogins": Campo que almacena el numero de logins realizados por el usuario.
     * "lastlogin": Fecha del ultimo login
     * "lastip": Ultima ip del usuario
     * "frozen": Campo que indica si el usuario esta deshabilitado (1: deshabilitado,0: activo)
     * "creationdate": Campo que indica la fecha de creacion del usuario.
     * "failedloginattempts": Campo que indica el numero de errores sucesivos en login.
     * "validated": Campo que indica si un usuario se ha validado o no.
     * 
     * 
     *  Parametros de configuracion
     *  REQUIRE_UNIQUE_EMAIL : TRUE / FALSE (def, FALSE)
     *  PLAINTEXT_PASSWORDS: TRUE / FALSE (def, FALSE)
     *  ATTEMPTS_BEFORE_LOCKOUT : 0.... (def, 0 = disabled)
     *  REQUIRE_ACCOUNT_VALIDATION : TRUE / FALSE (def: FALSE)
     *  LOGIN_ON_CREATE: TRUE / FALSE (def, FALSE. Si REQUIRE_ACCOUNT_VALIDATION es true, se ignora.
    */
    function __construct($dblink,$userTable,$dbMapping, $config)
    {
        $this->dblink=& $dblink;
        $this->userTable=$userTable;
        $this->dbMapping=& $dbMapping;
        $this->config=& $config;
        $this->loginManagers=array();
        $this->errCode=CUserManager::NO_ERR;
        $this->errData="";          
    }
    static function whoami()
    {
        return __CLASS__;
    }

    // 
    function addLoginManager($userType, & $loginManager)
    {
        $this->loginManagers[$userType]=& $loginManager;
    }
    
    

    
    /**
    * New User
    * 
    * @param array $params Array of values
    * @return true|false True if the creation is success
    */

    function createUser($userFields){
        
         global $oCurrentUser;
         $uDef=$oCurrentUser->definition; 
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
                           
        if(!$userId)
            return $this->setError(CUserManager::ERR_DATABASE,$fields["email"]);                
        
        $this->onNewUser($userId);
        
        if(!$this->config["REQUIRE_ACCOUNT_VALIDATION"] &&
            $this->config["LOGIN_ON_CREATE"])        
            $this->logUser($userId);
        
        return $userId;
    }
    
    abstract function onNewUser($userId);
    abstract function onLogin($userId);    
    
    function logUser($userId)
    {
        
        global $oCurrentUser;        
        $oCurrentUser->loadById($userId);
        $this->onLogin($userId);
    }
    
    function getByUsername($userName)
    {
        $result=$this->dblink->selectRow("SELECT * FROM ".$this->userTable." WHERE ".$this->dbMapping["username"]."='".trim($userName,"'")."'");
        return $result;
        
    }
    
    function getByEmail($email)
    {
        $result= $this->dblink->selectRow("SELECT * FROM ".$this->userTable." WHERE ".$this->dbMapping["email"]."='".trim($email,"'")."'");
        return $result;
                
    }

    function getPasswordByEmail($email) {
        $result= $this->dblink->selectRow("SELECT md5(PASSWORD) AS PASSWORD FROM ".$this->userTable." WHERE ".$this->dbMapping["email"]."='".$email."'");

        if(!$result){
            return false;
        }else{
            return $result["PASSWORD"];
        }
    }

    function getUserIdByEmail($email) {
        $result= $this->dblink->selectRow("SELECT USER_ID FROM ".$this->userTable." WHERE ".$this->dbMapping["email"]."='".$email."'");

        return $result["USER_ID"];
    }

    function getEmailByUserId($userId) {
        $result= $this->dblink->selectRow("SELECT EMAIL FROM ".$this->userTable." WHERE USER_ID='".$userId."'");

        return $result["EMAIL"];
    }


    function setChangePasswordById($userId,$password) {
        $q="UPDATE ".$this->userTable." SET PASSWORD='".md5($password)."' WHERE USER_ID=".$userId;

        $this->dblink->update($q);
    }


    /**
    * 
    * 
    * @param mixed $userName
    * @param mixed $password
    * Retorna false si ha habido algun error, o true si tod fue ok.
    */
    
    
    function login($userName,$password)
    {        
        $this->cleanErrors();
        
        $info=$this->getByEmail($userName);


        if(!$info)
            return $this->setError(CUserManager::ERR_NO_SUCH_USER,$userName);                
        
        $frozenField=$this->dbMapping["frozen"];
        if($frozenField && $info[$frozenField])
           return $this->setError(CUserManager::ERR_FROZEN,$userName);                        
        
        $validationField=$this->dbMapping["validated"];        
        if($this->config["REQUIRE_ACCOUNT_VALIDATION"] && $validationField && !$info[$validationField])
          return $this->setError(CUserManager::ERR_NOT_VALIDATED,$userName);                        
        
        $failedAttemptsField=$this->dbMapping["failedloginattempts"];
        if($this->config["ATTEMPTS_BEFORE_LOCKOUT"] && $failedAttemptsField &&
           $info[$failedAttemptsField]>=$this->config["ATTEMPTS_BEFORE_LOCKOUT"])
             return $this->setError(CUserManager::ERR_LOCKED_OUT,$userName);                       
        
        
        $encPassword=$this->config["PLAINTEXT_PASSWORDS"]?$password:md5($password);
        
        $updateFields=array();
        $retVal=true;
        
        if($encPassword!=$info[$this->dbMapping["password"]])
        {
            
            if($this->config["ATTEMPTS_BEFORE_LOCKOUT"] && $failedAttemptsField)        
            {
                $nFails=$info[$failedAttemptsField]+1;                
                if($nFails >= $this->config["ATTEMPTS_BEFORE_LOCKOUT"])
                    $this->setError(CUserManager::ERR_INVALID_LOGIN_AND_FROZEN,$userName);                       
                else
                {
                    $uptdateFields[]=$failedAttemptsField."=".($info[$failedAttemptsField]+1);
                    $this->setError(CUserManager::ERR_INVALID_LOGIN,$userName);                             
                }
            }
            else                
                $this->setError(CUserManager::ERR_INVALID_LOGIN,$userName);                 
            $retVal=false;                        
        }
        else
        {
            $userId=$info[$this->dbMapping["userid"]];
            $nLoginsField=$this->dbMapping["nlogins"];
            $lastLoginField=$this->dbMapping["lastlogin"];
            $lastIpField=$this->dbMapping["lastip"];
            $failedLoginField=$this->dbMapping["failedLoginAttempts"];
        
            if($nLoginsField)
                $updateFields[]=$nLoginsField."=".($info[$nLoginsField]+1);
            if($lastLoginField)
                $updateFields[]=$lastLoginField."=NOW()";
            
            global $oCurrentUser;
            
            if($lastIpField)
            {                                
                $updateFields[]=$lastIpField."='".\lib\model\types\IP::getCurrentIp()."'";
            }
            if($failedLoginField)
                $updateFields[]=$failedLoginField."=0";
                                                    
            $oCurrentUser->loadById($userId);
            $retVal=true;                
        }
        
        if(count($updateFields)>0)
        {
            
            $q="UPDATE ".$this->userTable." SET ".implode(",",$updateFields)." WHERE ".$this->dbMapping["userid"]."=".$userId;
            $this->dblink->update($q);
        }
        
        return $retVal;        
    }
    
    function unfreeze($id)
    {
        if($this->dbMapping["frozen"])
        {
             $q="UPDATE ".$this->userTable." SET ".$this->dbMapping["frozen"]."=0 WHERE ".$this->dbMapping["userid"]."=".$id;
             $this->dbLink->update($q);
        }
    }
    
    function freeze($id)
    {
        if($this->dbMapping["frozen"])
        {
             $q="UPDATE ".$this->userTable." SET ".$this->dbMapping["frozen"]."=1 WHERE ".$this->dbMapping["userid"]."=".$id;
             $this->dbLink->update($q);
        }
    }

    function validate($id)
    {
        if($this->dbMapping["validated"])
        {
             $q="UPDATE ".$this->userTable." SET ".$this->dbMapping["validated"]."=1 WHERE ".$this->dbMapping["userid"]."=".$id;
             $this->dbLink->update($q);
        }
    }    
    function getUserFromLogin($login)
    {
        $q="SELECT * from ".$this->userTable." WHERE ".$this->dbMapping["login"]."='".$login."'";
        return $this->dblink->selectRow($q);
    }
}

abstract class CStdUserManager extends CUserManager
{
    function __construct($dblink)
    {
        CUserManager::__construct($dblink,
                "USER",
                
                array(
                      "userid"=>"USER_ID" ,
                      "username"=>"LOGIN",
                      "password"=>"PASSWORD",
                      "email"=>"EMAIL",
                      "extType"=>"EXTTYPE",   
                      "extId"=>"EXTID",        
                      "nlogins"=>"NLOGINS",
                      "lastlogin"=>"LASTLOGIN",
                      "lastip"=>"LASTIP",
                      "frozen"=>"FROZEN",
                      "creationdate"=>"CREATIONDATE",
                      "failedloginattempts"=>"FAILEDLOGINATTEMPS",
                      "validated"=>"VALIDATED"),
                array("REQUIRE_UNIQUE_EMAIL"=>TRUE,
                      "PLAINTEXT_PASSWORDS"=>FALSE,
                      "ATTEMPTS_BEFORE_LOCKOUT"=>0,
                      "REQUIRE_ACCOUNT_VALIDATION"=>FALSE,
                      "LOGIN_ON_CREATE"=>FALSE
                    )
                );                                
    }    
}

    /**
    * Start Password Recovery
    * 
    * @param string $param Username or email
    * @return true|false True if the key is generated
    */
    function precovery_start($param){
        if(strlen($param) < 4){
            $this->user_errors['auth'][] = "Please, write a valid username or email"; 
            return false; 
            die();  
        }
        /* Try with username */
        if(preg_match('/^[a-z\d_]{4,28}$/i', utf8_decode($param))){
            if($this->user_exist($param)){
                $this->data['username'] = $param;
                $key = $this->getRket();
                if($key){
                    $this->user_errors['auth'][] = "Link: http://".$_SERVER['HTTP_HOST'].BASE_PATH."/actions/recover.php?action=recover&key=".$key;
                    return true;    
                }
                else{
                    $this->user_errors['auth'][] = "Internal error";
                    return false;    
                }    
            }
            else{
                $this->user_errors['auth'][] = "The user don't exist in our system";
                $this->check_bd_error();
                return false;
            }          
        }
        else{
            /* Try with email */
                if(preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $param)){
                    if($this->email_exist($param)){
                        $this->data['email'] = $param;
                        $this->data['username'] = $param;
                        $key = $this->getRket();
                        if($key){
                            $this->user_errors['auth'][] = "Link: http://".$_SERVER['HTTP_HOST'].BASE_PATH."/actions/recover.php?action=recover&key=".$key;
                            return true;    
                        }
                        else{
                            $this->user_errors['auth'][] = "Internal error";
                            return false;    
                        }   
                    }
                    else{
                        $this->user_errors['auth'][] = "The email do'nt exist in our system";
                        $this->check_bd_error();
                        return false;
                    }    
                }
                else{
                    $this->user_errors['auth'][] = "Incorrect email format";
                    return false;
                }
            $this->user_errors['auth'] = "Incorrect data";    
        }            
    }
    
    /**
    * Get recovery key
    * 
    * @return true|false True if the key is generated
    */
    function getRket(){
        if(@$this->data['username']){
            $query = sprintf("SELECT * FROM `users` WHERE `username` = '%s'",
                     mysql_real_escape_string($this->data['username']));
            $user = mysql_fetch_assoc(mysql_query($query,$this->dblink));
            if($user){
                $key = $this->genRkey($user);
                if($key){
                    return $key;    
                }
                else{
                    return false;
                }
            }
            else{
                $this->check_bd_error();
                return false;
            }
            
        }
        elseif(@$this->data['email']){
            $query = sprintf("SELECT * FROM `users` WHERE `email` = '%s'",
                     mysql_real_escape_string($this->data['email']));
            $user = mysql_fetch_assoc(mysql_query($query,$this->dblink));
            if($user){
                if($this->genRkey($user)){
                    return true;    
                }
                else{
                    return false;
                }
            }
            else{
                $this->check_bd_error();
                return false;
            }    
        }
    }
    
    /**
    * Generate recovery key
    * 
    * @param array $user Array of user values
    * @return true|false True if the key is generated  
    */
    function genRkey($user){
        $now = time();
        $genkey = sha1("RECOVER".$user['username'].$now);
        $query = sprintf("INSERT INTO `user_recovery` VALUES('%s','%s','%s','%s','%s','%s')",
                 mysql_real_escape_string($user['user_id']),
                 mysql_real_escape_string($genkey),
                 mysql_real_escape_string($_SERVER['REMOTE_ADDR']),
                 $now,
                 strtotime('+24 hour',$now),
                 0);
        $insert = mysql_query($query, $this->dblink);
        
        if($insert){
            return $genkey;        
        }
        else{
            $this->check_bd_error();
            return false;
        }    
    }
    
    /**
    * Process recovery key
    * 
    * @param string $key The key to be validate
    */
    function processRkey($key){
        $key = trim($key);
        $query = sprintf("SELECT * FROM `user_recovery` WHERE `recovery_key` = '%s'",
                 mysql_real_escape_string($key));
        $key_data = mysql_fetch_assoc(mysql_query($query, $this->dblink));
        if($key_data){
            $now = time();
            if($now < $key_data['end']){
                if(intval($key_data['status']) === 0){
                    $this->data['user_id'] = $key_data['user_id'];
                    return true;    
                }
                else{
                    $this->user_errors['auth'][] = "Invalid link";
                    return false;   
                }
                    
            }
            else{
                $this->user_errors['auth'][] = "Invalid link";
                return false;   
            }   
        }
        else{
            $this->check_bd_error();
            $this->user_errors['auth'][] = "Incorrect action";
            return false;
        }    
    }
    
    /**
    * Process a Recovery update
    * 
    * Update account via recovery key
    * @param array $array Array of values
    */
    function processRupdate($array){
        
        if($array['rkey'] == ''){
            $this->user_errors['auth'][] = "Incorrect action";
            return false;    
        }

        if($this->processRkey($array['rkey']) === false){
            return false;
        }

        if($this->valid_rpassword($array['password1']) === false){
            return false;    
        }

        if($array['password1'] != $array['password2']){
            $this->user_errors['auth'][] = "Password mismatch";
            return false;    
        }
        
        $query = sprintf("UPDATE `users` SET `password` = '%s' WHERE `user_id` = '%s'",
                 mysql_real_escape_string(sha1($array['password1'])),
                 mysql_real_escape_string($this->data['user_id']));
        
        $result = mysql_query($query);
        
        if($result){
            $this->terminate_precovery($array['rkey']);   
        }
        else{
            $this->check_bd_error();
            $this->user_errors['auth'][] = "Internal error";
        }
            
    }
    
    /**
    * End Password Recovery
    * 
    * @param string $key The key to be finished
    */
    function terminate_precovery($key){
        $query = sprintf("UPDATE `user_recovery` SET `status` = '1' WHERE `recovery_key` = '%s'",
                 mysql_real_escape_string($key));
        
        $result = mysql_query($query);
        if($result){
            $this->user_errors['auth'][] = "Changes saved!";   
        }
        else{
            $this->check_bd_error();
            $this->user_errors['auth'][] = "Internal error";
        }
            
    }
   
?>
