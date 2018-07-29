<?php
namespace model\web\WebUser\actions;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser/actions/Edit.php
  CLASS:Edit
*
*
**/
class LoginException extends \lib\model\BaseException
{
    const ERR_UNAUTHORIZED=1;
}

class Login extends \lib\action\Action
{
	 static  $definition=array(
               'MODEL'=>'WebUser',
               'ROLE'=>'SEARCH',
               'LABEL'=>'Edit',               
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'LOGIN'
                           ),
                     'PASSWORD'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'PASSWORD'
                           ),                     
                     ),
               'PERMISSIONS'=>array(null)
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for Edit
	 *
	 * PARAMS:
	 *
	 * RETURNS:
	 */
	function __construct( )
	{

		\lib\action\Action::__construct(Login::$definition);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of action :Edit
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 fields: LOGIN,PASSWORD,EMAIL,EXTTYPE,EXTID,NLOGINS,LASTLOGIN,LASTIP,STATE,CREATIONDATE,FAILEDLOGINATTEMPTS,VALIDATED	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
    var $newUser;
	function validate ( $actionResult )
	{        
            try{
                $this->newUser=\model\web\WebUser::login($this->LOGIN,$this->PASSWORD);
   /*             include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
                $permsManager=\Registry::getPermissionsManager();

                if(!$permsManager->canAccess('accessLevelOne','AdminWebSite',$newUser->getId()))
                {
                   // _d("EXCEPCION");
                    throw new \backoffice\WebUserException(\backoffice\WebUserException::ERR_UNAUTHORIZED);
                }
     */
                global $oCurrentUser;                
                $oCurrentUser=$this->newUser;
            }
            catch(\backoffice\WebUserException $e)
            {               
                //_d("ADDING ERROR");
                $actionResult->addGlobalError($e);
            }                        
            /* Insert the validation code here */	
            return $actionResult->isOk();
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this action had success.Edit
	 *
	 * PARAMS:
	 *
	 * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onSuccess( $model, $user)
	{
            if($this->newUser)
            {
                return $this->newUser;
            }
            /* Insert callback code here */	
            return false;
	}



	/**
	 *
	 * NAME:onError
	 *
	 * DESCRIPTION: Callback executed when this action had an errorEdit
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received.Note these parameters are the same received in Validate	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function onError($keys, $params, $actionResult, $user)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>
