<?php
namespace model\web\Jobs\App\Jobs\Config;

class BaseConfig
{
    const CONFIG = [];
    
    public function get($value=null) {
        if (!empty($value)) {
            return array_key_exists($value, static::CONFIG) ? static::CONFIG[$value] : null;
        } else {
            return static::CONFIG;
        }
    }
}

