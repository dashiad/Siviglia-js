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
        if ($this->model->__isNew())
        {
            $this->model->created_at = "NOW";
        } else {
            $this->model->updated_at = "NOW";
        }
    }
    
    public function persist()
    {
        $this->beforePersist();
        if (empty($this->model))
            $this->model = new $this->modelName;
        $this->fill();
        $this->model->save();
        $this->afterPersist();
    }   
    
    protected function beforePersist() {}
    protected function afterPersist() {}
}