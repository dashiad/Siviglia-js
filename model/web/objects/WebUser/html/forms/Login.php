<?php
namespace model\web\WebUser\html\forms;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser//html/forms/AdminLogin.php
  CLASS:AdminLogin
*
*
**/

class Login extends \lib\output\html\Form
{
	 static $definition=array(
               'NAME'=>'Login',
               'MODEL'=>'\model\web\WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\model\web\WebUser',
                     'ACTION'=>'Login'
                     ),
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
                           )
                     ),
               'ROLE'=>'SEARCH',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(
                     'LOGIN'=>array(
                           'TYPE'=>'/types/inputs/Login',
                           'PARAMS'=>array()
                           ),
                     'PASSWORD'=>array(
                           'TYPE'=>'/types/inputs/Password',
                           'PARAMS'=>array()
                           )
                     ),
               "INDEXFIELDS"=>array()
               );
     /**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for 
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(Login::$definition,$actionResult);
	
	}

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
	 *		 fields: LOGIN,PASSWORD	 *
	 * $actionResult:\lib\controller\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function validate()
	{
        $actionResult=new \lib\action\ActionResult();

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
	function onSuccess()
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