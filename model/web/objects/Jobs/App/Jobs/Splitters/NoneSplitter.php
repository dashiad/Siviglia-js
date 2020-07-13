<?php
namespace model\web\Jobs\App\Jobs\Splitters;

class NoneSplitter implements SplitterInterface
{

    public function get(array $params) : array
    {
        //return [["NoneSplitter_dummy_item"]];
        return [[$params]];
    }
}

