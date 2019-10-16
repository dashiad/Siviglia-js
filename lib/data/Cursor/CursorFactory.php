<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 13:31
 */

namespace lib\data\Cursor;
include_once(__DIR__."/CursorException.php");
include_once(__DIR__."/BaseCursor.php");

class CursorFactory
{
    /*
     *  REQUIERE QUE EN $params HAYA UN CAMPO "TYPE", QUE APUNTA AL NOMBRE DE LA CLASE, SIN LA PALABRA CURSOR.
     */
    static function getCursor($params)
    {
        if(is_a($params,'\lib\data\Cursor\BaseCursor'))
            return $params;
        if(!isset($params["TYPE"]))
            throw new \lib\data\Cursor\CursorException(\lib\data\Cursor\CursorException::CURSOR_TYPE_NOT_SPECIFIED);
        $includeFile=__DIR__.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR,$params["TYPE"])."Cursor.php";

        if(!is_file($includeFile))
            throw new \lib\data\Cursor\CursorException(\lib\data\Cursor\CursorException::UNKNOWN_CURSOR_TYPE);
        include_once($includeFile);
        $className='lib\data\Cursor\\'.$params["TYPE"]."Cursor";
        $cursor=new $className();
        $cursor->init($params);
        return $cursor;
    }
}
