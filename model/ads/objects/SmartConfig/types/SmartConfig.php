<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;
require_once (__DIR__ . "/BaseType.php");

class SmartConfig extends BaseType
{

    public $definition = [
        "TYPE" => "Container",
        "DESCRIPTION" => "Config completa",
        "LABEL" => "Config completa",
        'FIELDS' => [
                "config" => [
                    'TYPE' => 'Dictionary',
                    'LABEL' => 'Regex',
                    'VALUETYPE' => [
                        'LABEL' => 'Plugin',
                        'TYPE' => 'Container',
//                         'KEEP_KEY_ON_EMPTY' => false,
                        'FIELDS' => [
                            'Exelate' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\Exelate',
                                'LABEL' => 'Plugin Exelate'
                            ],
                            'Adobe' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\Adobe',
                                'LABEL' => 'Plugin Adobe'
                            ],
                            "BlueKaiPlugin" => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\BlueKai',
                                'LABEL' => 'Plugin BlueKai'
                            ],
                            'ImageLoaderUrl' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\ImageLoaderUrl',
                                'LABEL' => 'Plugin ImageLoaderUrl'
                            ],
                            'AdnSegments' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\AdnSegments',
                                'LABEL' => 'Plugin AdnSegments'
                            ],
                            'GPTConfig' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\GPTConfig',
                                'LABEL' => 'Plugin GPTConfig'
                            ]
                        ]
                    ]
                ]
            ]
        ];

    public function __construct($name, $parentType = null, $value = null, $validationMode = null)
    {
        parent::__construct($name, $this->definition, $parentType, $value, $validationMode);
    }

    public function _setValue($val, $validationMode = null)
    {
        $this->value = $val;
        $this->valueSet = true;
    }

    public function _validate($value)
    {
        return true;
    }

    public function _getValue()
    {
        return $this->value;
    }

    public function _equals($value)
    {
        return $this->value === $value;
    }

    public function getMetaClassName()
    {
        return self::class;
    }

    public function _copy($val)
    {
        $this->value = $val->getValue();
        $this->valueSet = true;
    }
}

