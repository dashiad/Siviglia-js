<?php
namespace model\web\Page\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'EditAction',
               'MODEL'=>'\model\web\Page',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Page',
                     'ACTION'=>'EditAction',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Edit',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(
                     'id_site'=>array(
                           'PARAMS'=>array(
                                 'LABEL'=>array(
                                       'id_site',
                                       'host',
                                       'canonical_url',
                                       'hasSSL',
                                       'namespace',
                                       'websiteName'
                                       ),
                                 'VALUE'=>'id_site',
                                 'NULL_RELATION'=>array(-1),
                                 'PRE_OPTIONS'=>array(null),
                                 'DATASOURCE'=>array(
                                       'MODEL'=>'\model\web\Site',
                                       'NAME'=>'FullList',
                                       'PARAMS'=>array()
                                       )
                                 )
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

			parent::__construct(EditAction::$definition,$actionResult);

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
