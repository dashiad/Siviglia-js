<?php
namespace model\web\SmartConfig\actions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/SmartConfig/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\web\SmartConfig',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\SmartConfig',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               //'INDEXFIELDS'=>array(''),
               'FIELDS'=>array(
				   'domain'=>array(
					   'REQUIRED'=>1,
					   'FIELD'=>'domain',
					   'MODEL'=>'\model\web\SmartConfig'
				   ),
                     'regex'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'regex',
                           'MODEL'=>'\model\web\SmartConfig'
                           ),
                     'plugin'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'plugin',
                           'MODEL'=>'\model\web\SmartConfig'
                           ),
                     'config'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'config',
                           'MODEL'=>'\model\web\SmartConfig'
                           ),
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

			parent::__construct(Edit::$definition);

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
	 *		 fields: domain, regex, plugin, config
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * $user: User executing this request
	 *
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
	 * DESCRIPTION: Callback executed when this action had success.EditAction
	 *
	 * PARAMS:
	 *
	 * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.
	 *
	 * $user: User executing this request
	 *
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
	 * $keys: Keys received
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * $user: User executing this request
	 *
	 * RETURNS:
	 */
	 function onError( $keys, $params, $actionResult, $user)
	{


	/* Insert callback code here */

	return true;

	}

}