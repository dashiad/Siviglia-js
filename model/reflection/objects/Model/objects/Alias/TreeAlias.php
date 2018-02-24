<?php
  namespace model\reflection\Model\Alias;
  class TreeAlias extends \model\reflection\base\AliasDefinition
  {
      function __construct($name,$parentModel,$definition) 
      {
          parent::__construct($name,$parentModel,$definition); 
      }
      function isRelation() {return false;}
      
      function isAlias(){ return true;}
      
      
      function generateActions()
      {
          // En principio, sin acciones.
          return array();
      }

      function getDataSourceCreationCallback()
      {
          return null;
      }
      
       function getDataSources()
        {
            return array();           
        }
      
  }
  
