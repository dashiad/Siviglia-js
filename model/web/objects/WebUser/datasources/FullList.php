<?php
namespace backoffice\WebUser\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser/datasources/FullList.php
  CLASS:FullList
*
*
**/

class FullList
{
	 static  $definition=array(
               'ROLE'=>'list',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                     'LOGIN'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'LOGIN',
                           'TRIGGER_VAR'=>'LOGIN'
                           ),
                     'dynLOGIN'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'LOGIN',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'PASSWORD'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'PASSWORD',
                           'TRIGGER_VAR'=>'PASSWORD'
                           ),
                     'dynPASSWORD'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'PASSWORD',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'USER_ID'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'USER_ID',
                           'TRIGGER_VAR'=>'USER_ID'
                           ),
                     'EMAIL'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'EMAIL',
                           'TRIGGER_VAR'=>'EMAIL'
                           ),
                     'dynEMAIL'=>array(
                           'MODEL'=>'WebUser',
                           'FIELD'=>'EMAIL',
                           'PARAMTYPE'=>'DYNAMIC'
                           )
                     ),
               'IS_ADMIN'=>0,
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'LOGIN'
                           ),
                     'PASSWORD'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'PASSWORD'
                           ),
                     'USER_ID'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'USER_ID'
                           ),
                     'EMAIL'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'EMAIL'
                           )
                     ),
               'PERMISSIONS'=>array('PUBLIC'),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'WebUser',
                                 'BASE'=>array(
                                       'LOGIN',
                                       'PASSWORD',
                                       'USER_ID',
                                       'EMAIL'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'LOGIN',
                                                   'OP'=>'=',
                                                   'V'=>'[%LOGIN%]'
                                                   ),
                                             'TRIGGER_VAR'=>'LOGIN',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'LOGIN'
                                             ),
                                       array(
                                             'FILTER'=>'LOGIN LIKE CONCAT([%dynLOGIN%],\'%\')',
                                             'TRIGGER_VAR'=>'dynLOGIN',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dynLOGIN'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'PASSWORD',
                                                   'OP'=>'=',
                                                   'V'=>'[%PASSWORD%]'
                                                   ),
                                             'TRIGGER_VAR'=>'PASSWORD',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'PASSWORD'
                                             ),
                                       array(
                                             'FILTER'=>'PASSWORD LIKE CONCAT([%dynPASSWORD%],\'%\')',
                                             'TRIGGER_VAR'=>'dynPASSWORD',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dynPASSWORD'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'USER_ID',
                                                   'OP'=>'=',
                                                   'V'=>'[%USER_ID%]'
                                                   ),
                                             'TRIGGER_VAR'=>'USER_ID',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'USER_ID'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'EMAIL',
                                                   'OP'=>'=',
                                                   'V'=>'[%EMAIL%]'
                                                   ),
                                             'TRIGGER_VAR'=>'EMAIL',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'EMAIL'
                                             ),
                                       array(
                                             'FILTER'=>'EMAIL LIKE CONCAT([%dynEMAIL%],\'%\')',
                                             'TRIGGER_VAR'=>'dynEMAIL',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dynEMAIL'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>