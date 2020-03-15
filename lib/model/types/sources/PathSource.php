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
        $raw=$this->parent->getPath($this->definition["PATH"]);
        // Si lo que se ha devuelto es un array simple, se construye uno por defecto.
        if(is_scalar($raw[0]))
        {
            $result=[];
            for($k=0;$k<count($raw);$k++)
            {
                $result[]=["VALUE"=>$raw[$k],"INDEX"=>$k,"LABEL"=>$raw[$k]];
            }
            return $result;
        }
        return $raw;
    }
}
