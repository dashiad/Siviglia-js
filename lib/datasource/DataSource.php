<?php
namespace lib\datasource;
class DataSourceException extends \lib\model\BaseException
{
    const ERR_DATASOURCE_IS_GROUPED=5;
    const ERR_NO_SUCH_DATASOURCE=1;
    const ERR_INVALID_DATASOURCE_PARAM=2;
    const ERR_PARAM_REQUIRED=3;
    const ERR_UNKNOWN_CHILD_DATASOURCE=4;
    const ERR_NO_MODEL_OR_METHOD=5;
}
abstract class DataSource extends \lib\model\BaseTypedObject
{
        
    var $isLoaded=false;

    abstract function fetchAll();    
    abstract function getIterator($rowInfo=null);
    function isLoaded()
    {
        return $this->isLoaded;
    }
    function getFieldsKey()
    {
        return "PARAMS";
    }
}

abstract class TableDataSource extends DataSource {
    abstract function count();
    abstract function countColumns();
    abstract function getMetaData();
}


?>
