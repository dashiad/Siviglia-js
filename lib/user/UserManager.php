<?php
class UserManagerException extends \lib\model\BaseException
{
    const ERR_DUPLICATED_USERNAME=3;
    const ERR_DUPLICATED_EMAIL=4;
    const ERR_NO_SUCH_USER=5;
    const ERR_FROZEN=6;
    const ERR_NOT_VALIDATED=7;
    const ERR_LOCKED_OUT=8;
    const ERR_INVALID_LOGIN=9;
    const ERR_INVALID_LOGIN_AND_FROZEN=10;
}

abstract class CUserManager {
    var $serializer;
    var $dbMapping;
    var $config;
    var $user_errors = array();
    var $data;
    var $loginManagers;
    var $userTable;
    
    function __construct($serializer,$config)
    {
        $this->serializer=$serializer;
        $this->config=& $config;
        $this->loginManagers=array();
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
         $uDef=$oCurrentUser->getDefinition();
         
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
         for($k=0;$k<count($fields);$k++)
         {
             $curField=$fields[$k];
             $mapping=$this->dbMapping[$curField];
          
             if(!$userFields[$mapping]->is_set() && $required[$k])             
                 return $this->setError(CUserManager::ERR_REQUIRED_FIELD,$mapping);
                        
         }

         if($this->getByUsername($params[$this->dbMapping["username"]]))
            return $this->setError(CUserManager::ERR_DUPLICATED_USERNAME,$fields["username"]);
         
                          
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
        
        return $this->dblink->selectRow("SELECT * FROM ".$this->userTable." WHERE ".$this->dbMapping["username"]."='".trim($userName,"'")."'");
        
    }
    
    function getByEmail($email)
    {
        return $this->dblink->selectRow("SELECT * FROM ".$this->userTable." WHERE ".$this->dbMapping["email"]."='".$email."'");
                
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
        
        
        $info=$this->getByUsername($userName);
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

