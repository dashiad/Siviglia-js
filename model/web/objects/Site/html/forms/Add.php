<?php
namespace model\web\Site\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site//html/forms/AddAction.php
  CLASS:AddAction
*
*
**/

class Add extends \lib\output\html\Form
{
	 static  $_definition=array(
               'NAME'=>'Add',
               'MODEL'=>'\model\web\Site',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Site',
                     'ACTION'=>'Add',
                     'INHERIT'=>1
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function __construct( $actionResult=null)
	{

			parent::__construct(Add::$_definition,$actionResult);

	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.AddAction
	 *
	 * PARAMS:
	 *
	 * $actionResult: Action Result object
	 *
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function onError( $actionResult)
	{


	/* Insert callback code here */

	return true;

	}

}
?>
