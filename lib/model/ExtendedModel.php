<?php
namespace lib\model;
/*
        Sus indexFields deben ser relaciones a las claves del objeto padre
*/
class ExtendedModel extends MultipleModel
{
        protected $parentModelName;
        //protected $parentModel;
        protected $mainIndex;
        protected $relatedModel;
        protected $__forceNew=false;

        function __construct($serializer=null,$definition=null,$validationMode=null)
        {

                BaseModel::__construct($serializer,$definition,$validationMode);
                $this->parentModelName=$this->__objectDef["EXTENDS"];
                $this->__setRelatedModelName($this->__objectDef["EXTENDS"]);
        }

        function __isNew()
        {
            if($this->__forceNew)
                return true;
            return parent::__isNew();
        }

    function loadFromFields($serializer=null)
    {
        if($this->relatedModel)
        {
            $this->relatedModel->loadFromFields();
            $keys=$this->__key->getKeyNames();
            foreach($keys as $curKey)
            {
                $f=$this->__objectDef["FIELDS"][$curKey];
                $remF=array_values($f["FIELDS"]);
                $this->{$curKey}=$this->relatedModel->{$remF[0]};
            }
        }
        BaseModel::loadFromFields();
        return;
    }
        function __getRelatedModel()
        {
            if(!$this->relatedModel)
            {
                $keys=$this->__key->getKeyNames();
                $this->relatedModel=$this->{$keys[0]}[0];
                $this->relatedModel->__allowRelay(false);
            }
            return $this->relatedModel;
        }

        function __saveMembers($serializer) {


            if($this->__isNew())
            {
                if(!$this->__key->is_set())
                {
                    $this->__forceNew=true;
                    $instance=$this->__getRelatedModel();
                    $instance->setDirty(true);
                    $instance->save();
                    $this->__key->set($instance);
                }
            }
            else
            {

                // Solo se guarda el related, en caso de que se haya accedido.
                if($this->relatedModel)
                    $this->relatedModel->save();
            }

            BaseModel::__saveMembers($serializer);
            $this->__forceNew=false;
    }
    function __call($name,$arguments)
    {
        $instance=$this->__getRelatedModel();
        return call_user_func_array(array($instance,$name),$arguments);
    }
    function delete($serializer=null)
    {
        $this->__getRelatedModel()->delete();
        parent::delete();
    }
}


