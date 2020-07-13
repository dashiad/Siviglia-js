<?php
namespace model\web\Jobs\App\Jobs\Messages;

/**
 *
 * @author Andrés
 * @property from
 * @property to
 * @property action
 * @property data
 * @property name
 * @property status
 * @property children
 * @property children_count
 * @property params
 *
 *
 */
class StatusMessage extends BaseMessage
{
    protected $msgType = 'StatusMessage';
    
    protected $fields = [
        'name'      => null,
        'status'    => null,
        'parent'    => null,
        'params'    => [],
        'children'  => [],
        'children_count' => 0,
    ];
  
}

