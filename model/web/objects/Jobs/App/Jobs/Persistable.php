<?php
namespace model\web\Jobs\App\Jobs;

trait Persistable
{
    
    protected $model = null;
    //protected $modelName;
    //protected $fields = [];
    
    public function fill()
    {
        foreach ($this->fields as $key=>$value) {
            if (property_exists($this, $value)) {
                if (is_array($this->{$value})) {
                    $this->model->{$key} = json_encode($this->{$value});
                } else {
                    $this->model->{$key} = $this->{$value};
                }
            } else {
                $this->model->{$key} = null;
            }
        }
        /*$date = date('Y-m-d H:i:s', time());
        if (empty($this->model->created_at))
        {
            $this->model->created_at = $date;
        } else {
            $this->model->updated_at = $date;
        }*/
    }
    
    public function persist()
    {
        if (empty($this->model))
            $this->model = new $this->modelName;
        $this->fill();
        $this->model->save();
    }
    
}