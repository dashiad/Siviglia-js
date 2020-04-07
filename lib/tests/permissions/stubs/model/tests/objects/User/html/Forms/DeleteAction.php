<?php
namespace model\tests\User\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class DeleteAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'DeleteAction',
               'MODEL'=>'\model\tests\User',
               'ACTION'=>array(
                     'MODEL'=>'\model\tests\User',
                     'ACTION'=>'DeleteAction',
				   'INHERIT'=>1
                     ),
               'FIELDS'=>array(),
               'ROLE'=>'Delete',
               'INPUTS'=>array(),
               'NOFORM'=>1,
               'INDEXFIELDS'=>array("id")
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for DeleteAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(DeleteAction::$definition,$actionResult);

	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :DeleteAction
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
	 * DESCRIPTION: Callback executed when this form had success.DeleteAction
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
	 * DESCRIPTION: Callback executed when this action had an errorDeleteAction
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
