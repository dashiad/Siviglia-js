<?php
namespace model\web\Lang\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/datasources/ViewDs.php
  CLASS:ViewDs
*
*
**/

class ViewDs
{
	 static  $definition=array(
               'ROLE'=>'view',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                     'id_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'id_lang',
                           'TRIGGER_VAR'=>'id_lang'
                           )
                     ),
               'IS_ADMIN'=>0,
               'INDEXFIELDS'=>array(
                     'id_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'id_lang',
                           'REQUIRED'=>'true'
                           )
                     ),
               'FIELDS'=>array(
                     'name'=>array(
                           'MODEL'=>'\model\web\Lang',
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
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'id_lang'
                           ),
                     'date_format_full'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_full'
                           ),
                     'date_format_lite'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_lite'
                           )
                     ),
               'INCLUDE'=>array(
                     'ps_feature_value_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_feature_value_lang',
                           'DATASOURCE'=>'FullList',
                           'JOINTYPE'=>'INNER',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           ),
                     'ps_feature_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_feature_lang',
                           'DATASOURCE'=>'FullList',
                           'JOINTYPE'=>'INNER',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           ),
                     'ps_category_lang'=>array(
                           'OBJECT'=>'backoffice\ps_product\ps_category_lang',
                           'DATASOURCE'=>'FullList',
                           'JOINTYPE'=>'INNER',
                           'JOIN'=>array('id_lang'=>'id_lang')
                           )
                     ),
               'PERMISSIONS'=>array(["TYPE"=>"Public"]),
         'SOURCE'=>[
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'Lang',
                                 'BASE'=>array(
                                       'name',
                                       'is_rtl',
                                       'language_code',
                                       'iso_code',
                                       'active',
                                       'id_lang',
                                       'date_format_full',
                                       'date_format_lite'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'id_lang',
                                                   'OP'=>'=',
                                                   'V'=>'[%id_lang%]'
                                                   ),
                                             'FILTERREF'=>'id_lang'
                                             )
                                       )
                                 )
                           )
                     )
             ]
               );
}
?>
