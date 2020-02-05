<?php
namespace model\web\Jobs\App\Jobs\Splitters;

interface SplitterInterface
{
    public function get(Array $params) : Array;
}