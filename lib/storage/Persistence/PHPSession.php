<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 25/12/2017
 * Time: 23:57
 */

namespace lib\storage\Persistence;


class PHPSession implements \ArrayAccess
{
    var $data=array();
    static $id=null;
    function __construct()
    {
        $this->startSession();
    }
    function startSession()
    {
        if(PHPSession::$id===null) {
            session_start();
            $this->data = $_SESSION;
        }
        PHPSession::$id=session_id();
    }
    function getId()
    {
        $this->startSession();
        return session_id();
    }
    function persist($key,$value)
    {
        $this->data[$key]=$value;
    }
    function retrieve($key)
    {
        return io($this->data,$key,null);
    }
    function remove($key)
    {
        if(isset($this->data[$key]))
            unset($this->data[$key]);
    }
    function save()
    {
        $_SESSION=$this->data;
    }
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->retrieve($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->persist($offset,$value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
