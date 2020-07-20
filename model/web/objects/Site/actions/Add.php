<?php
namespace model\web\Site\actions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/actions/AddAction.php
  CLASS:AddAction
*
*
**/


class Add extends \lib\action\Action
{
	 static  $_definition=array(
               'MODEL'=>'\model\web\Site',
               'ROLE'=>'Add',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Site',
                           'PERMISSION'=>'create'
                           )
                     ),
               'IS_ADMIN'=>false,
               'FIELDS'=>array(
                     'host'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'host',
                           'MODEL'=>'\model\web\Site'
                           ),
                     'canonical_url'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'canonical_url',
                           'MODEL'=>'\model\web\Site'
                           ),
                     'hasSSL'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'hasSSL',
                           'MODEL'=>'\model\web\Site'
                           ),
                     'namespace'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'namespace',
                           'MODEL'=>'\model\web\Site'
                           ),
                     'websiteName'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'websiteName',
                           'MODEL'=>'\model\web\Site'
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

			parent::__construct(Add::$_definition);

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
	 *		 fields: host,canonical_url,hasSSL,namespace,websiteName
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * $user: User executing this request
	 *
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
	 * DESCRIPTION: Callback executed when this action had success.AddAction
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
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
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
?>
