<?php
namespace model\web\Site\WebsiteUrls\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteUrls//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'EditAction',
               'MODEL'=>'Sites\WebsiteUrls',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Site\WebsiteUrls',
                     'ACTION'=>'EditAction',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Edit',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(
                     'id_website'=>array(
                           'PARAMS'=>array(
                                 'LABEL'=>array(
                                       'id_website',

                                       'name'

                                       ),
                                 'VALUE'=>'id_website',
                                 'NULL_RELATION'=>array(0),
                                 'PRE_OPTIONS'=>array('Select an option'),
                                 'DATASOURCE'=>array(
                                       'MODEL'=>'model\web\Site',
                                       'NAME'=>'FullList',
                                       'PARAMS'=>array()
                                       )
                                 )
                           )
                     ),
               'INDEXFIELDS'=>array(
                     'id_websiteUrl'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_websiteUrl',
                           'MODEL'=>'\model\web\Site\WebsiteUrls'
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(EditAction::$definition,$actionResult);
	
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
	 *		 	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function validate( $params, $actionResult, $user)
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