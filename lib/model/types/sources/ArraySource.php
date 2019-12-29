<?php


namespace lib\model\types\sources;
/*
 *   Definition:
 *   "SOURCE":[
 *      "TYPE":"Array",
 *      "DATA":[
 *              [key1:value1,key2:value2,key3:value3]...]
 *      "LABEL":<field>,
 *      "VALUE":<field>
 *   ]
 *
 */

class ArraySource extends BaseSource
{
    function getData()
    {
        return $this->definition["DATA"];
    }
}