<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 11:19
 */

namespace lib\data\Cursor;


class CursorException extends \Exception
{
    const CANT_CREATE_DIRECTORY=1;
    const UNKNOWN_CURSOR_TYPE=2;
    const CURSOR_TYPE_NOT_SPECIFIED=3;
    const SOURCE_DOESNT_EXIST=4;
    const DESTINATION_DOESNT_EXIST=5;
    function __construct($code)
    {
        parent::__construct("[CursorException]",$code);
    }
}
