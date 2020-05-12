<?php
namespace model\reflection\Model\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'Edit',
               'MODEL'=>'\model\reflection\Model',
               'ACTION'=>array(
                     'MODEL'=>'\model\reflection\Model',
                     'ACTION'=>'Edit',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Edit',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array(
                     'modelName'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_site',
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function __construct( $actionResult=null)
	{

			parent::__construct(Edit::$definition,$actionResult);

	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.EditAction
	 *
	 * PARAMS:
	 *
	 * $actionResult: Action Result object
	 *
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function onError( $actionResult)
	{


	/* Insert callback code here */

	return true;

	}

}
?>
