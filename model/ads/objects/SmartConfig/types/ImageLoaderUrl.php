<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class ImageLoaderUrl extends BaseType
{
    
    // TODO: este campo es opcional dentro de SmartConfig, pero al tener
    // valores por defecto uno de los subcampos, está inicializado y falla
    // por ser el otro requerido
    public $definition = [
        'LABEL' => 'ImageLoaderUrl',
        'TYPE' => 'Container',
        'FIELDS' => [
            'url' => [
                'LABEL' => 'URL',
                'TYPE' => 'String',
//                 'REQUIRED' => true
//                 'REQUIRED' => false,
            ],
            'throttle' => [
                'LABEL' => 'Throttle',
                'TYPE' => 'Integer',
//                 'REQUIRED' => true,
//                 'DEFAULT' => 1
//                 'REQUIRED' => false,
            ]
        ]
    ];
    
    public function __construct($name, $parentType=null, $value=null, $validationMode=null) {
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
        return $this->value===$value;
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



