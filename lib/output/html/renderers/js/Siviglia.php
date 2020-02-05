<?php
/**
 * Class Siviglia
 * @package lib\output\html\renderers\js
 *  (c) Smartclip
 */


namespace lib\output\html\renderers\js;


class Siviglia extends BaseJsRenderer
{
    function __construct()
    {
        parent::__construct(
          [
              "#/js/siviglia/(.*)/Model.js#"         => ["type"=>"model", "regex"=>"/js/Siviglia/Model.js"],
              "#/js/siviglia/(.*)/forms/(.*).js#"    => ["type"=>"forms", "regex"=>"/js/Siviglia/forms/#1#.js"],
              "#/js/siviglia/(.*)/forms/(.*).html#"  => ["type"=>"formsTpl", "regex"=>"/js/Siviglia/forms/#1#.html"],
              "#/js/siviglia/(.*)/views/(.*).js#"    => ["type"=>"views", "regex"=>"/js/Siviglia/views/#1#.js"],
              "#/js/siviglia/(.*)/views/(.*).html#"  => ["type"=>"viewsTpl", "regex"=>"/js/Siviglia/views/#1#.html"]
          ]
        );
    }
}
