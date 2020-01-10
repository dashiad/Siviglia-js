<?php


namespace lib\model\types\sources;


class SourceFactory
{
    static function getSource($parent,$definition,$useValidatingData=false)
    {
        switch($definition["TYPE"])
        {
            case "Array":
                {
                    return new ArraySource($parent,$definition,$useValidatingData);
                } break;
            case "DataSource":
                {
                    return new DataSource($parent,$definition,$useValidatingData);
                }break;
            case "Path":{
                    return new PathSource($parent,$definition,$useValidatingData);
            }break;
        }
    }
}
