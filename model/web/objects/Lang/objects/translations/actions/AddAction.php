<?php
namespace model\web\Lang\translations\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations/actions/AddAction.php
  CLASS:AddAction
*
*
**/

class AddAction extends \lib\action\Action
{
	 static  $definition=array(
               'OBJECT'=>'Lang\translations',
               'ROLE'=>'Add',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Lang\translations',
                           'PERMISSION'=>'create'
                           )
                     ),
               'IS_ADMIN'=>false,
               'FIELDS'=>array(
                     'value'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'value',
                           'MODEL'=>'\model\web\Lang\translations'
                           ),
                     'lang'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'lang',
                           'MODEL'=>'\model\web\Lang\translations'
                           ),
                     'id_string'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_string',
                           'MODEL'=>'\model\web\Lang\translations'
                           )
                     )
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for AddAction
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

			parent::__construct(AddAction::$definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :AddAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: value,lang,id_string	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function validate ( $actionResult )
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this action had success.AddAction
	 *
	 * PARAMS:
	 *
	 * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onSuccess( $model, $user)
	{


	/* Insert callback code here */
	
	return true;
	
	}



	/**
	 *
	 * NAME:onError
	 *
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onError ($keys, $params, $actionResult, $user)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>