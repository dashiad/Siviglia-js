<?php


namespace lib\model\types\sources;


class SourceFactory
{
    static function getSource($parent,$definition)
    {
        switch($definition["TYPE"])
        {
            case "Array":
                {
                    return new ArraySource($parent,$definition);
                } break;
            case "DataSource":
                {
                    return new DataSource($parent,$definition);
                }break;
            case "Path":{
                    return new PathSource($parent,$definition);
            }break;
        }
    }
}