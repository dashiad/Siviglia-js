<?php 
namespace lib\model\types;
class UserId extends UUID
{
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/UserId.php");
        return '\model\reflection\Types\meta\UserId';
    }
}
