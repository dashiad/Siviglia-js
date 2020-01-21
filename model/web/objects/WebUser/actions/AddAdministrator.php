<?php
namespace model\web\WebUseractions;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser/actions/AddAdministrator.php
  CLASS:AddAdministrator
*
*
**/

class AddAdministrator extends \lib\controller\Action
{
	 static  $definition=array(
               'MODEL'=>'\model\web\WebUser',
               'ROLE'=>'SEARCH',
               'LABEL'=>'Add Administrator',
               "INDEXFIELDS"=>null,
               'FIELDS'=>array(
                     'USER_ID'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\model\web\objects\WebUser',
                           'FIELD'=>'USER_ID'
                           ),
                     'ADMIN_TYPE'=>array(
                         'REQUIRED'=>1,
                         'TYPE'=>'Enum',
                         'VALUES'=>array("FullAdmin","WebAdmin","AppAdmin")
                     )
                     ),
               'IS_ADMIN'=>1,
               'PERMISSIONS'=>array(null)
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for AddAdministrator
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

			\lib\controller\Action::__construct(AddAdministrator::$definition);

	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :AddAdministrator
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
	 * DESCRIPTION: Callback executed when this action had success.AddAdministrator
	 *
	 * PARAMS:
	 *
	 * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onSuccess( $model, $user)
	{

            include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
            $oManager=new \AclManager();
            $label=$model->{"*ADMIN_TYPE"}->getLabel();
            $oManager->addUserToGroup($label,$model->USER_ID->USER_ID);
            /* Insert callback code here */

	return true;

	}



	/**
	 *
	 * NAME:onError
	 *
	 * DESCRIPTION: Callback executed when this action had an errorAddAdministrator
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onError ($keys, $params, $actionResult, $user)
	{


	/* Insert callback code here */

	return true;

	}

}
?>
