<?php
/**
 * Class BaseTypeException
 * @package lib\model\types
 *  (c) Smartclip
 */


namespace lib\model\types;


use lib\storage\Persistence\NullSession;

class BaseTypeException extends \lib\model\BaseException{

    const ERR_UNSET=1;
    const ERR_INVALID=2;
    const ERR_TYPE_NOT_FOUND=3;
    const ERR_INCOMPLETE_TYPE=4;
    const ERR_SERIALIZER_NOT_FOUND=7;
    const ERR_TYPE_NOT_EDITABLE=8;
    const ERR_REQUIRED=9;
    const TXT_UNSET="Type is unset";
    const TXT_INVALID="Value [%value%] is not valid for this type";
    const TXT_TYPE_NOT_FOUND="Type [%type%] not found";
    const TXT_INCOMPLETE_TYPE="Type is incomplete";
    const TXT_SERIALIZER_NOT_FOUND="Serializer [%serializer%] not found";
    const TXT_TYPE_NOT_EDITABLE="Type is not editable";
    const TXT_REQUIRED="Field required :[%path%]";

    var $params;
    var $source;
    public function __construct($code,$params=null,$source=null)
    {
        $this->source=$source;
        $this->params=$params;
        parent::__construct($code,$params);
    }
    public function getFullPath()
    {
        $pathParts=[];

        if($this->source)
        {
            $source=$this->source;
            while(is_a($source,'\lib\model\types\BaseType'))
            {
                $pathParts[]=$source->__getFieldName();
                $source=$source->__getParent();
            }
        }
        return implode("/",array_reverse($pathParts));
    }
}
