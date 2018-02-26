<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 22/02/2018
 * Time: 19:18
 */

namespace lib\model\states;
/**
 * Class CallbackCollection
 * @package lib\model\states
 *
 * Un callbackcollection tiene una definicion, y se le pide que la aplique de diferentes formas.
 *  Primero, la definicion:
 *  La definicion es un diccionario, de tipo clave=>valor.
 *  El valor tiene que tener un campo "TYPE" que puede ser "METHOD" o "PROCESS"
 *  Si es "METHOD", puede contener:
 *      - PATH: Si existe, el metodo se llamará sobre el objeto que existe en ese path, a partir del modelo de referencia.
 *      - PARAMS: Si existe, especifica los parametros que se pasaran al metodo.
 *  Si es "PROCESS" , contiene:
 *      - "METHODS": lista de entradas de la defincion, que hay que aplicar.
 *      Es decir, un process es una forma de agrupar callbacks en serie.
 *
 *  Una vez que se tiene esta definicion, a este objeto se le puede pedir que aplique un conjunto de callbacks, usando un
 *  modelo de referencia.
 *  Esta ejecucion puede ser en modo "TEST", en cuyo caso un valor de retorno "false" de alguno de los callbacks, hace que
 *  la ejecucion termine, y se retorne false.
 *  Si es en modo "LINEAR", se ejecutarán todos los callbacks (siempre que no sea interrumpido por una excepcion,etc).
 *
 *  Ejemplo de definicion:
 *  array(
 *     "SIMPLE"=>array("TYPE"=>"METHOD","METHOD"=>"aa"),
 *     "SIMPLE_1"=>array("TYPE"=>"METHOD","METHOD"=>"aa","PARAMS"=>array(1,2,3)),
 *     "SIMPLE_2"=>array("TYPE"=>"METHOD","METHOD"=>"aa","PARAMS"=>array(1,2,3),"PATH"=>"/id_element/"),
 *     "PROCESS_1"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("SIMPLE","SIMPLE_1"),
 *      "PROCESS_2"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("PROCESS_1","SIMPLE_2")
 *  )
 *
 * Ejemplo de llamada:
 * (array("PROCESS_2"), $this, "LINEAR")
 * (array("SIMPLE","SIMPLE_1"),$this,"TEST")
 */

class CallbackCollectionException extends \lib\model\BaseException
{
    const ERR_NO_SUCH_CALLBACK=1;
    const ERR_UNKNOWN_CALLBACK_TYPE=2;
    const TXT_NO_SUCH_CALLBACK="Callback not found: [%callback%]";
    const TXT_UNKNOWN_CALLBACK_TYPE="Unknown callback type [%type%]";
}
class CallbackCollection
{
    function __construct($definition)
    {
        $this->definition=$definition;
    }

    function apply($callbackSpec,$referenceModel,$mode="LINEAR")
    {
        return $this->applyList($callbackSpec,$referenceModel,$mode);
    }
    private function applyList($callbackSpec,$referenceModel,$mode="LINEAR")
    {
        foreach($callbackSpec as $key=>$value)
        {
            $result=$this->applyObject($value,$referenceModel,$mode);
            if(!$result && $mode=="TEST")
                return false;
        }
        return true;
    }
    private function applyObject($name, $referenceModel,$mode)
    {
        if(!isset($this->definition[$name]))
            throw new CallbackCollectionException(CallbackCollectionException::ERR_NO_SUCH_CALLBACK,array("callback"=>$name));
        $cdef=$this->definition[$name];
        $target=$referenceModel;
        if(isset($cdef["PATH"]))
            $target=$referenceModel->getPath($cdef["PATH"],null);

        switch($cdef["TYPE"])
        {
            case "METHOD":{
                $params=isset($cdef["PARAMS"])?$cdef["PARAMS"]:array();
                $result=call_user_func_array(array($target,$cdef["METHOD"]),$params);
                if(!$result && $mode=="TEST")
                    return $result;
            }break;
            case "PROCESS":{
                return $this->applyList($cdef["CALLBACKS"],$referenceModel,$mode);
            }break;
        }
        return true;
    }
}