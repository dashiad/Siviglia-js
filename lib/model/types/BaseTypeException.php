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
    const TXT_UNSET="Type is unset";
    const TXT_INVALID="Value [%value%] is not valid for this type";
    const TXT_TYPE_NOT_FOUND="Type [%type%] not found";
    const TXT_INCOMPLETE_TYPE="Type is incomplete";
    const TXT_SERIALIZER_NOT_FOUND="Serializer [%serializer%] not found";
    const TXT_TYPE_NOT_EDITABLE="Type is not editable";

    var $params;
    public function __construct($code,$params=null)
    {
        $this->params=$params;
        parent::__construct($code,$params);
    }
}
