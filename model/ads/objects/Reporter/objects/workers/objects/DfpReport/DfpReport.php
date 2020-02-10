<?php
namespace model\ads\Reporter\workers;

use model\web\Jobs\BaseWorker;

class DfpReport extends BaseWorker
{
    protected $name = 'dfp_report';
}