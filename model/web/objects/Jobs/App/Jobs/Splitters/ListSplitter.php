<?php
namespace model\web\Jobs\App\Jobs\Splitters;

class ListSplitter implements SplitterInterface
{
    
    public function get(Array $params) : Array
    {
        return array_chunk($params['items'], $params['max_chunk_size']);
    }

}