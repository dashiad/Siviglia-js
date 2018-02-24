<?php
namespace web\objects\WebUser\html\forms;
/**
 FILENAME:c:/xampp/htdocs/framework//web/objects/WebUser//html/forms/SetEditorAction.php
  CLASS:SetEditorAction
*
*
**/

class SetEditorAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'SetEditorAction',
               'MODEL'=>'WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\web\objects\WebUser',
                     'ACTION'=>'SetEditorAction'
                     ),
               'FIELDS'=>array(
                     'SectionEditors'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'SectionEditors',
                           'REQUIRED'=>1,
                           'TYPE'=>'DataSet',
                           'TARGET_RELATION'=>'SectionEditors'
                           )
                     ),
               'ROLE'=>'SetRelation',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(
                     'SectionEditors'=>array(
                           'PARAMS'=>array(
                                 'LABEL'=>array(
                                       'name',
                                       'state',
                                       'isHome',
                                       'caching',
                                       'cacheTime',
                                       'path',
                                       'creationDate'
                                       ),
                                 'VALUE'=>array('id_seccion')
                                 )
                           )
                     ),
               'TARGET_RELATION'=>'SectionEditors',
               'INDEXFIELDS'=>array(
                     'USER_ID'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'USER_ID',
                           'MODEL'=>'\web\objects\WebUser'
                           )
                     )
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for SetEditorAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(SetEditorAction::$definition,$actionResult);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :SetEditorAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: SectionEditors	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
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
	 * DESCRIPTION: Callback executed when this form had success.SetEditorAction
	 *
	 * PARAMS:
	 *
	 * $actionResult: Action Result object	 *
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
	 * DESCRIPTION: Callback executed when this action had an errorSetEditorAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function onError( $actionResult)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>