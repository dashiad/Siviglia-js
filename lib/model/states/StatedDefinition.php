<?php
namespace lib\model\states;

use \lib\model\BaseTypedException;

/*
 * Ejemplo complejo de defincion de estado:
 *
 * array(
               'STATES' => array(
                'LISTENER_TAGS'=>array(
                "APPLY_WALLET"=>"applyWallet",
                "PRODUCTS_ARE_PUBLISHED"=>array(
                    "METHOD"=>"changeProducts",
                    "PARAMS"=>\backoffice\ps_product\PercentilProduct::STATE_PUBLISHED
                ),
                "SEND_PAID_EMAIL"=>array("METHOD"=>"sendEmail","PARAMS"=>array("paidEmail")),
                "REMOVE_FROM_PICKING"=>"removeFromPicking",
                "RETURN_PAYMENT"=>"returnPayment",
                "ORDER_IS_RETURNED"=>"doFullReturn",
                "ORDER_IS_RETURNED_WITHOUT_PAYMENT"=>array("METHOD"=>"fullReturn","PARAMS"=>array(\backoffice\ps_orders\OrderDetailReturnReasons::REASON_REJECTED_COD)),
                "ORDER_IS_LOST"=>"onLost",
                "SET_AS_PAID"=>"setAsPaid",
                "SET_AS_NOT_PAID"=>"setAsNotPaid",

                "TEST_PRODUCTS_AVAILABLE"=>"productsAreAvailable",
                "TEST_PAYMENT_OK"=>"hasPaymentOk",
                "TEST_NO_PICKING_MISSES"=>"hasNoPickingMisses",
                "TEST_NOT_PAID"=>"hasNoPayment",
                "TEST_NO_RETURNS"=>"hasNoReturns",
                "TEST_PAID"=>"isPaid"
            ),
            "STATES"=>array(
            ps_order_state::STATE_ORDER_NONE=>array(
                    'LISTENERS'=>array('TEST'=>array(),'ON_LEAVE'=>array(),'ON_ENTER'=>array()),
                    'FIELDS' => array('EDITABLE' => array('*'),'REQUIRED'=>array(),'FIXED'=>array(),'SET'=>array()),
                    'PERMISSIONS'=>array(
                        "ADD"=>array(array("REQUIRES"=>"ADD","ON"=>"/model/web/Page")),
                        "DELETE"=>array(array("REQUIRED"=>"DELETE","ON"=>"/model/web/Page")),
                        "EDIT"=>[["REQUIRES"=>"ADMIN","ON"=>"/model/web/Page")),
                        "VIEW"=>[["REQUIREs"=>"VIEW","ON"=>"/model/web/Page"
                    )
            ),
            ps_order_state::STATE_ORDER_PAID=>array(
                    'ALLOW_FROM'=>array( ps_order_state::STATE_ORDER_NONE,
                            ps_order_state::STATE_ORDER_WAITING_PAYMENT,
                            ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR,
                            ps_order_state::STATE_PAYMENT_ERROR ),
                    'LISTENERS'=>array(
                        'TESTS'=>array("TEST_PRODUCTS_AVAILABLE","TEST_PAYMENT_OK"),
                        'ON_ENTER'=>array_merge(array("APPLY_WALLET"),$paidAct,array("SEND_PAID_EMAIL", "CREATE_INVOICE"))
        )
    ),

            ps_order_state::STATE_ORDER_WAITING_PAYMENT=>array(
                'ALLOW_FROM'=>array(ps_order_state::STATE_ORDER_NONE),
                'LISTENERS'=>array('TESTS'=>array("TEST_PRODUCTS_AVAILABLE"),
                    'ON_ENTER'=>$paidAct
                )
            ),
    ps_order_state::STATE_ORDER_CANCELLED=>array(
        'IS_FINAL'=>1,
        'ALLOW_FROM'=>array(ps_order_state::STATE_ORDER_PAID,ps_order_state::STATE_ORDER_PICKED,
            ps_order_state::STATE_ORDER_PROCESSED,ps_order_state::STATE_ORDER_WAITING_PAYMENT,
            ps_order_state::STATE_PAYMENT_ERROR,ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR,
            ps_order_state::STATE_ORDER_REVIEWED,ps_order_state::STATE_ORDER_OK,ps_order_state::STATE_ORDER_NONE
        ),
        'LISTENERS'=>array(
            'TESTS'=>array("TEST_PAID"),

            'ON_ENTER'=>array(
                'STATES'=>array(
                    // ORDER CANCELLED DESDE ORDER PAID
                    ps_order_state::STATE_ORDER_PAID=>$cancelledAct,
                    ps_order_state::STATE_ORDER_WAITING_PAYMENT=>array_merge($cancelledAct,array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL")),
                    ps_order_state::STATE_PAYMENT_ERROR=>array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL","ADD_HISTORY"),
                    ps_order_state::STATE_ORDER_PAYMENT_AMOUNT_ERROR=>array("REMOVE_CART_RESERVES","SEND_CANCELLED_EMAIL","ADD_HISTORY"),
                    ps_order_state::STATE_ORDER_REVIEWED=>$cancelledAct,
                    ps_order_state::STATE_ORDER_OK=>$cancelledAct,
                    ps_order_state::STATE_ORDER_PICKED=>array_merge($cancelledAct,array("REMOVE_FROM_PICKING")),
                    ps_order_state::STATE_ORDER_PROCESSED=>array_merge($cancelledAct,array("REMOVE_FROM_PICKING")),
                    ps_order_state::STATE_ORDER_NONE=>array()
                )
            ),
            // Hay que tener un REJECT_TO ya que puede ser que nos llegue una notificacion asincrona de pago, para un pedido que ya esta cancelado.
            'REJECT_TO'=>array(
                'STATES'=>array(
                    ps_order_state::STATE_ORDER_PAID=>$cancelledAct
                )
            )
        )
    ),

               )
            );
 *
 *
 *
 */
class StatedDefinition
{
        var $definition;
        var $hasState;
        var $stateField;
        var $model;
        var $onlyDefault;
        var $stateFieldObj=null;
        var $stateType;
        var $oldState=null;
        var $newState=null;
        var $newStateLabel=null;
        var $oldStateLabel=null;
        var $changingState=false;
        var $pathPrefix='';
        function __construct(& $model,$definition=null)
        {
            $this->model=$model;
            $this->definition= $definition!==null?$definition:$model->getDefinition();
            $this->pathPrefix=$model->getPathPrefix();
        }
        function setOldState($state)
        {
            //$this->oldState=$this->getStateLabel($state);
            $this->oldState = $state;
            $this->oldStateLabel = $this->getStateLabel($state);
        }
        function setNewState($state)
        {
            $this->newState=$state;
            $this->newStateLabel = $this->getStateLabel($state);
        }

        function getNewState()
        {
            if($this->newState)
                return $this->newState;
            return $this->stateType->getValue();
        }
        function getOldState()
        {
             if($this->oldState)
                 return $this->oldState;
            return $this->getStateFieldObj()->getValue();
        }

        function reset()
        {
            $this->oldState=null;
            $this->oldStateLabel=null;
            $this->newState=null;
            $this->newStateLabel=null;
        }

        function disable()
        {
            $this->hasState=false;
        }
        function enable()
        {
            $this->hasState=isset($this->definition["STATES"])?true:false;
            if($this->hasState)
            {
                $this->stateField=$this->definition["STATES"]["FIELD"];
                if($this->stateField[0]!==$this->pathPrefix)
                    $this->stateField=$this->pathPrefix.$this->stateField;
                $this->stateFieldObj=$this->model->__getField($this->stateField);
                $this->stateType=$this->stateFieldObj;
            }
        }
        function getCurrentState()
        {
                if(!$this->hasState)
                    return null;

            if($this->stateType->hasValue())
                return $this->stateType->getValue();
            return $this->getDefaultState();
        }
        function getStateField()
        {
            if($this->hasState)
                return $this->definition["STATES"]["FIELD"];
            return null;
        }
        function hasStates()
        {
            return $this->hasState;
        }
        function getStates()
        {
            if($this->hasState)
                return $this->definition["STATES"]["STATES"];
            return null;
        }
        function getDefaultState()
        {
            if(!$this->hasState)
                return null;
            if($this->stateType->getDefaultState()!==null)
                return $this->stateType->getDefaultState();

            if($this->definition["STATES"]["DEFAULT_STATE"])
                {
                    $position=array_search($this->definition["STATES"]["DEFAULT_STATE"],
                                           array_keys($this->definition["STATES"]["STATES"])
                                           );
                    if($position!==false)
                    return $position;
                }
            return 0;
        }
        function getStateFieldObj()
        {
            return $this->stateFieldObj;
        }
        function getStateId($name)
        {
            return $this->stateType->getValueFromLabel($name);
        }
        function isFinalState($label)
        {
            if(!is_string($label))
                $label=$this->getStateLabel($label);
            return \io($this->definition["STATES"]["STATES"][$label],"FINAL",false);
        }
        function getStateLabel($id)
        {
            if(!is_numeric($id))
                return $id;
            $labels= $this->stateType->getLabels();
            return $labels[$id];
        }
        function getCurrentStateLabel()
        {
            return $this->getStateLabel($this->getCurrentState());
        }
        function checkState()
        {
            if(!$this->hasState)
                return true;
            if($this->newState==null)
                return true;

            if(!isset($this->definition["STATES"]["STATES"][$this->newStateLabel]) ||
                !isset($this->definition["STATES"]["STATES"][$this->newStateLabel]["FIELDS"]) ||
                !isset($this->definition["STATES"]["STATES"][$this->newStateLabel]["FIELDS"]["REQUIRED"]))
                return true;
            $st=& $this->definition["STATES"]["STATES"][$this->newStateLabel]["FIELDS"]["REQUIRED"];
            foreach($st as $cF)
            {
                $field=$this->model->__getField($cF);
                if(!$field->is_set())
                {
                    $e=new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD,array("field"=>$cF));
                    $field->__setErrored($e);
                    throw $e;
                }
            }
        }
        function isRequired($fieldName)
        {
            if($this->hasState==false)
                return $this->model->__getField($fieldName)->isDefinedAsRequired();

            return $this->isRequiredForState($fieldName,$this->getCurrentStateLabel());
        }
        function isEditable($fieldName)
        {
            if($fieldName[0]!==$this->pathPrefix)
                $fieldName=$this->pathPrefix.$fieldName;
            if($this->hasState==false)
                return true;
            if($fieldName==$this->stateField)
                return true;

            return $this->isEditableInState($fieldName,$this->getCurrentStateLabel());
        }
        function isFixed($fieldName)
        {
            if($this->hasState==false)
                return false;
            return $this->isFixedInState($fieldName,$this->getNewState());
        }
        function isRequiredForState($fieldName,$stateName)
        {
            if(!$this->hasState)
                return $this->model->__getField($fieldName)->isRequired();

            if($this->existsFieldInStateDefinition($stateName,$fieldName,"REQUIRED"))
                return true;
            return $this->model->__getField($fieldName)->isDefinedAsRequired();

        }
        function isEditableInState($fieldName,$stateName)
        {

            if(!$this->hasState)
                return true;
            if($fieldName[0]!==$this->pathPrefix)
                $fieldName=$this->pathPrefix.$fieldName;
            if($fieldName==$this->stateField)
                return true;
            $res=$this->existsFieldInStateDefinition($stateName,$fieldName,"EDITABLE",true);
            return $res;
        }
        function isFixedInState($fieldName,$stateName)
        {
            if(!$this->hasState)
                return true;
            return $this->existsFieldInStateDefinition($stateName,$fieldName,"FIXED");
        }

        // Dependiendo de si el $group existe o no, querriamos que la funcion devolviera una cosa u otra.
        // Por ejemplo, si preguntamos si un cierto campo es REQUIRED dentro de un estado, y ese estado no define
        // REQUIRED, queremos que devuelva false.
        // Pero si en vez de REQUIRED preguntamos por EDITABLE, queremos de devuelva true.
        // FieldName puede ser un path.
        function existsFieldInStateDefinition($stateName,$fieldName,$group,$defResult=false)
        {
            if($fieldName[0]!==$this->pathPrefix)
                $fieldName=$this->pathPrefix.$fieldName;
            if(!isset($this->definition["STATES"]["STATES"][$stateName]))
                throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_UNKNOWN_STATE,array("state"=>$stateName));
            $st= $this->definition["STATES"]["STATES"][$stateName];
            if(!isset($st["FIELDS"]))
                return $defResult;
            if(!isset($st["FIELDS"][$group]))
                return false;
            $g=$st["FIELDS"][$group];
            for($k=0;$k<count($g);$k++)
            {
                $path=$g[$k];
                if($path=="*")
                    return true;
                if($path[0]!==$this->pathPrefix)
                    $path=$this->pathPrefix.$path;
                if($path==$fieldName)
                    return true;
                if(preg_match("~".$path."/~",$fieldName))
                    return true;
            }
            return false;
        }

    function isChangingState()
    {
        return $this->changingState;
    }
    function changeState($next)
    {
        // Si no habia un estado previo, o sea, el estado anterior era nulo, este campo estaba en un
        // objeto nulo, o ha sido reseteado.
        // El asunto es que no debe llamar a ningun callback.
        // Conseguir esto es gracias a que, aunque los campos de estado tienen un valor por defecto,
        // 1) Esos valores por defecto se inicializan usando apply, por lo que el tipo de dato no
        // llama a changeState, por lo que oldState sigue siendo null.

        if($this->oldState==null)
            $this->oldState= $this->model->__getField($this->stateField)->getValue();
        if($this->oldState===null) {

            $this->nextState=$next;
            return;
        }

        $orig=$next;
        if(is_string($next))
        {
            try {
                $next = $this->getStateId($next);
            }catch(\lib\model\types\BaseTypeException $e)
            {
                throw new BaseTypedException(BaseTypedException::ERR_UNKNOWN_STATE,array("state"=>$orig));
            }
        }
        if($next===false)
            throw new BaseTypedException(BaseTypedException::ERR_UNKNOWN_STATE,array("state"=>$orig));
        $this->changingState=true;
        if($next===$this->newState)
            return;
        // por ahora, hacemos esto: Si ya hay un newState, rechanzamos el nuevo cambio.
        if($this->newState)
            throw new BaseTypedException(BaseTypedException::ERR_DOUBLESTATECHANGE,array("current"=>$this->getCurrentState(),"new"=>$next,"middle"=>$this->newState));

        $this->setOldState($this->getOldState());

        if($this->isFinalState($this->oldStateLabel))
        {
            $this->changingState=false;
            $this->newState=null;
            throw new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_FINAL_STATE,array("current"=>$this->oldStateLabel,"new"=>$this->newStateLabel));
        }

        $actualState = $this->oldState;
        if($this->oldState===$next && $this->oldState!==null) {
            $this->newState=null;
            $this->changingState=false;
            return true;
        }
        $this->setNewState($next);

        $newId=$this->newState;
        if(!isset($this->definition["STATES"]["STATES"][$this->newStateLabel]))
        {
            $this->model->__getField($this->stateField)->set($newId);
            $this->newState=null;
            $this->changingState=false;
            return;
        }
        $definition=$this->definition["STATES"]["STATES"][$this->newStateLabel];
        // Se ve si el estado actual es final o no.

        if(isset($definition["FIELDS"]["REQUIRED"]))
        {
            $f=$definition["FIELDS"]["REQUIRED"];
            for($n=0;$n<count($f);$n++)
            {
                $field=$this->model->{"*".$f[$n]};
                if(!$field->hasValue()) {
                    $this->changingState=false;
                    $this->newState=null;
                    $e=new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD, array("field" => $f[$n]));
                    $field->__setErrored($e);
                    throw $e;

                }
            }
        }
        if(isset($definition["ALLOW_FROM"]))
        {
            if(array_search($this->oldStateLabel,$definition["ALLOW_FROM"])===false)
            {
                if(isset($definition["REJECT_TO"][$this->newStateLabel]))
                {
                    $this->executeCallbacks("REJECT_TO",$this->newStateLabel,$this->oldStateLabel);
                    $this->changingState=false;
                    $this->newState=null;
                    throw new BaseTypedException(BaseTypedException::ERR_REJECTED_CHANGE_STATE,array("current"=>$actualState,"new"=>$next));
                }
                else
                {
                    $this->changingState=false;
                    $this->newState=null;
                    throw new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_STATE_TO,array("current"=>$actualState,"new"=>$next));
                }

            }
        }
        try
        {
            $result=$this->executeCallbacks("TESTS",$this->newStateLabel,$this->oldStateLabel);
        }catch(\Exception $e)
        {
            $this->changingState=false;
            $this->newState=null;
            throw $e;
        }
        if(!$result)
        {
            $this->changingState=false;
            $this->newState=null;
            throw new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_STATE,array("current"=>$actualState,"new"=>$next));
        }
        $this->executeCallbacks("ON_LEAVE",$this->oldStateLabel,$this->newStateLabel);
        $this->executeCallbacks("ON_ENTER",$this->newStateLabel,$this->oldStateLabel);
        $this->changingState=false;
        $this->oldState=$this->newState;
        $this->newState=null;
    }

    function executeCallbacks($type,$state,$refState)
    {
        if(!isset($this->definition["STATES"]["LISTENER_TAGS"]))
            return true;
        if(!isset($this->definition["STATES"]['STATES'][$state]["LISTENERS"][$type]))
            return true;

        $cbConnection=new \lib\model\states\CallbackCollection($this->definition["STATES"]["LISTENER_TAGS"]);

        $def=$this->definition["STATES"]['STATES'][$state]["LISTENERS"][$type];
        $callbacks=$this->getStatedDefinition($def,$refState);

        //Hay que buscar quien es el modelo destino.
        $dest=$this->model;
        while($dest!==null && !is_a($dest,'\lib\model\BaseTypedObject'))
            $dest=$dest->getParent();
        if($dest===null)
            throw new BaseTypedException(BaseTypedException::ERR_NO_STATE_CONTROLLER,array("state"=>$state,"callbackType"=>$type));

        $result=$cbConnection->apply(
            $callbacks,
            $dest,
            $type=="TESTS"?"TEST":"LINEAR",
            [$this->model]
            );
        return $result;
    }

    function getStateTransitions($stateId)
    {
        if (!$this->hasState)
            return null;
        $curStateDef=$this->definition["STATES"]["STATES"][$this->getStateLabel($stateId)];

        if(isset( $curStateDef["ALLOW_FROM"])) {
            $allowed=$curStateDef["ALLOW_FROM"];
            $result=[];
            foreach($allowed as $k=>$v)
            {
                $result[]=$this->getStateId($v);
            }
            return $result;
        }
        return null;
    }

    // Metodo para comprobar si el objeto puede pasar del estado A al B.
    function canTranslateTo($newStateId)
    {
        $currentState=$this->getCurrentState();
        $transitions=$this->getStateTransitions($newStateId);
        if($transitions===null)
            return true;

        return in_array($currentState,$transitions);
    }

    function getStatedDefinition($statedDef,$stateToCheck)
    {
        if(isset($statedDef["STATES"]))
        {
            if(isset($statedDef["STATES"][$stateToCheck]))
                return $statedDef["STATES"][$stateToCheck];
            if(isset($statedDef["STATES"]["*"]))
                return $statedDef["STATES"]["*"];
            return array();
        }
        return $statedDef;
    }
    function getRequiredFields($state)
    {
        if(isset($this->definition["STATES"]["STATES"][$state]["FIELDS"]["REQUIRED"]))
            return $this->definition["STATES"]["STATES"][$state]["FIELDS"]["REQUIRED"];
        return [];
    }

    function getRequiredPermissions()
    {
        $currentState=$this->getCurrentState();
        if(isset( $this->definition["STATES"]["STATES"][$this->getStateLabel($currentState)]["PERMISSIONS"]))
            return $this->definition["STATES"]["STATES"][$this->getStateLabel($currentState)]["PERMISSIONS"];
        return null;

    }

}

?>
