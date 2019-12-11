<?php
namespace model\web\Lang\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/datasources/FullListDs.php
  CLASS:FullListDs
*
*
**/

class FullListDs
{
	 static  $definition=array(
               'ROLE'=>'list',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                     'name'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'name',
                           'TRIGGER_VAR'=>'name'
                           ),
                     'dynname'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'name',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'is_rtl'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'is_rtl',
                           'TRIGGER_VAR'=>'is_rtl'
                           ),
                     'language_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'language_code',
                           'TRIGGER_VAR'=>'language_code'
                           ),
                     'dynlanguage_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'language_code',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'iso_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'iso_code',
                           'TRIGGER_VAR'=>'iso_code'
                           ),
                     'dyniso_code'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'iso_code',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'active'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'active',
                           'TRIGGER_VAR'=>'active'
                           ),
                     'id_lang'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'id_lang',
                           'TRIGGER_VAR'=>'id_lang'
                           ),
                     'date_format_full'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_full',
                           'TRIGGER_VAR'=>'date_format_full'
                           ),
                     'dyndate_format_full'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_full',
                           'PARAMTYPE'=>'DYNAMIC'
                           ),
                     'date_format_lite'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_lite',
                           'TRIGGER_VAR'=>'date_format_lite'
                           ),
                     'dyndate_format_lite'=>array(
                           'MODEL'=>'\model\web\Lang',
                           'FIELD'=>'date_format_lite',
                           'PARAMTYPE'=>'DYNAMIC'
                           )
                     ),
               'IS_ADMIN'=>0,
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
               'PERMISSIONS'=>array('PUBLIC'),
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
                                                   'F'=>'name',
                                                   'OP'=>'=',
                                                   'V'=>'[%name%]'
                                                   ),
                                             'TRIGGER_VAR'=>'name',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'name'
                                             ),
                                       array(
                                             'FILTER'=>'name LIKE CONCAT([%dynname%],\'%\')',
                                             'TRIGGER_VAR'=>'dynname',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dynname'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'is_rtl',
                                                   'OP'=>'=',
                                                   'V'=>'[%is_rtl%]'
                                                   ),
                                             'TRIGGER_VAR'=>'is_rtl',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'is_rtl'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'language_code',
                                                   'OP'=>'=',
                                                   'V'=>'[%language_code%]'
                                                   ),
                                             'TRIGGER_VAR'=>'language_code',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'language_code'
                                             ),
                                       array(
                                             'FILTER'=>'language_code LIKE CONCAT([%dynlanguage_code%],\'%\')',
                                             'TRIGGER_VAR'=>'dynlanguage_code',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dynlanguage_code'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'iso_code',
                                                   'OP'=>'=',
                                                   'V'=>'[%iso_code%]'
                                                   ),
                                             'TRIGGER_VAR'=>'iso_code',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'iso_code'
                                             ),
                                       array(
                                             'FILTER'=>'iso_code LIKE CONCAT([%dyniso_code%],\'%\')',
                                             'TRIGGER_VAR'=>'dyniso_code',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dyniso_code'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'active',
                                                   'OP'=>'=',
                                                   'V'=>'[%active%]'
                                                   ),
                                             'TRIGGER_VAR'=>'active',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'active'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'id_lang',
                                                   'OP'=>'=',
                                                   'V'=>'[%id_lang%]'
                                                   ),
                                             'TRIGGER_VAR'=>'id_lang',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'id_lang'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'date_format_full',
                                                   'OP'=>'=',
                                                   'V'=>'[%date_format_full%]'
                                                   ),
                                             'TRIGGER_VAR'=>'date_format_full',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'date_format_full'
                                             ),
                                       array(
                                             'FILTER'=>'date_format_full LIKE CONCAT([%dyndate_format_full%],\'%\')',
                                             'TRIGGER_VAR'=>'dyndate_format_full',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dyndate_format_full'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'date_format_lite',
                                                   'OP'=>'=',
                                                   'V'=>'[%date_format_lite%]'
                                                   ),
                                             'TRIGGER_VAR'=>'date_format_lite',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'date_format_lite'
                                             ),
                                       array(
                                             'FILTER'=>'date_format_lite LIKE CONCAT([%dyndate_format_lite%],\'%\')',
                                             'TRIGGER_VAR'=>'dyndate_format_lite',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'dyndate_format_lite'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>