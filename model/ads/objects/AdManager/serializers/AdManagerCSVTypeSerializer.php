<?php
/**
 * Class AdManagerTypeSerializer
 * @package model\ads\objects\AdManager\serializers
 *  (c) Smartclip
 */


namespace model\ads\objects\AdManager\serializers;


class AdManagerCSVTypeSerializer extends \lib\storage\TypeSerializer
{

    function getTypeNamespace()
    {
        // TODO: Implement getTypeNamespace() method.
        return '\model\ads\AdManager\serializers\CSV\types';
    }
}
