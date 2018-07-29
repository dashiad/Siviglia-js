<?php
namespace model\web\Lang\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/actions/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class DeleteAction extends \lib\action\Action
{
	 static  $definition=array(
               'OBJECT'=>'Lang',
               'ROLE'=>'Delete',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Lang',
                           'PERMISSION'=>'delete'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array(
                     'id_lang'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_lang',
                           'MODEL'=>'\model\web\Lang'
                           )
                     )
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for DeleteAction
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

			parent::__construct(DeleteAction::$definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :DeleteAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 	 *
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
	 * DESCRIPTION: Callback executed when this action had success.DeleteAction
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
	 * DESCRIPTION: Callback executed when this action had an errorDeleteAction
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