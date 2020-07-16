<?php
namespace model\ads\SmartConfig\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class Delete extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'Delete',
               'MODEL'=>'\model\ads\SmartConfig',
               'ACTION'=>array(
                     'MODEL'=>'\model\ads\SmartConfig',
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
                     'id_site'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_site',
                           'MODEL'=>'\model\ads\SmartConfig'
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

			parent::__construct(Delete::$definition,$actionResult);

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
