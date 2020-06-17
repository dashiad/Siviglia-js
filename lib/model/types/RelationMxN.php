<?php
 namespace lib\model\types;
 class RelationMxN extends \lib\model\types\InverseRelation
 {
    protected $relationModelName;
    protected $remoteModelName;
    protected $relationFields;
    protected $relationModelInstance;
    protected $relationModelMapping;
    protected $localModelMapping;
    protected $remoteModelMapping;
    protected $relationIndexType;
    protected $relationModelIndexes;
    protected $uniqueRelations;
    function __construct($name,$definition, $parentType, $value=null, $validationMode=null)
    {
        $this->relationModelName=\lib\model\ModelService::getModelDescriptor($definition["MODEL"]);
        $this->remoteModelName=\lib\model\ModelService::getModelDescriptor($definition["REMOTE_MODEL"]);
        // Se necesita la definicion del objeto relacion.
        $this->uniqueRelations=isset($definition["RELATIONS_ARE_UNIQUE"])?$definition["RELATIONS_ARE_UNIQUE"]:false;
        $m1=$definition["MODEL"];
        $this->relationModelInstance=new $m1();
        $this->remoteTable=$this->relationModelInstance->__getTableName();
        $rMD=$this->relationModelInstance->getDefinition();
        $this->relationFields=$rMD["MULTIPLE_RELATION"]["FIELDS"];
        foreach($this->relationFields as $value)
        {
            $f=$rMD["FIELDS"][$value]["MODEL"];
            $ff=array_values($rMD["FIELDS"][$value]["FIELDS"]);
            // TODO: Aqui se supone que $parentType es un BaseModel, no simplemente un container!
            // Esto habria que comprobarlo, y tirar une excepcion si no es asi!
            if($parentType->__getObjectNameObj()->equals($f))
            {
                 $this->relationModelMapping["local"]=$value;
                 $this->localModelMapping[$ff[0]]=$value;
            }
            else
            {
                $relationModelMapping["remote"]=$f;
                $this->remoteModelMapping[$ff[0]]=$value;
            }
        }
        $indexes=$rMD["INDEXFIELDS"];
        $this->relationModelIndexes=$indexes;
        $this->relationIndexType=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($this->relationModelName,$indexes[0],$name,$parentType);
        parent::__construct($name,$definition, $parentType, $value, $validationMode);

    }
    function createRelationValues()
    {
        return new MultipleRelationValues($this,isset($this->definition["LOAD"])?$this->definition["LOAD"]:"LAZY");
    }
     function isAlias()
     {
         return true;
     }


    function getRelationModelName()
    {
        return $this->relationModelName;
    }

    function getRemoteModelName()
    {
        return $this->remoteModelName;
    }

    function getRelationModelMapping()
    {
        return $this->relationModelMapping;
    }
    // Keys son los campos locales.Valores son los campos de la tabla relacion.
    function getLocalMapping()
    {
        return $this->localModelMapping;
    }
    // Keys son los campos remotos.Valores son los campos de la tabla relacion.
    function getRemoteMapping()
    {
        return $this->remoteModelMapping;
    }
    function getRelationIndexes()
    {
        return $this->relationModelIndexes;
    }
    // Devuelve el tipo del campo indice de la tabla relacion.
    function getRelationIndexType()
    {
        return $this->relationIndexType;
    }
    function getRelationModelInstance()
    {
        return $this->relationModelInstance;
    }
    function onModelSaved()
    {
        if(!$this->relation->is_set() && $this->getModel()->__isNew())
        {
            // Tenemos los objetos A y B. B tiene una relacion con A, asi que A tiene una relacion inversa con B, y esta relacion es un alias, y esta clase es ese alias.
            // Aqui estamos en caso de que se ha creado un A, y, a traves de el, uno o varios B.Ahora se ha guardado A, asi que tenemos que copiar el campo relacion, de A, a todos
            // los B que se hayan creado.
            $nObjects=$this->relationValues->count();
            $this->relation->setFromModel($this->getModel());
            for($k=0;$k<$nObjects;$k++)
            {
                $cObject=$this->relationValues[$k];
                $this->relation->setToModel($cObject);
            }
            $this->relationValues->save();

        }

        //$this->relation->cleanState();
    }
    function relationsAreUnique()
    {
        return $this->uniqueRelations;
    }
    function delete($value)
    {
        $this->relationValues->delete($value);
        $this->relationValues->reset();
    }
    function add($value)
    {
        $this->relationValues->add($value);
        $this->relationValues->reset();
    }
}

class MultipleRelationValues extends \lib\model\types\base\RelationValues
{
    function delete($value)
    {
        // TODO: Optimizar para hacer el minimo numero de queries posibles.
        $type=$this->recognizeType($value);

        if(!is_array($type))
        {
            $type=array($type);
            $value=array($value);
        }
        $relInstance=$this->relField->getRelationModelInstance();
        $serializer=$relInstance->__getSerializer();

        for($k=0;$k<count($type);$k++)
        {
            switch($type[$k])
            {
                case "remote":
                    {
                        // Tengamos A y B, relacionadas por C.
                        // Se nos pasa a borrar, una instancia de B.
                        // Por lo tanto, no tenemos los campos indice de C.Lo que podemos tener son los campos de C que relacionan A con B.
                        $remFields=$this->relField->getRemoteMapping();
                        // Hay que crear una instancia de C, y borrar en base a esos campos
                        $fields=[];
                        foreach($remFields as $key2=>$value2)
                        {
                            $relInstance->{$value2}=$value[$k]->{$key2};
                            $fields[]=$value2;
                        }
                        $locFields=$this->relField->getLocalMapping();
                        foreach($locFields as $key2=>$value2)
                        {
                            $relInstance->{$value2}=$this->relField->getModel()->{$key2};
                            $fields[]=$value2;
                        }
                        // Se hace un borrado por campos.
                        $serializer->delete($relInstance,$fields);
                    }break;
                case "relation":
                    {
                        // Tengamos A y B, relacionadas por C.
                        // Se nos pasa a borrar una instancia de C.
                        // Por lo tanto, eliminamos solo esa relacion entre A y C.
                        $serializer->delete($value[$k]);
                    }break;
                // Value se refiere a un id de la tabla relacion.
            case "value":
                {
                    $instance=$this->relField->getRelationModelInstance();
                    $index=$instance->getIndexFields();
                    $firstIndex=$index[0];
                    $instance->{$firstIndex}=$value[$k];
                    $serializer->delete($instance);
                }break;
            }
        }

    }


    function add($value)
    {
        // TODO : Optimizar para hacer el minimo numero de queries posibles.
        // Al aniadir, hay 2 casos: que el objeto relacion sea nuevo, o que ya exista.
        $relInstance=$this->relField->getRelationModelInstance();
        $serializer=$relInstance->__getSerializer();
        $uniques=$this->relField->relationsAreUnique();
        $modelService=\Registry::getService("model");
         $type=$this->recognizeType($value);

        if(!is_array($type))
        {
            $type=array($type);
            $value=array($value);
        }
        for($k=0;$k<count($type);$k++)
        {
            switch($type[$k])
            {
                case "value":
            case "remote":
                {
                    if($type[$k]=="remote") {
                        // Relacion A con B a traves de C. Nos han pasado un B.Hay por tanto que crear una instancia de C, y asignar campos.
                        // Si el objeto a relacionar (B) esta sucio, hay que guardarlo.
                        if ($value[$k]->isDirty())
                            $value[$k]->save();
                    }
                    $newInstance=$this->relField->getRelationModelName()->getInstance();
                    // Por lo tanto, no tenemos los campos indice de C.Lo que podemos tener son los campos de C que relacionan A con B.
                    // Esto significa que se borran *todas* las relaciones entre A y B que existen en C.
                    $remFields=$this->relField->getRemoteMapping();
                    foreach($remFields as $key2=>$value2) {
                        if($type[$k]=="remote")
                            $newInstance->{$value2} = $value[$k]->{$key2};
                        else
                            $newInstance->{$value2} = $value[$k];
                    }
                    $locFields=$this->relField->getLocalMapping();
                    foreach($locFields as $key2=>$value2)
                        $newInstance->{$value2}=$this->relField->getModel()->{$key2};
                    $newInstance->save();
                }break;
            case "relation":
                {
                    // Se ha recibido una instancia de la relacion.Hay que asignar el campo que apunta a este objeto.
                    $locFields=$this->relField->getLocalMapping();
                    foreach($locFields as $key2=>$value2)
                        $value[$k]->{$value2}=$this->relField->getModel()->{$key2};
                    $value[$k]->save();
                }break;
                // Se ha recibido un id del objeto remoto.

            }
        }

    }
    function recognizeType($value,$allowArray=true)
    {
        if(is_object($value))
        {
            $type=get_class($value);
            $relName=$this->relField->getRemoteModelName()->getNamespaced();
            // El valor es una instancia del objeto relacion.Lo que necesitamos son sus campos indices.
            if($type==substr($relName,1))
            {
                return "remote";
            }
            $remName=$this->relField->getRelationModelName()->getNamespaced();
            if($type==substr($remName,1))
            {
                return "relation";
            }
        }
        if(is_array($value))
        {
            if($allowArray==false)
            {
                // TODO: lanzar excepcion.
                return;
            }
            foreach($value as $p)
            {
                $results[]=$this->recognizeType($p,false);
            }
            return $results;
        }
        return "value";
    }
}



?>
