<?php
namespace web\objects\WebUser\html\forms;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser//html/forms/Delete_Batch.php
  CLASS:Delete_Batch
*
*
**/

class Delete_Batch extends \lib\output\html\Form
{
	 var $definition=array(
               'NAME'=>'Delete_Batch',
               'MODEL'=>'WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\web\objects\WebUser',
                     'ACTION'=>'Delete_Batch'
                     ),
               'FIELDS'=>array(),
               'ROLE'=>'BATCH',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELD'=>array(
                     'USER_ID'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'USER_ID'
                           )
                     ),
               'NOFORM'=>1
               );


	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function validate( $params, $actionResult, $user)
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.
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
	 * DESCRIPTION: Callback executed when this action had an error
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