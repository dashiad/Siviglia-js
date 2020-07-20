<?php
namespace model\web\Jobs\App\Jobs\Splitters;

class JobDescriptorSplitter implements SplitterInterface
{

    public function get(array $params) : Array
    {
        return $params['jobs'];
    }
}