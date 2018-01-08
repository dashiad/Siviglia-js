<?php
namespace model\web\Page\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class DeleteAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'DeleteAction',
               'MODEL'=>'\model\Page',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Page',
                     'ACTION'=>'DeleteAction',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Delete',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array(
                     'id_page'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_page',
                           'MODEL'=>'\model\web\Page'
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

			parent::__construct(DeleteAction::$definition,$actionResult);
	
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