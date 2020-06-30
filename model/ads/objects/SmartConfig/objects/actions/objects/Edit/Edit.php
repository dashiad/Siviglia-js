<?php
namespace model\ads\SmartConfig\actions;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/actions/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\ads\SmartConfiog',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
//                     array(
//                           'MODEL'=>'\model\ads\SmartConfig',
//                           'PERMISSION'=>'edit'
//                           )
                     ),
               'IS_ADMIN'=>false,
               'INDEXFIELDS'=>array("domain"),
               'FIELDS'=>array(
/*                   'id_page'=>array(
                       'REQUIRED'=>1,
                       'FIELD'=>'id_page',
                       'MODEL'=>'\model\web\Page'
                   ),
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
                                 'LABEL'=>'websiteName',
                                 'PARAMS'=>array()
                                 )
                           ),
                     'name'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'name',
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
                           'FIELD'=>'tags',
                           'MODEL'=>'\model\web\Page'
                           ),
                     'description'=>array(
                           'FIELD'=>'description',
                           'MODEL'=>'\model\web\Page'
                           )
		   )*/
	       )               );


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
