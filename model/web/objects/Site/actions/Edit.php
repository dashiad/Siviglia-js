<?php
namespace model\web\Site\actions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\web\Site',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Site',
                           'PERMISSION'=>'edit'
                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array('id_site'),
               'FIELDS'=>array(
				   'id_site'=>array(
					   'REQUIRED'=>1,
					   'FIELD'=>'id_site',
					   'MODEL'=>'\model\web\Site'
				   ),
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
