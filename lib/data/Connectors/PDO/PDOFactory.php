<?php
/**
 * Class PDOFactory
 * @package lib\data\Cursor\PDO
 *  (c) Smartclip
 */


namespace lib\data\Cursor\PDO;


class PDOFactory
{
    static $connectionPool;
    static function getConnection($connectionString,$userName,$password)
    {
        $key=$connectionString.$userName.$password;
        if(!isset(PDOFactory::$connectionPool[$key]))
        {
            PDOFactory::$connectionPool[$key]=new \PDO($connectionString, $userName, $password);
        }
        return PDOFactory::$connectionPool[$key];

    }
}
