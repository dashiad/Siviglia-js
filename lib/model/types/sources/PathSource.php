<?php

namespace lib\model\types\sources;

/*
 *  LA DEFINICION ES:
 *  PATH=/a/b/c/{/a/b/c}
 */
class PathSource extends BaseSource
{
    function getData()
    {
        return $this->parent->getPath($this->definition["PATH"]);
    }
}