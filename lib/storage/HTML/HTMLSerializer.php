<?php
/**
 * Class HTMLSerializer
 * @package lib\storage\HTML
 *  (c) Smartclip
 */


namespace lib\storage\HTML;


use lib\storage\TypeSerializer;

class HTMLSerializer extends TypeSerializer
{
    function __construct()
    {
        parent::__construct([], "HTML");
    }

    function getTypeNamespace()
    {
        return '\lib\storage\HTML\types';
    }
}
