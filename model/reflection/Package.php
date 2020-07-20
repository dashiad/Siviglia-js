<?php
/**
 * Class Package
 * @package model\ads
 *  (c) Smartclip
 */


namespace model\reflection;


class Package extends \lib\model\Package
{

    function includeModel($modelName)
    {
        switch($modelName)
        {
            case 'model\reflection\Types\types\meta\BaseType':
                {
                    include_once(__DIR__."/objects/Types/types/BaseType.php");
                    return;
                }break;
            default:
                {
                    parent::includeModel($modelName);
                }
        }

    }
}
