<?php
namespace model\web\Jobs\App\Jobs\Alerts;

use model\web\Jobs\App\Jobs\Messages\AlertMessage;

interface AlertInterface
{   
    public function setMessage(AlertMessage $message);
    public function setTo(Array $to);
    public function send() : bool;
    
    public static function replaceContent(String $text, Array $values);
}

