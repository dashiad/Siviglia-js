<?php
namespace model\web\Site\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/NotPages.php
  CLASS:NotPages
*
*
**/

class NotPages
{
	 static  $definition=array(
               'LABEL'=>'NotPages',
               'DATAFORMAT'=>'Table',
               'ROLE'=>'MxNlist',
               'IS_ADMIN'=>0,
               'INDEXFIELDS'=>array(
                     'id_site'=>array(
                           'MODEL'=>'\model\Site',
                           'FIELD'=>'id_site',
                           'REQUIRED'=>'true'
                           )
                     ),
               'RELATION'=>'Pages',
               'PARAMS'=>array(
                     'id_site'=>array(
                           'MODEL'=>'\model\web\Page',
                           'FIELD'=>'id_site',
                           'TRIGGER_VAR'=>'id_site'
                           )
                     ),
               'INCLUDE'=>array(
                     '\model\Page'=>array(
                           'MODEL'=>'\model\Page',
                           'DATASOURCE'=>'View',
                           'JOINTYPE'=>'OUTER',
                           'JOIN'=>array('id_site'=>'id_site')
                           )
                     ),
               'PERMISSIONS'=>array('PUBLIC'),
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
               'SOURCE'=>[
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'BASE'=>'SELECT DISTINCT tr.* FROM page tr,Websites tl WHERE NOT (tr.id_site=tl.id_site)',
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'tl.id_site',
                                                   'OP'=>'=',
                                                   'V'=>'[%id_site%]'
                                                   ),
                                             'FILTERREF'=>'id_site'
                                             )
                                       )
                                 )
                           )
                     )
                   ]
               );
}
?>
