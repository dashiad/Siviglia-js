<?php
namespace model\web\Page;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Page/Definition.php
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
               'INDEXFIELDS'=>array('id_page'),
               'TABLE'=>'page',
               'LABEL'=>'Page',
               'SHORTLABEL'=>'Page',
               'CARDINALITY'=>'300',
               'CARDINALITY_TYPE'=>'FIXED',
               'FIELDS'=>array(
                     'id_page'=>array(
                           'TYPE'=>'AutoIncrement',
                           'MIN'=>0,
                           'MAX'=>9999999999,
                           'LABEL'=>'id_page',
                           'SHORTLABEL'=>'id_page',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>true
                           ),
                     'tag'=>array(
                           'TYPE'=>'String',
                           'LABEL'=>'Tag',
                           'MAXLENGTH'=>30,
                           'DESCRIPTIVE'=>'true',
                           'SHORTLABEL'=>'tag',
                           'ISLABEL'=>true
                           ),
                     'id_site'=>array(
                           'DEFAULT'=>'NULL',
                           'FIELDS'=>array('id_site'=>'id_site'),
                           'MODEL'=>'\model\web\Site',
                           'LABEL'=>'id_website',
                           'SHORTLABEL'=>'id_site',
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
                           'ISLABEL'=>true
                           ),
                     'date_add'=>array(
                           'DEFAULT'=>'NULL',
                           'SHORTLABEL'=>'date_add',
                           'TYPE'=>'DateTime',
                           'LABEL'=>'date_add',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>true
                           ),
                     'date_modified'=>array(
                           'DEFAULT'=>'NULL',
                           'SHORTLABEL'=>'date_modified',
                           'TYPE'=>'DateTime',
                           'LABEL'=>'date_modified',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>true
                           ),
                     'id_type'=>array(
                           'DEFAULT'=>'1',
                           'SHORTLABEL'=>'id_type',
                           'TYPE'=>'Integer',
                           'LABEL'=>'id_type',
                           'DESCRIPTIVE'=>'true',
                           'ISLABEL'=>true
                           ),
                     'isPrivate'=>array(
                           'TYPE'=>'Boolean',
                           'LABEL'=>'isPrivate',
                           'SHORTLABEL'=>'isPrivate',
                           'DEFAULT'=>false,
                           'DESCRIPTIVE'=>true,
                           'ISLABEL'=>'true'
                           ),
                     'path'=>array(
                           'TYPE'=>'String',
                           'MINLENGTH'=>2,
                           'MAXLENGTH'=>255,
                           'LABEL'=>'path',
                           'SEARCHABLE'=>1,
                           'SHORTLABEL'=>'path',
                           'DESCRIPTIVE'=>true,
                           'ISLABEL'=>'true'
                           ),
                     'title'=>array(
                           'TYPE'=>'String',
                           'MINLENGTH'=>2,
                           'MAXLENGTH'=>200,
                           'LABEL'=>'title',
                           'SEARCHABLE'=>1,
                           'SHORTLABEL'=>'title',
                           'DESCRIPTIVE'=>true,
                           'ISLABEL'=>'true'
                           ),
                     'tags'=>array(
                           'TYPE'=>'String',
                           'MINLENGTH'=>2,
                           'MAXLENGTH'=>255,
                           'LABEL'=>'tags',
                           'SEARCHABLE'=>1,
                           'SHORTLABEL'=>'tags',
                           'DESCRIPTIVE'=>true,
                           'ISLABEL'=>'true'
                           ),
                     'description'=>array(
                           'TYPE'=>'String',
                           'MINLENGTH'=>2,
                           'MAXLENGTH'=>255,
                           'LABEL'=>'description',
                           'SEARCHABLE'=>1,
                           'SHORTLABEL'=>'description',
                           'DESCRIPTIVE'=>true,
                           'ISLABEL'=>'true'
                           ),
                        'role'=>array(
                            'LABEL'=>'Role',
                            'SHORTLABEL'=>'Role',
                            'DESCRIPTIVE'=>true,
                            'TYPE'=>'Enum',
                            'VALUES'=>array(
                                \model\web\Page::PAGE_ROLE_VIEW,
                                \model\web\Page::PAGE_ROLE_LIST,
                                \model\web\Page::PAGE_ROLE_EDIT,
                                \model\web\Page::PAGE_ROLE_CREATE,
                                \model\web\Page::PAGE_ROLE_GENERIC
                            )
                        ),
                        'model'=>array(
                            'TYPE'=>'String',
                            'REQUIRED'=>false,
                            'LABEL'=>"Model",
                            "SHORTLABEL"=>"Model",
                            "MAXLENGTH"=>128
                        ),
                        'modelParam'=>array(
                            'TYPE'=>'String',
                            'REQUIRED'=>false,
                            'LABEL'=>"Model id param",
                            "SHORTLABEL"=>"Param",
                            "MAXLENGTH"=>40
                        ),
                        'datasource'=>array(
                            'TYPE'=>'String',
                            'REQUIRED'=>false,
                            'LABEL'=>'Datasource',
                            'SHORTLABEL'=>'Datasource',
                            'MAXLENGTH'=>128
                        ),
                        'requiredPermission'=>array(
                            'TYPE'=>'ENUM',
                            'DEFAULT'=>'PUBLIC',
                            'LABEL'=>'Required permission',
                            'VALUES'=>array(
                                \model\web\Page::PAGE_PERMISSION_PUBLIC,
                                \model\web\Page::PAGE_PERMISSION_LOGGED,
                                \model\web\Page::PAGE_PERMISSION_OWNER,
                                \model\web\Page::PAGE_PERMISSION_MODEL,
                                \model\web\Page::PAGE_PERMISSION_SITE
                            )
                        ),
                        'requireOwnership'=>array(
                            'TYPE'=>"Boolean",
                            'REQUIRED'=>false,
                            'DEFAULT'=>false,
                            'LABEL'=>'Only owner'
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