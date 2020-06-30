<?php 
namespace lib\Csrf;

use DateTime;

class CsrfToken {
    
    const DEFAULT_EXPIRATION = 2*60*60; // 2 horas (en segundos)
    
    protected $action;
    protected $uid;
    protected $createdAt;
    protected $expiresAt;
    protected $token;
    
    public function __construct(Int $expiration=self::DEFAULT_EXPIRATION, 
                                ?String $action=null, 
                                ?String $uid=null)  : CsrfToken
    {
        $this->createdAt = new \DateTime();
        $this->expiresAt = new \DateTime("+$expiration seconds");
        $this->action    = $action;
        $this->uid       = $uid;
        $this->token     = bin2hex(openssl_random_pseudo_bytes(64));
        
        return $this;
    }
    
    public function setAction(String $action) : CsrfToken
    {
        $this->action = $action;
        return $this;
    }
    
    public function setUid(String $uid)  : CsrfToken 
    {
        $this->uid = $uid;
        return $this;
    }
    
    public function save() : CsrfToken
    {
        // TODO: guardar
        return $this;
    }
    
    public function getAction() : ?string
    {
        return $this->action;
    }
    
    public function getUid() : ?String
    {
        return $this->Uid;
    }
    
    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }
    
    public function getExpiresAt() : DateTime
    {
        return $this->expiresAt;
    }
    
    public function hasExpired() : Bool
    {
        $now = new \DateTime();
        return $this->expiresAt>$now;
    }
    
    public function getToken() : String
    {
        return $this->token;
    }
    
    public function isToken(String $token) : Bool
    {
        return ($this->token===$token);
    }
    
    
}