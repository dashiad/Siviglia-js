<?php
namespace model\ads\Reporter\workers;

use model\web\Jobs\BaseWorker;

class DfpReport extends BaseWorker
{
    protected static $defaultName = 'dfp_report';

    protected function init()
    {
        //
    }
    
    protected function runItem($item)
    {
        return $item;
    }

}