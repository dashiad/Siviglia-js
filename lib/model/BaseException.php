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
        return "UNKNOWN::UNKNOWN";
    }
      function getParamsAsString()
      {
          if($this->params=="")
              return "";
        ob_start();
          print_r($this->params);
        return ob_get_clean();
      }

      public function __toString()
      {

          $reflectionClass=new \ReflectionClass($this);
          $constants=$reflectionClass->getConstants();
          $map=array();

          // Primero se obtienen los errores reales, aquellos que no contienen "TXT"
          $key=null;
          foreach($constants as $key2=>$value2)
          {
              if($value2==$this->code) {
                  $key = $key2;
                  break;
              }
          }
          if($key==null)
              return $this->getCodeString();
          // Luego, se obtienen los valores de cadena, y se busca si hace match con
          // alguna de las excepciones anteriores
          $txtKey=preg_replace("/^ERR_/","TXT_",$key2);
          if(!isset($constants[$txtKey]))
              return $this->getCodeString();

          if($this->params==null)
              return $constants[$txtKey];
          // Se devuelve el resultado de evaluar una parametrizableString, con los parametros
          return \lib\php\ParametrizableString::getParametrizedString($constants[$txtKey],$this->params);
      }
  }
