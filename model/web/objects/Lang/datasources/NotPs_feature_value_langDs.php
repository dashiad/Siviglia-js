<?php
namespace model\web\Lang\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/datasources/NotPs_feature_value_langDs.php
  CLASS:NotPs_feature_value_langDs
*
*
**/

class NotPs_feature_value_langDs
{
	 static  $definition=array(
               'LABEL'=>'NotPs_feature_value_langDs',
               'DATAFORMAT'=>'Table',
               'ROLE'=>'MxNlist',
               'IS_ADMIN'=>0,
               'INDEXFIELDS'=>array(
                     'id_lang'=>array(
                           'MODEL'=>'Lang',
                           'FIELD'=>'id_lang',
                           'REQUIRED'=>'true'
                           )
                     ),
               'RELATION'=>'ps_feature_value_lang',
               'PARAMS'=>array(
                     'ps_feature_value_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'ps_feature_value_lang',
                           'TRIGGER_VAR'=>'ps_feature_value_lang'
                           )
                     ),
               'INCLUDE'=>array(
                     'ps_feature_value_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_feature_value_lang',
                           'DATASOURCE'=>'View',
                           'JOINTYPE'=>'OUTER',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           )
                     ),
               'PERMISSIONS'=>array('PUBLIC'),
               'FIELDS'=>array(
                     'id_lang'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_value_lang',
                           'FIELD'=>'id_lang'
                           ),
                     'value'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_value_lang',
                           'FIELD'=>'value'
                           ),
                     'id_feature_value'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_value_lang',
                           'FIELD'=>'id_feature_value'
                           )
                     ),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>null,
                                 'BASE'=>'SELECT DISTINCT tr.* FROM ps_feature_value_lang tr,Lang tl WHERE NOT (tr.id_lang=tl.id_lang)',
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'tl.id_lang',
                                                   'OP'=>'=',
                                                   'V'=>'{%id_lang%}'
                                                   ),
                                             'FILTERREF'=>'id_lang'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'tl.ps_feature_value_lang',
                                                   'OP'=>'=',
                                                   'V'=>'{%ps_feature_value_lang%}'
                                                   ),
                                             'TRIGGER_VAR'=>'ps_feature_value_lang',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'ps_feature_value_lang'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>