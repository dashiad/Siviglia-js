<?php
namespace model\web\Jobs\App\Jobs\Messages;

/**
 *
 * @author Andrés
 * @property from
 * @property to
 * @property action
 * @property data
 * @property sender_id
 * @property params
 * 
 *
 */
class SimpleMessage extends BaseMessage
{
    protected $msgType = 'SimpleMessage';
    
    protected $fields = [
        'sender_id' => null,
        'params'    => [],
    ];
}
