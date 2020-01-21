<?php
namespace model\web\WebUser\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser/datasources/ViewDs.php
  CLASS:ViewDs
*
*
**/

class ViewDs
{
	 static  $definition=array(
               'ROLE'=>'view',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(),
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
               'INCLUDE'=>array(

                     ),
               'PERMISSIONS'=>array('PUBLIC'),
               'SOURCE'=>[
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
                                 'CONDITIONS'=>null
                                 )
                           )
                     )
                   ]
               );
}
?>
