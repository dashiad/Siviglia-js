<?php
namespace model\web\Jobs\App\Jobs\Workers;

class DirectoryListWorker extends Worker
{
    
    protected $name = 'dir_ls_worker';

    protected function init()
    {
        //
    }
    
    public function runItem($directory)
    {
        $result = $directory.":";
        foreach(scandir($directory) as $file) {
            $result.=$file.",";
        }
        return $result;
    }

}