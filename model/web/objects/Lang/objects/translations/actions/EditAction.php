<?php
namespace model\web\Lang\translations\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\action\Action
{
	 static  $_definition=array(
               'OBJECT'=>'Lang\translations',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Lang\translations',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array(
                     'id_translation'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_translation',
                           'MODEL'=>'\model\web\Lang\translations'
                           )
                     ),
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
	 * DESCRIPTION: Constructor for EditAction
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

			parent::__construct(EditAction::$_definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :EditAction
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
	function validateAction ( $actionResult )
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this action had success.EditAction
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
	 * DESCRIPTION: Callback executed when this action had an errorEditAction
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