<?php
namespace model\web\Site\WebsiteUrls\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteUrls//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class DeleteAction extends \lib\output\html\Form
{
	 static  $_definition=array(
               'NAME'=>'DeleteAction',
               'MODEL'=>'\model\web\Site\WebsiteUrls',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Site\WebsiteUrls',
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
                     'id_websiteUrl'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_websiteUrl',
                           'MODEL'=>'\model\web\Site\WebsiteUrls'
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(DeleteAction::$_definition,$actionResult);
	
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
	function validateAction ( $actionResult )
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