<?php
/**
 * Class BaseTypeException
 * @package lib\model\types
 *  (c) Smartclip
 */


namespace lib\model\types;


class BaseTypeException extends \lib\model\BaseException{

    const ERR_UNSET=1;
    const ERR_INVALID=2;
    const ERR_TYPE_NOT_FOUND=3;
    const ERR_INCOMPLETE_TYPE=4;
    const ERR_SERIALIZER_NOT_FOUND=7;
    const ERR_TYPE_NOT_EDITABLE=8;

    var $params;
    public function __construct($code,$params=null)
    {
        $this->params=$params;
        parent::__construct($code,$params);
    }
}
