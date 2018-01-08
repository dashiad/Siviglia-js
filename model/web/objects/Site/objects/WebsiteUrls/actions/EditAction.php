<?php
namespace model\web\Site\WebsiteUrls\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteUrls/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'Sites\WebsiteUrls',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'Sites\WebsiteUrls',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array(
                     'id_websiteUrl'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_websiteUrl',
                           'MODEL'=>'\model\web\Site\WebsiteUrls'
                           )
                     ),
               'FIELDS'=>array(
                     'id_website'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_website',
                           'MODEL'=>'Sites\WebsiteUrls',
                           'DATASOURCE'=>array(
                                 'MODEL'=>'model\web\Site',
                                 'NAME'=>'FullList',
                                 'PARAMS'=>array()
                                 )
                           ),
                     'url'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'url',
                           'MODEL'=>'Sites\WebsiteUrls'
                           ),
                     'priority'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'priority',
                           'MODEL'=>'Sites\WebsiteUrls'
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
	 * RETURNS:
	 */
	function __construct( )
	{

			parent::__construct(EditAction::$definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :EditAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: id_website,url,priority	 *
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
	 * DESCRIPTION: Callback executed when this action had success.EditAction
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
	 * DESCRIPTION: Callback executed when this action had an errorEditAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onError( $params, $actionResult, $user)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>