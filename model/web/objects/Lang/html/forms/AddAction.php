<?php
namespace model\web\Lang\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang//html/forms/AddAction.php
  CLASS:AddAction
*
*
**/

class AddAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'AddAction',
               'OBJECT'=>'Lang',
               'ACTION'=>array(
                     'OBJECT'=>'\model\web\Lang',
                     'ACTION'=>'AddAction'
                     ),
               'FIELDS'=>array(
                     'name'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'name',
                           'REQUIRED'=>1
                           ),
                     'is_rtl'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'is_rtl',
                           'REQUIRED'=>1
                           ),
                     'language_code'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'language_code',
                           'REQUIRED'=>1
                           ),
                     'iso_code'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'iso_code',
                           'REQUIRED'=>1
                           ),
                     'active'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'active',
                           'REQUIRED'=>1
                           ),
                     'date_format_full'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'date_format_full',
                           'REQUIRED'=>1
                           ),
                     'date_format_lite'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'date_format_lite',
                           'REQUIRED'=>1
                           )
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(AddAction::$definition,$actionResult);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :AddAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: name,is_rtl,language_code,iso_code,active,date_format_full,date_format_lite	 *
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
	 * DESCRIPTION: Callback executed when this form had success.AddAction
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
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
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