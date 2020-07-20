<?php
namespace lib\php;
class CronjobHandler {


    public function __construct() {
        $path_length     = strrpos(__FILE__, "/");
        $this->path      = substr(__FILE__, 0, $path_length) . '/';
        $this->handle    = 'crontab_temp.txt';
        $this->cron_file = "{$this->path}{$this->handle}";
    }

    public function write_to_file()
    {
        if ( ! $this->crontab_file_exists())
        {
            $this->cron_file = "{$this->path}{$this->handle}";

            $init_cron = "crontab -l > {$this->cron_file} && [ -f {$this->cron_file} ] || > {$this->cron_file}";

            exec($init_cron);
        }

        return $this;
    }

    public function remove_cronjob($cron_jobs=NULL)
    {
        $this->write_to_file();

        $cron_array = file($this->cron_file, FILE_IGNORE_NEW_LINES);

        $original_count = count($cron_array);

        $not_erased_items = array();
        if (is_array($cron_jobs))
        {
            foreach ($cron_jobs as $cron_regex) {
                foreach($cron_array as $item_in_cron){
                    if($item_in_cron != $cron_regex) {
                        $not_erased_items[] = $item_in_cron;
                    }
                }
                $cron_array = $not_erased_items;
            }
        }
        else
        {
            foreach($cron_array as $item_in_cron){
                if($item_in_cron != $cron_jobs) {
                    $not_erased_items[] = $item_in_cron;
                }
            }
        }
        $cron_array = $not_erased_items;

        if($original_count === count($cron_array)) {
            $this->remove_file();
        } else {
            $this->remove_crontab();
            $this->append_cronjob($cron_array);
        }
    }

    public function remove_crontab()
    {
        exec("crontab -r");
        $this->remove_file();

        return $this;
    }

    public function remove_file()
    {
        if ($this->crontab_file_exists()) {
            exec("rm {$this->cron_file}");
        }

        return $this;
    }

    public function append_cronjob($cron_jobs=NULL)
    {
        if (is_null($cron_jobs)) {
            return $this;
        }

        $append_cronfile = "echo '";

        $append_cronfile .= (is_array($cron_jobs)) ? implode("\n", $cron_jobs) : $cron_jobs;

        $append_cronfile .= "'  >> {$this->cron_file}";

        $install_cron = "crontab {$this->cron_file}";

        $this->write_to_file();
        exec($append_cronfile);
        exec($install_cron);
        $this->remove_file();

        return $this;
    }

    private function crontab_file_exists()
    {
        return file_exists($this->cron_file);
    }
}