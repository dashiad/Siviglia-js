<?php
/**
 * Class AdUnitName
 * @package model\Ads\objects\AdManager\serializers\CSV\types
 *  (c) Smartclip
 */


namespace model\Ads\AdManager\types;


class AdUnitName extends \lib\model\types\_String
{
    function capitalize()
    {
        return strtolower($this->value);
    }
}
