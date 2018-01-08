<?php
namespace model\web\Lang\translations\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations/datasources/AdminView.php
  CLASS:AdminView
*
*
**/

class AdminView
{
	 static  $definition=array(
               'ROLE'=>'view',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                     'id_translation'=>array(
                           'MODEL'=>'Lang\translations',
                           'FIELD'=>'id_translation',
                           'TRIGGER_VAR'=>'id_translation'
                           )
                     ),
               'IS_ADMIN'=>1,
               'INDEXFIELDS'=>array(
                     'id_translation'=>array(
                           'MODEL'=>'Lang\translations',
                           'FIELD'=>'id_translation',
                           'REQUIRED'=>'true'
                           )
                     ),
               'FIELDS'=>array(
                     'VALUE'=>array(
                           'TYPE'=>'Text',
                           'MAXLENGTH'=>0
                           ),
                     'lang'=>array(
                           'MODEL'=>'\model\web\Lang\translations',
                           'FIELD'=>'lang'
                           ),
                     'id_string'=>array(
                           'MODEL'=>'\model\web\Lang\translations',
                           'FIELD'=>'id_string'
                           ),
                     'id_translation'=>array(
                           'MODEL'=>'\model\web\Lang\translations',
                           'FIELD'=>'id_translation'
                           )
                     ),
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'Lang\translations',
                           'PERMISSION'=>'adminView'
                           )
                     ),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'translations',
                                 'BASE'=>array(
                                       'value',
                                       'lang',
                                       'id_string',
                                       'id_translation'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'id_translation',
                                                   'OP'=>'=',
                                                   'V'=>'{%id_translation%}'
                                                   ),
                                             'FILTERREF'=>'id_translation'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>