<?php
namespace lib\storage\Comscore\types;

class _String extends BaseType
{
    public function serialize($name, $type, $serializer, $model=null)
    {
        /**
         * @var $type \lib\model\types\BaseType
         */
        return [$name => $type->getValue()]; // TODO: sanitize if needed
    }
}
