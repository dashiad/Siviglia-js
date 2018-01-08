<?php namespace lib\model;
  class BaseException extends \Exception
  {
      var $params;

    public function __construct($code,$params=null)
      {           
          $this->params=$params;       
          parent::__construct("", $code);
      }    
    public function getParams()
    {
        return $this->params;
    }
    public function fatal()
    {
        return true;
    }
    public function __toString()
      {
        $rfl=new \ReflectionClass(get_class($this));
        $constants=array_flip($rfl->getConstants());
        $cad= get_class($this)."[ {$this->code} :".$constants[$this->code]." ] <br>";
        //if($this->params)
         //     debug($this->params);
       print_r($this->getTrace());
        return $cad;
      }
    
    function getCodeString()
    {
        $reflectionClass=new \ReflectionClass($this);
        $className=get_class($this);
        // Se obtienen las constantes
        $constants = $reflectionClass->getConstants();
        foreach($constants as $key=>$value)
        {            
            if($this->code==$value)
            {
                if( strpos($key,"ERR_")===0 )
                {
                    $key=substr($key,4);
                }
                return $className."::".$key;
            }
            
        }
    }
      function getParamsAsString()
      {
          if($this->params=="")
              return "";
        ob_start();
          print_r($this->params);
        return ob_get_clean();
      }
  }
