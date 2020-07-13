<?php
namespace model\tests\User\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\output\html\Form
{
	 static  $_definition=array(
               'NAME'=>'EditAction',
               'MODEL'=>'\model\tests\User',
               'ACTION'=>array(
                     'MODEL'=>'\model\tests\User',
                     'ACTION'=>'EditAction',
				     'INHERIT'=>1
                     ),
               'ROLE'=>'Edit',
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array("id")
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for EditAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(EditAction::$_definition,$actionResult);

	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :EditAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: LOGIN,PASSWORD,USER_ID,EMAIL	 *
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
	 * DESCRIPTION: Callback executed when this form had success.EditAction
	 *
	 * PARAMS:
	 *
	 * $actionResult: Action Result object	 *
	 * RETURNS:
	 */
	function onSuccess( $actionResult)
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function onError( $actionResult)
	{


	/* Insert callback code here */

	return true;

	}

}
?>
