<?php
namespace model\web\WebUser\html\forms;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser//html/forms/AddAdministrator.php
  CLASS:AddAdministrator
*
*
**/

class AddAdministrator extends \lib\output\html\Form
{
	 var $definition=array(
               'NAME'=>'AddAdministrator',
               'MODEL'=>'\model\web\WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\objects\WebUser',
                     'ACTION'=>'AddAdministrator'
                     ),
               'FIELDS'=>array(
                     'USER_ID'=>array(
                           'REQUIRED'=>1,
                           'TYPE'=>'Relationship',
                           'MODEL'=>'\model\web\objects\WebUser',
                           'FIELD'=>'USER_ID'
                           ),
                     'ADMIN_TYPE'=>array(
                         'REQUIRED'=>1,
                         'TYPE'=>'Enum',
                         'VALUES'=>array("FullAdmin","WebAdmin","AppAdmin")
                     )),
               'ROLE'=>'SEARCH',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(
                     'USER_ID'=>array(
                           'TYPE'=>'/types/inputs/Selector',
                           'PARAMS'=>array()
                           )
                     ),
               "INDEXFIELDS"=>array()
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
	 *		 fields: USER_ID	 *
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
