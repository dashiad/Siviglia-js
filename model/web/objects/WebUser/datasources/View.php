<?php
namespace backoffice\WebUser\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser/datasources/ViewDs.php
  CLASS:ViewDs
*
*
**/

class View
{
	 static  $definition=array(
               'ROLE'=>'view',
               'DATAFORMAT'=>'Table',
               'IS_ADMIN'=>0,
               'PARAMS'=>array(
                 'USER_ID'=>array(
                     'MODEL'=>'\model\web\WebUser',
                     'FIELD'=>'USER_ID'
                 )
               ),
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'MODEL'=>'\model\web\WebUser',
                           'FIELD'=>'LOGIN'
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
               'INCLUDE'=>array(


                     ),
               'PERMISSIONS'=>array('PUBLIC'),
         'SOURCE'=>[
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'WebUser',
                                 'BASE'=>"SELECT * FROM WebUser WHERE [%0%]",
                                 'CONDITIONS'=>array(
                                     array(
                                         'FILTER'=>array(
                                             'F'=>'USER_ID',
                                             'OP'=>'=',
                                             'V'=>'[%USER_ID%]'
                                         ),
                                         'TRIGGER_VAR'=>'USER_ID',
                                         'DISABLE_IF'=>'0',
                                         'FILTERREF'=>'USER_ID'
                                     )
                                 )
                                 )
                           )
                     )
             ]
               );
}
?>
