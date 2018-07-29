<?php
namespace model\web\Lang\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\action\Action
{
	 static  $definition=array(
               'OBJECT'=>'Lang',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Lang',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array(
                     'id_lang'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_lang',
                           'MODEL'=>'\model\web\Lang'
                           )
                     ),
               'FIELDS'=>array(
                     'name'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'name',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'is_rtl'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'is_rtl',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'language_code'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'language_code',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'iso_code'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'iso_code',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'active'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'active',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'date_format_full'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'date_format_full',
                           'MODEL'=>'\model\web\Lang'
                           ),
                     'date_format_lite'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'date_format_lite',
                           'MODEL'=>'\model\web\Lang'
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

			parent::__construct(EditAction::$definition);
	
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
	 *		 fields: name,is_rtl,language_code,iso_code,active,date_format_full,date_format_lite	 *
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