<?php
namespace model\web\Jobs\App\Jobs\Messages;

/**
 * 
 * @author Andrés
 * @property from
 * @property to
 * @property content
 *
 */
class AlertMessage extends BaseMessage
{
    protected $msgType = 'AlertMessage';
    
    protected $fields = [
        'content' => '',
    ];
}
