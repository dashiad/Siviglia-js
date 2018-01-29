<?php
namespace model\reflection\Model\Type;
class StateType extends BaseType
{
        function getDefaultState()
        {
            return $this->definition["DEFAULT"];
        }
}
