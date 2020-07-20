<?php

namespace lib\model\types\sources;

/*
 *  LA DEFINICION ES:
 *  PATH=/a/b/c/{%/a/b/c%}
 */
class PathSource extends BaseSource
{
    function getData()
    {
        $raw=$this->parent->getPath($this->__definition["PATH"]);
        // Si lo que se ha devuelto es un array simple, se construye uno por defecto.
        if(is_scalar($raw[0]))
        {
            $data=[];
            for($k=0;$k<count($raw);$k++)
            {
                $data[]=["VALUE"=>$raw[$k],"INDEX"=>$k,"LABEL"=>$raw[$k]];
            }
        }
        else
            $data=$raw;
        if(isset($this->__definition["PREPEND"]))
            $data=array_merge($this->__definition["PREPEND"],$data);
        if(isset($this->__definition["APPEND"]))
            $data=array_merge($data,$this->__definition["APPEND"]);
        return $data;
    }
}
