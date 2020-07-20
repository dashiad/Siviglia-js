<?php
namespace model\reflection\Model\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class Delete extends \lib\output\html\Form
{
	 static  $_definition=array(
               'NAME'=>'Delete',
               'MODEL'=>'\model\reflection\Model',
               'ACTION'=>array(
                     'MODEL'=>'\model\reflection\Model',
                     'ACTION'=>'Delete',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Delete',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array(
                     'modelName'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'modelName',
                           'MODEL'=>'\model\reflection\Model'
                           )
                     ),
               'NOFORM'=>1
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for DeleteAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function __construct( $actionResult=null)
	{

			parent::__construct(Delete::$_definition,$actionResult);

	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.DeleteAction
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
	 * DESCRIPTION: Callback executed when this action had an errorDeleteAction
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
