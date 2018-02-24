<?php
namespace model\web\Site\WebsiteCountries\actions;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteCountries/actions/AddAction.php
  CLASS:AddAction
*
*
**/

class AddAction extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\web\Site\WebsiteCountries',
               'ROLE'=>'Add',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Site\WebsiteCountries',
                           'PERMISSION'=>'create'
                           )
                     ),
               'IS_ADMIN'=>false,
               'FIELDS'=>array(
                     'id_website'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_website',
                           'MODEL'=>'\model\web\Site\WebsiteCountries',
                           'DATASOURCE'=>array(
                                 'MODEL'=>'Site',
                                 'NAME'=>'FullList',
                                 'PARAMS'=>array()
                                 )
                           ),
                     'id_country'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_country',
                           'MODEL'=>'\model\web\Site\WebsiteCountries',
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
	 * DESCRIPTION: Constructor for AddAction
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

			parent::__construct(AddAction::$definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :AddAction
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
	function validate ( $actionResult )
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this action had success.AddAction
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
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onError ($keys, $params, $actionResult, $user)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>