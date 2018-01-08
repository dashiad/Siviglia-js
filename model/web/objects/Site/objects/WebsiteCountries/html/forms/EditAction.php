<?php
namespace model\web\Site\WebsiteCountries\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteCountries//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'EditAction',
               'MODEL'=>'Sites\WebsiteCountries',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Site\WebsiteCountries',
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
                                       'namespace',
                                       'websiteName'
                                       ),
                                 'VALUE'=>'id_website',
                                 'NULL_RELATION'=>array(0),
                                 'PRE_OPTIONS'=>array('Select an option'),
                                 'DATASOURCE'=>array(
                                       'MODEL'=>'Site',
                                       'NAME'=>'FullList',
                                       'PARAMS'=>array()
                                       )
                                 )
                           ),
                     'id_country'=>array(
                           'PARAMS'=>array(
                                 'LABEL'=>array(
                                       'zip_code_format',
                                       'id_country',
                                       'contains_states',
                                       'call_prefix',
                                       'need_zip_code',
                                       'id_currency',
                                       'need_identification_number',
                                       'active',
                                       'iso_code',
                                       'display_tax_label',
                                       'id_zone'
                                       ),
                                 'VALUE'=>'id_country',
                                 'NULL_RELATION'=>array(0),
                                 'PRE_OPTIONS'=>array('Select an option'),
                                 'DATASOURCE'=>array(
                                       'MODEL'=>'ps_customer\ps_country',
                                       'NAME'=>'FullList',
                                       'PARAMS'=>array()
                                       )
                                 )
                           )
                     ),
               'INDEXFIELDS'=>array(
                     'id_websiteCountry'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_websiteCountry',
                           'MODEL'=>'\model\web\Site\WebsiteCountries'
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