<?php
namespace model\web\Page\PageResource;
/**
 FILENAME:/var/www/percentil/backoffice//web/objects/CustomSections/Definition.php
  CLASS:Definition
*
*
**/

class Definition extends \lib\model\BaseModelDefinition
{
	 static  $definition=array(
               'ROLE'=>'ENTITY',
               'DEFAULT_SERIALIZER'=>'default',
               'DEFAULT_WRITE_SERIALIZER'=>'default',
               'INDEXFIELDS'=>array('id_pageResource'),
               'TABLE'=>'PageResource',
               'LABEL'=>'Page Resource',
               'SHORTLABEL'=>'Page Resource',
               'CARDINALITY'=>'3000',
               'CARDINALITY_TYPE'=>'FIXED',
               'FIELDS'=>array(
                     'id_pageResource'=>array(
                           'TYPE'=>'AutoIncrement',
                           'MIN'=>0,
                           'MAX'=>9999999999,
                           'LABEL'=>'id_page',
                           'SHORTLABEL'=>'id_page',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>'false'
                           ),
                      'path'=>array(
                        'TYPE'=>'String',
                          'LABEL'=>'Path',
                          'MAXLENGTH'=>255,
                          'DESCRIPTIVE'=>true
                      ),
                     'id_page'=>array(
                           'DEFAULT'=>'NULL',
                           'FIELDS'=>array('id_page'=>'id_page'),
                           'MODEL'=>'\model\web\Page',
                           'LABEL'=>'Page id',
                           'SHORTLABEL'=>'Page id',
                           'TYPE'=>'Relationship',
                           'MULTIPLICITY'=>'1:N',
                           'ROLE'=>'HAS_ONE',
                           'CARDINALITY'=>1
                           ),
                     'name'=>array(
                           'TYPE'=>'String',
                           'MINLENGTH'=>2,
                           'MAXLENGTH'=>30,
                           'LABEL'=>'name',
                           'SEARCHABLE'=>1,
                           'SHORTLABEL'=>'name',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>'false'
                           ),
                     'date_add'=>array(
                           'DEFAULT'=>'NULL',
                           'SHORTLABEL'=>'date_add',
                           'TYPE'=>'DateTime',
                           'LABEL'=>'date_add',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>'false'
                           ),
                     'date_modified'=>array(
                           'DEFAULT'=>'NULL',
                           'SHORTLABEL'=>'date_modified',
                           'TYPE'=>'DateTime',
                           'LABEL'=>'date_modified',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>'false'
                           )
                     ),
               'PERMISSIONS'=>array(),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'ENGINE'=>'InnoDb',
                           'CHARACTER SET'=>'utf8',
                           'COLLATE'=>'utf8_general_ci',
                           'TABLE_OPTIONS'=>array('ROW_FORMAT'=>'FIXED')
                           )
                     )
               );
}
?>