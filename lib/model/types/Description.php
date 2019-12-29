<?php
namespace lib\model\types;
class Description extends Text
{

    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Description.php");
        return '\model\reflection\Types\meta\Description';
    }

}
