<?php
namespace model\tests\Post\actions;

class EditAction extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\tests\Post',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\tests\Post',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array('id'),

               	'FIELDS'=>array(
				   'id'=>array(
					   'REQUIRED'=>1,
					   'FIELD'=>'id',
					   'MODEL'=>'\model\tests\Post'
				   ),
					'Name'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'creator_id/Name',
                           'MODEL'=>'\model\tests\Post'
                           )
                     ),
		 		  'simple'=>array(
		 		  	  'TYPE'=>"String",
					  "MAXLENGTH"=>10
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
	 *		 fields: host,canonical_url,hasSSL,namespace,websiteName
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
?>
