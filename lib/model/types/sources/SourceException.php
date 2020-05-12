<?php


namespace lib\model\types\sources;


class SourceException extends \lib\model\BaseException
{
    const ERR_INVALID_SOURCE=1;
    const TXT_INVALID_SOURCE="Invalid source: [%source%]";
}