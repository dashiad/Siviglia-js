<?php
namespace model\reflection\Model\actions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page/actions/AddAction.php
  CLASS:AddAction
*
*
**/

class Edit extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\reflection\Model',
               'ROLE'=>'Edit',
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'\model\reflection\MetaDefinition',
                           'PERMISSION'=>'create'
                           )
                     ),
		 		'INDEXFIELDS'=>array("modelName"),
               'IS_ADMIN'=>false,
               'FIELDS'=>array(
				   'modelName'=>array(
					   "LABEL"=>"Destination file",
					   "TYPE"=>"String",
					   "REQUIRED"=>true
				   ),
                     'definition'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'definition',
                           'MODEL'=>'\model\reflection\MetaDefinition'
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
		file_put_contents(__DIR__."/".$this->file,json_encode($this->definition));

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
