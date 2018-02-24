<?php
namespace web\objects\WebUser\html\forms;
/**
 FILENAME:c:/xampp/htdocs/rentasignal2//web/objects/WebUser//html/forms/Create.php
  CLASS:Create
*
*
**/

class Create extends \lib\output\html\Form
{
	 var $definition=array(
               'NAME'=>'Create',
               'MODEL'=>'WebUser',
               'ACTION'=>array(
                     'MODEL'=>'\web\objects\WebUser',
                     'ACTION'=>'Create'
                     ),
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'LOGIN'
                           ),
                     'PASSWORD'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'PASSWORD'
                           ),
                     'EMAIL'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'EMAIL'
                           ),
                     'EXTTYPE'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'EXTTYPE'
                           ),
                     'EXTID'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'EXTID'
                           ),
                     'NLOGINS'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'NLOGINS'
                           ),
                     'LASTLOGIN'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'LASTLOGIN'
                           ),
                     'LASTIP'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'LASTIP'
                           ),
                     'STATE'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'STATE'
                           ),
                     'CREATIONDATE'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'CREATIONDATE'
                           ),
                     'FAILEDLOGINATTEMPTS'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'FAILEDLOGINATTEMPTS'
                           ),
                     'VALIDATED'=>array(
                           'REQUIRED'=>1,
                           'MODEL'=>'\web\objects\WebUser',
                           'FIELD'=>'VALIDATED'
                           )
                     ),
               'ROLE'=>'CREATE',
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
                           ),
                     'EMAIL'=>array(
                           'TYPE'=>'/types/inputs/Email',
                           'PARAMS'=>array()
                           ),
                     'EXTTYPE'=>array(
                           'TYPE'=>'/types/inputs/Integer',
                           'PARAMS'=>array()
                           ),
                     'EXTID'=>array(
                           'TYPE'=>'/types/inputs/String',
                           'PARAMS'=>array()
                           ),
                     'NLOGINS'=>array(
                           'TYPE'=>'/types/inputs/Integer',
                           'PARAMS'=>array()
                           ),
                     'LASTLOGIN'=>array(
                           'TYPE'=>'/types/inputs/DateTime',
                           'PARAMS'=>array()
                           ),
                     'LASTIP'=>array(
                           'TYPE'=>'/types/inputs/IP',
                           'PARAMS'=>array()
                           ),
                     'STATE'=>array(
                           'TYPE'=>'/types/inputs/State',
                           'PARAMS'=>array()
                           ),
                     'CREATIONDATE'=>array(
                           'TYPE'=>'/types/inputs/Timestamp',
                           'PARAMS'=>array()
                           ),
                     'FAILEDLOGINATTEMPTS'=>array(
                           'TYPE'=>'/types/inputs/Integer',
                           'PARAMS'=>array()
                           ),
                     'VALIDATED'=>array(
                           'TYPE'=>'/types/inputs/Boolean',
                           'PARAMS'=>array()
                           )
                     ),
               'INDEXFIELD'=>array()
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
	 *		 fields: LOGIN,PASSWORD,EMAIL,EXTTYPE,EXTID,NLOGINS,LASTLOGIN,LASTIP,STATE,CREATIONDATE,FAILEDLOGINATTEMPTS,VALIDATED	 *
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