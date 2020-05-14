<?php
namespace model\web\Site\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/FullList.php
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
                     'id_site'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'id_site',
                           'TRIGGER_VAR'=>'id_site'
                           ),
                     'host'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'host',
                           'TRIGGER_VAR'=>'host'
                           ),
                     'canonical_url'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'canonical_url',
                           'TRIGGER_VAR'=>'canonical_url'
                           ),
                     'hasSSL'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'hasSSL',
                           'TRIGGER_VAR'=>'hasSSL'
                           ),
                     'namespace'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'namespace',
                           'TRIGGER_VAR'=>'namespace'
                           ),
                     'websiteName'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'websiteName',
                           'TRIGGER_VAR'=>'websiteName'
                           )
                     ),
               'IS_ADMIN'=>0,
               'FIELDS'=>array(
                     'id_site'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'id_site'
                           ),
                     'host'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'host'
                           ),
                     'canonical_url'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'canonical_url'
                           ),
                     'hasSSL'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'hasSSL'
                           ),
                     'namespace'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'namespace'
                           ),
                     'websiteName'=>array(
                           'MODEL'=>'\model\web\Site',
                           'FIELD'=>'websiteName'
                           )
                     ),
               'PERMISSIONS'=>array(["TYPE"=>"Public"]),
               'SOURCE'=>[
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'Websites',
                                 'BASE'=>array(
                                       'id_site',
                                       'host',
                                       'canonical_url',
                                       'hasSSL',
                                       'namespace',
                                       'websiteName'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'id_site',
                                                   'OP'=>'=',
                                                   'V'=>'[%id_site%]'
                                                   ),
                                             'TRIGGER_VAR'=>'id_site',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'id_site'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'host',
                                                   'OP'=>'=',
                                                   'V'=>'[%host%]'
                                                   ),
                                             'TRIGGER_VAR'=>'host',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'host'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'canonical_url',
                                                   'OP'=>'=',
                                                   'V'=>'[%canonical_url%]'
                                                   ),
                                             'TRIGGER_VAR'=>'canonical_url',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'canonical_url'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'hasSSL',
                                                   'OP'=>'=',
                                                   'V'=>'[%hasSSL%]'
                                                   ),
                                             'TRIGGER_VAR'=>'hasSSL',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'hasSSL'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'namespace',
                                                   'OP'=>'=',
                                                   'V'=>'[%namespace%]'
                                                   ),
                                             'TRIGGER_VAR'=>'namespace',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'namespace'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'websiteName',
                                                   'OP'=>'=',
                                                   'V'=>'[%websiteName%]'
                                                   ),
                                             'TRIGGER_VAR'=>'websiteName',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'websiteName'
                                             )
                                       )
                                 )
                           )
                     )
                   ]
               );
}
