<?php
namespace model\web\Jobs\App\Jobs\Messages;

/**
 * 
 * @author Andrés
 * @property from
 * @property to
 * @property action
 * @property data
 *
 */
class BaseMessage
{
    const FIELDS = [
        'timestamp' => null,
        'msg_type'  => null,
        'from'      => null,
        'to'        => null,
        'action'    => null,
        'data'      => [],
    ];
    
    protected $msgType = 'BaseMessage';
    protected $fields  = [];
    protected $message = [];
    
    /**
     * 
     * @param array $args
     */
    public function __construct(Array $args=[])
    {
        $this->message = array_merge(self::FIELDS, $this->fields);
        $args['msg_type'] = $this->msgType;
        foreach($args as $name=>$value) {
            $this->$name = $value;
        }
        $this->timestamp = time();
    }
    
    /**
     * 
     * @param String $name
     * @return mixed|NULL
     */
    public function __get(String $name)
    {
        return (array_key_exists($name, $this->message)) ? $this->message[$name] : null;
    }
    
    /**
     * 
     * @param String $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set(String $name, $value)
    {
        if (array_key_exists($name, $this->message)) {
            $this->message[$name] = $value;
        } else {
            throw new \Exception("La propiedad $name no existe en ".get_class($this).PHP_EOL);
        }
    }

    /**
     * 
     * @return String
     */
    public function toJson() : String
    {
        return json_encode($this->message);
    }
    
}
