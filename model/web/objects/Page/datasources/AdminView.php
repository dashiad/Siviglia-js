<?php
namespace model\web\Page\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page/datasources/AdminView.php
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
                     'id_page'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'id_page',
                           'TRIGGER_VAR'=>'id_page'
                           )
                     ),
               'IS_ADMIN'=>1,
               'INDEXFIELDS'=>array(
                     'id_page'=>array(
                           'MODEL'=>'\model\Page',
                           'FIELD'=>'id_page',
                           'REQUIRED'=>'true'
                           )
                     ),
               'FIELDS'=>array(
                     'id_page'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'id_page'
                           ),
                     'tag'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'tag'
                           ),
                     'id_site'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'id_site'
                           ),
                     'name'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'name'
                           ),
                     'date_add'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'date_add'
                           ),
                     'date_modified'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'date_modified'
                           ),
                     'id_type'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'id_type'
                           ),
                     'isPrivate'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'isPrivate'
                           ),
                     'path'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'path'
                           ),
                     'title'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'title'
                           ),
                     'tags'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'tags'
                           ),
                     'description'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'description'
                           )
                     ),
               'INCLUDE'=>array(
                     '\model\Site_id_site'=>array(
                           'MODEL'=>'\model\web\Site',
                           'DATASOURCE'=>'AdminFullList',
                           'JOINTYPE'=>'LEFT',
                           'JOIN'=>array('id_site'=>'id_site')
                           )
                     ),
               'PERMISSIONS'=>array(
                     array(
                           'MODEL'=>'Page',
                           'PERMISSION'=>'adminView'
                           )
                     ),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'page',
                                 'BASE'=>array(
                                       'id_page',
                                       'tag',
                                       'id_site',
                                       'name',
                                       'date_add',
                                       'date_modified',
                                       'id_type',
                                       'isPrivate',
                                       'path',
                                       'title',
                                       'tags',
                                       'description'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'id_page',
                                                   'OP'=>'=',
                                                   'V'=>'[%id_page%]'
                                                   ),
                                             'FILTERREF'=>'id_page'
                                             )
                                       )
                                 )
                           )
                     )
               );
}
?>