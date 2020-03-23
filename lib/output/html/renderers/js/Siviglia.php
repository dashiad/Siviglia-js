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
              "#/js/Siviglia/model/(.*)/Model.js#"         => ["root"=>"model","type"=>"model", "regex"=>"/js/Model.js"],
              "#/js/Siviglia/model/(.*)/forms/(.*).js#"    => ["root"=>"model","type"=>"forms", "regex"=>"/js/Siviglia/forms/#1#.js"],
              "#/js/Siviglia/model/(.*)/forms/(.*).html#"  => ["root"=>"model","type"=>"formsTpl", "regex"=>"/js/Siviglia/forms/#1#.html"],
              "#/js/Siviglia/model/(.*)/views/(.*).js#"    => ["root"=>"model","type"=>"views", "regex"=>"/js/Siviglia/views/#1#.js"],
              "#/js/Siviglia/model/(.*)/views/(.*).html#"  => ["root"=>"model","type"=>"viewsTpl", "regex"=>"/js/Siviglia/views/#1#.html"],
              "#/js/Siviglia/site/widgets/(.*).js#"  => ["root"=>"site","type"=>"views", "regex"=>"/#1#.js"],
              "#/js/Siviglia/site/widgets/(.*).html#"  => ["root"=>"site","type"=>"viewsTpl", "regex"=>"/#1#.html"]
          ]
        );
    }
}
