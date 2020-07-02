<?php
namespace lib\model\types;
use lib\model\BaseTypedException;

class State extends Enum
{
    protected $changing=false;
    function getDefaultState()
    {
        return $this->__definition["DEFAULT"];
    }
    function _setValue($val,$validationMode=null)
    {
        if($val===$this->value)
            return;
        if($validationMode!==\lib\model\types\BaseType::VALIDATION_MODE_NONE) {
            if ($this->changing == true) {

                throw new BaseTypedException(BaseTypedException::ERR_DOUBLESTATECHANGE);
            }
            // Cuando se intenta cambiar el campo de estado de un objeto, hay que comprobar
            $st = $this->__controller->getStateDef();
            $st->changeState($val);
        }
        parent::_setValue($val);
    }

    function onStateChangeComplete()
    {
        // Si el cambio de estado se ha completado, se puede volver a cambiar el estado.
        $this->changing=false;
    }
}
