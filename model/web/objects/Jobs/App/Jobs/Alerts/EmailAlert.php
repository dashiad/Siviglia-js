<?php
namespace model\web\Jobs\App\Jobs\Alerts;

use model\web\Jobs\App\Jobs\Messages\AlertMessage;

class EmailAlert implements AlertInterface
{
    
    protected $message;
    protected $to = [];

    public function setTo(Array $to)
    {
        foreach ($to as $address) {
            $this->to[] = $address;
        }
    }

    public function setMessage(AlertMessage $msg)
    {
        $this->message = json_encode($msg);
    }

    public function send() : bool
    {
        foreach ($this->to as $address) {
            echo "enviando a $address: $this->message";
        }
        return true;
    }
    
    public static function replaceContent(String $text, Array $params)
    {
        foreach ($params as $key=>$value) {
            $text = str_replace($key, $value, $text);
        }
        return $text;
    }
}