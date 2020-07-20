<?php
namespace model\web\Jobs\App\Jobs\Workers;

use model\web\Jobs\App\Jobs\Messages\AlertMessage;

class AlertWorker extends Worker
{
    protected $alert;
    protected $name = 'alert_worker';
    
    protected function runItem($item)
    {
        if ($this->alert->send()) 
        {
            return "Enviado correo a $item".PHP_EOL;
        } else {
            throw new \Exception("Error al enviar correo a $item");
        }
    }

    protected function init()
    {
        $className   = JOBS_NAMESPACE.'Alerts\\'.$this->args['params']['alert_type'].'Alert';
        $args        = $this->args['params'];
        $this->alert = new $className($args);
        $this->alert->setTo($this->args['items']);
        $contentTemplate = $this->args['params']['params']['content'] ?? '';
        $content = $className::replaceContent($contentTemplate, ['%job_id%' => $this->getId()]);
        $msg = ['content' => $content];
        $this->alert->setMessage(new AlertMessage($msg));
    }
}