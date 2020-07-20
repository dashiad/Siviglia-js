<?php
  namespace model\reflection\Model;

  class TreeAlias extends \model\reflection\Model\AliasDefinition
  {
      function __construct($name,$parentModel,$definition)
      {
          parent::__construct($name,$parentModel,$definition);
      }
      function __isRelation() {return false;}

      function __isAlias(){ return true;}


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

