<?php
namespace model\web\Site\WebsiteCountries\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteCountries/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'Sites\WebsiteCountries',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'Sites\WebsiteCountries',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array(
                     'id_websiteCountry'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_websiteCountry',
                           'MODEL'=>'\model\web\Site\WebsiteCountries'
                           )
                     ),
               'FIELDS'=>array(
                     'id_website'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_website',
                           'MODEL'=>'Sites\WebsiteCountries',
                           'DATASOURCE'=>array(
                                 'MODEL'=>'Site',
                                 'NAME'=>'FullList',
                                 'PARAMS'=>array()
                                 )
                           ),
                     'id_country'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_country',
                           'MODEL'=>'Sites\WebsiteCountries',
                           'DATASOURCE'=>array(
                                 'MODEL'=>'ps_customer\ps_country',
                                 'NAME'=>'FullList',
                                 'PARAMS'=>array()
                                 )
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
	 *		 fields: id_website,id_country	 *
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