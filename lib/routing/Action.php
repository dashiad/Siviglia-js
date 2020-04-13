<?php
namespace lib\routing;
class Action
{   const MODE_LOAD_FORM=0;
    const MODE_PROCESS_FORM=1;
    static function getInstance($params,\Request $request,$mode)
    {
        switch($request->getOutputType())
        {
            case \Request::OUTPUT_JSON:
            {
                $jds= new \lib\output\json\JsonAction($params,$request,$mode);
                return $jds;
            }break;

        }
    }
}
