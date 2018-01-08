<?php
namespace model\web\Lang\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/datasources/FullPs_feature_langDs.php
  CLASS:FullPs_feature_langDs
*
*
**/

class FullPs_feature_langDs
{
	 static  $definition=array(
               'LABEL'=>'FullPs_feature_langDs',
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
               'RELATION'=>'ps_feature_lang',
               'PARAMS'=>array(
                     'ps_feature_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'ps_feature_lang',
                           'TRIGGER_VAR'=>'ps_feature_lang'
                           )
                     ),
               'INCLUDE'=>array(
                     'ps_feature_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_feature_lang',
                           'DATASOURCE'=>'View',
                           'JOINTYPE'=>'LEFT',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           )
                     ),
               'PERMISSIONS'=>array('_PUBLIC_'),
               'FIELDS'=>array(
                     'name'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_lang',
                           'FIELD'=>'name'
                           ),
                     'is_rtl'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'is_rtl'
                           ),
                     'language_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'language_code'
                           ),
                     'iso_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'iso_code'
                           ),
                     'active'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'active'
                           ),
                     'id_lang'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_lang',
                           'FIELD'=>'id_lang'
                           ),
                     'date_format_full'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_full'
                           ),
                     'date_format_lite'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_lite'
                           ),
                     'id_feature'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_feature_lang',
                           'FIELD'=>'id_feature'
                           )
                     ),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>null,
                                 'BASE'=>'SELECT * FROM Lang tl LEFT JOIN ps_feature_lang tr ON tr.id_lang=tl.id_lang',
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
                                                   'F'=>'tl.ps_feature_lang',
                                                   'OP'=>'=',
                                                   'V'=>'{%ps_feature_lang%}'
                                                   ),
                                             'TRIGGER_VAR'=>'ps_feature_lang',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'ps_feature_lang'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>