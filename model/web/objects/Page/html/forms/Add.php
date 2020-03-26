<?php
namespace model\web\Page\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page//html/forms/AddAction.php
  CLASS:AddAction
*
*
**/

class Add extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'Add',
               'MODEL'=>'\model\web\Page',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\Page',
                     'ACTION'=>'Add',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Add',
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
                     ),
               'INDEXFIELDS'=>array()
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for AddAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
	 *
	 * RETURNS:
	 */
	 function __construct( $actionResult=null)
	{

			parent::__construct(Add::$definition,$actionResult);

	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.AddAction
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
	 * DESCRIPTION: Callback executed when this action had an errorAddAction
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
