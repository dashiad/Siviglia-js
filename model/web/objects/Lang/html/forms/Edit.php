<?php
namespace model\web\Lang\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'Edit',
               'OBJECT'=>'Lang',
               'ACTION'=>array(
                     'OBJECT'=>'\model\web\Lang',
                     'ACTION'=>'Edit'
                     ),
               'FIELDS'=>array(
                     'name'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'name',
                           'REQUIRED'=>1
                           ),
                     'is_rtl'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'is_rtl',
                           'REQUIRED'=>1
                           ),
                     'language_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'language_code',
                           'REQUIRED'=>1
                           ),
                     'iso_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'iso_code',
                           'REQUIRED'=>1
                           ),
                     'active'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'active',
                           'REQUIRED'=>1
                           ),
                     'date_format_full'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_full',
                           'REQUIRED'=>1
                           ),
                     'date_format_lite'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_lite',
                           'REQUIRED'=>1
                           )
                     ),
               'ROLE'=>'Edit',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array(
                     'id_lang'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_lang',
                           'MODEL'=>'\model\web\Lang'
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

			parent::__construct(Edit::$definition,$actionResult);

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
