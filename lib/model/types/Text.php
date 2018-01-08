<?php namespace lib\model\types;
  class Text extends BaseType
  {
        
  }
  class TextMYSQLSerializer extends BaseTypeMYSQLSerializer
  {
      function serialize($type)
      {
          $v= $type->hasValue()?"'".preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $type->getValue())."'":"NULL";
          return $v;
      }

      function getSQLDefinition($name,$definition)
      {
          
          $charSet=$definition["CHARACTER SET"];
          if(!$charSet)$charSet="utf8";
          $collation=$definition["COLLATE"];
          if(!$collation)$collation="utf8_general_ci";
          
          return array("NAME"=>$name,"TYPE"=>"TEXT CHARACTER SET ".$charSet." COLLATE ".$collation." ".$defaultExpr);
      }
  }

  class TextHTMLSerializer extends BaseTypeHTMLSerializer {
  
      function serialize($obj)
      {
          return $obj->getValue();
      } 
      function unserialize($type,$value)
      {
          $value=$this->sanitize($value);
          $type->validate($value);
          $type->setValue($value);
      }
      
      function sanitize($text)
      {
          // Elimino siempre los posibles escapeos.
          $text=str_replace('\\','',$text);          
          return $this->xss_clean($text);
      }
      function xss_clean($data)
      {
      // Fix &entity\n;
      $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
      $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
      $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
      $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
 
    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
 
    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
 
    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
 
    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
 
    do
    {
        // Remove really unwanted tags
        $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }while ($old_data !== $data);
 
    // we are done...
    return $data;
}

  }
?>
