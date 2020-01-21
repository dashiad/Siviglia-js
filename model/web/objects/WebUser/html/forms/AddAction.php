<?php
namespace model\web\WebUser\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser//html/forms/AddAction.php
  CLASS:AddAction
*
*
**/

class AddAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'AddAction',
               'MODEL'=>'\model\web\WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\WebUser',
                     'ACTION'=>'AddAction'
                     ),
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'LOGIN',
                           'REQUIRED'=>1
                           ),
                     'PASSWORD'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'PASSWORD',
                           'REQUIRED'=>1
                           ),
                     'USER_ID'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'USER_ID',
                           'REQUIRED'=>1
                           ),
                     'EMAIL'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'EMAIL',
                           'REQUIRED'=>1
                           )
                     ),
               'ROLE'=>'Add',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array()
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for AddAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(AddAction::$definition,$actionResult);

	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :AddAction
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
	function validate ( $actionResult )
	{


	/* Insert the validation code here */

			return $actionResult->isOk();

	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.AddAction
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
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
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
