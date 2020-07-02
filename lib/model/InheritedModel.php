<?php
namespace lib\model;
/*
        Sus indexFields deben ser relaciones a las claves del objeto padre
*/
class InheritedModel extends BaseModel
{
        protected $parentModelName;
        protected $parentModel;
        protected $mainIndex;
        protected $mainIndexField;
        function __construct($serializer=null,$definition=null,$validationMode)
        {
                BaseModel::__construct($serializer,$definition,$validationMode);

                $this->parentModelName=$this->__objectDef["INHERITS"];
                $s=\Registry::getService("model");
                $this->parentModel=$s->getModel($this->parentModelName);
                $parentDef=$this->parentModel->__objectDef;

                foreach($parentDef as $key=>$value)
                {
                    if($key!="TABLE" && $key!="INDEXES" && $key!="FIELDS" && !$this->__objectDef[$key])
                    {
                        $this->__objectDef[$key]=$value;
                    }
                }
                $keys=$this->__key->getKeyNames();
                $this->mainIndex=$keys[0];
                $this->mainIndexField=$this->__fields[$this->mainIndex];
        }
        function __setValidationMode($mode)
        {
            parent::__setValidationMode($mode); // TODO: Change the autogenerated stub
            $this->parentModel->__setValidationMode($mode);
        }

    function __getFields()
        {
            $localFields=parent::__getFields();
            $parentFields=$this->parentModel->__getFields();
            return array_merge($parentFields,$localFields);
        }

        function __getField($fieldName)
        {
            try
            {
                parent::__getField($fieldName);
            }catch(BaseModelException $e)
            {
                return $this->parentModel->__getField($fieldName);
            }
        }
        function __getFieldDefinition($fieldName)
        {
            try
            {
                parent::__getFieldDefinition($fieldName);
            }catch(BaseModelException $e)
            {
                return $this->parentModel->__getFieldDefinition($fieldName);
            }
        }


        function __get($varName)
        {
            try
            {
                BaseModel::__get($varName);
            }catch(\Exception $e)
            {
                $this->{$this->mainIndex}[0]->__get($varName);
            }
        }

        function __set($varName,$varValue)
        {
            try
            {
                BaseModel::__set($varName,$varValue);
            }catch(\Exception $e)
            {
                $this->{$this->mainIndex}[0]->{$varName}=$varValue;
            }
        }
}
