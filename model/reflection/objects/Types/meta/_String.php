<?php namespace model\reflection\Types\meta;


  class _String extends \model\reflection\Meta\BaseMetadata
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"String"],
                  "MINLENGTH"=>["TYPE"=>"Integer"],
                  "MAXLENGTH"=>["TYPE"=>"Integer"],
                  "REGEXP"=>["TYPE"=>"String"],
                  "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false],
                  "FIXED"=>["TYPE"=>"String"],
                  "DEFAULT"=>["TYPE"=>"String"],
                  "SOURCE"=>BaseType::getSourceMeta()
              ]
          ];
      }
  }

