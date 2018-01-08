<?php
namespace model\web\Lang\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/datasources/FullPs_category_langDs.php
  CLASS:FullPs_category_langDs
*
*
**/

class FullPs_category_langDs
{
	 static  $definition=array(
               'LABEL'=>'FullPs_category_langDs',
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
               'RELATION'=>'ps_category_lang',
               'PARAMS'=>array(
                     'ps_category_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'ps_category_lang',
                           'TRIGGER_VAR'=>'ps_category_lang'
                           )
                     ),
               'INCLUDE'=>array(
                     'ps_category_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_category_lang',
                           'DATASOURCE'=>'View',
                           'JOINTYPE'=>'LEFT',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           )
                     ),
               'PERMISSIONS'=>array('_PUBLIC_'),
               'FIELDS'=>array(
                     'name'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
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
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
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
                     'meta_description'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'meta_description'
                           ),
                     'id_category'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'id_category'
                           ),
                     'meta_title'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'meta_title'
                           ),
                     'meta_keywords'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'meta_keywords'
                           ),
                     'description'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'description'
                           ),
                     'link_rewrite'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'link_rewrite'
                           ),
                     'ps_category_id_category'=>array(
                           'MODEL'=>'backoffice\ps_product\ps_category_lang',
                           'FIELD'=>'ps_category_id_category'
                           )
                     ),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>null,
                                 'BASE'=>'SELECT * FROM Lang tl LEFT JOIN ps_category_lang tr ON tr.id_lang=tl.id_lang',
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
                                                   'F'=>'tl.ps_category_lang',
                                                   'OP'=>'=',
                                                   'V'=>'{%ps_category_lang%}'
                                                   ),
                                             'TRIGGER_VAR'=>'ps_category_lang',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'ps_category_lang'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>