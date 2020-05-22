<?php
namespace model\ads\SmartConfig;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/Definition.php
 CLASS:Definition
 *
 *
 **/
use \lib\model\BaseModelDefinition;

class Definition extends BaseModelDefinition
{
    static $definition = [
        'FIELDS' => [
            'domain' => [
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
            ],
            'plugin' => [
                'TYPE' => 'String',
                'LABEL' => 'Plugin',
            ],
            'config' => [
                'TYPE' => 'Array',
                'LABEL' => 'Config',
                'ELEMENTS' => [], // TODO: ver posibles formatos
            ],
        ],
    ];
}