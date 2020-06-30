<?php
namespace model\web\Page\actions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page/actions/AddAction.php
  CLASS:AddAction
*
*
**/

class Add extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\web\Page',
               'ROLE'=>'Add',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\web\Page',
                           'PERMISSION'=>'create'
                           )
                     ),
               'IS_ADMIN'=>false,
               'FIELDS'=>array(
                     'tag'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'tag',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'id_site'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_site',
                           'MODEL'=>'\model\web\Page',
                           'DATASOURCE'=>array(
                                 'MODEL'=>'\model\web\Site',
                                 'NAME'=>'FullList',
                                 'PARAMS'=>array()
                                 )
                           ),
                     'name'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'name',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'date_add'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'date_add',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'date_modified'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'date_modified',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'id_type'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_type',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'isPrivate'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'isPrivate',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'path'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'path',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'title'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'title',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'tags'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'tags',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'description'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'description',
                           'MODEL'=>'\model\web\Page'
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

			parent::__construct(Add::$definition);

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
	 *		 fields: tag,id_site,name,date_add,date_modified,id_type,isPrivate,path,title,tags,description
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
