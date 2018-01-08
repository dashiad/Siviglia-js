<?php
namespace lib\routing;
class Datasource
{
    static function getInstance($definition,$params,\Request $request)
    {
        switch($request->getOutputType())
        {
            case \Request::OUTPUT_JSON:
            {
                return new \lib\output\json\JsonDataSource($definition,$params,$request);
            }break;

        }
    }
}