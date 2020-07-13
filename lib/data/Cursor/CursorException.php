<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 11:19
 */

namespace lib\data\Cursor;


class CursorException extends \lib\model\BaseException
{
    const ERR_CANT_CREATE_DIRECTORY=1;
    const ERR_UNKNOWN_CURSOR_TYPE=2;
    const ERR_CURSOR_TYPE_NOT_SPECIFIED=3;
    const ERR_SOURCE_DOESNT_EXIST=4;
    const ERR_DESTINATION_DOESNT_EXIST=5;
    const ERR_CALLBACK_RETURN_VALUE_MUST_BE_AN_ARRAY=6;
    const TXT_CALLBACK_RETURN_VALUE_MUST_BE_AN_ARRAY="Callback return value must be an array";
}
