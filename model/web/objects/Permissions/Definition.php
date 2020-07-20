<?php
namespace model\web\Permissions;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/Definition.php
  CLASS:Definition
*
*
**/

class Definition extends \lib\model\BaseModelDefinition
{
	 static  $definition=array(
               'ROLE'=>'ENTITY',
               'DEFAULT_SERIALIZER'=>'web',
               'DEFAULT_WRITE_SERIALIZER'=>'web',
               'INDEXFIELDS'=>array('id'),
               'TABLE'=>'_permissions',
               'LABEL'=>'Permissions',
               'SHORTLABEL'=>'Permissions',
               'CARDINALITY'=>'300',
               'CARDINALITY_TYPE'=>'FIXED',
               'FIELDS'=>array(
                     'id'=>array(
                           'TYPE'=>'AutoIncrement',
                           'MIN'=>0,
                           'MAX'=>9999999999,
                           'LABEL'=>'id',
                           'SHORTLABEL'=>'id',
                           'ISLABEL'=>'false'
                           ),
                     'aro_type'=>array(
                           'TYPE'=>'Enum',
                           'MYSQL'=>["STORE_AS_INTEGER"=>true],
                         'VALUES'=>[
                             "Item",
                             "Group"
                         ],
                           'LABEL'=>'Tipo de usuario',
                           'SHORTLABEL'=>'Tipo Usuario',
                           'ISLABEL'=>'false',
                         'REQUIRED'=>true
                           ),
                   'aco_type'=>array(
                       'TYPE'=>'Enum',
                       'MYSQL'=>["STORE_AS_INTEGER"=>true],
                       'VALUES'=>[
                           "Item",
                           "Group"
                       ],
                       'LABEL'=>'Tipo de permiso',
                       'SHORTLABEL'=>'Tipo Permiso',
                       'ISLABEL'=>'false',
                       'REQUIRED'=>true
                   ),
                   'axo_type'=>array(
                       'TYPE'=>'Enum',
                       'MYSQL'=>["STORE_AS_INTEGER"=>true],
                       'VALUES'=>[
                           "Item",
                           "Group"
                       ],
                       'LABEL'=>'Tipo de target',
                       'SHORTLABEL'=>'Tipo Target',
                       'ISLABEL'=>'false',
                       'REQUIRED'=>true
                   ),

                   'aro_id'=>array(
                       'TYPE'=>'Integer',
                       'LABEL'=>'Id usuario',
                       'SHORTLABEL'=>'Id Usuario',
                       'ISLABEL'=>'false',
                       'REQUIRED'=>true
                   ),
                   'aco_id'=>array(
                       'TYPE'=>'Integer',
                       'LABEL'=>'Id de permiso',
                       'SHORTLABEL'=>'Id Permiso',
                       'ISLABEL'=>'false',
                       'REQUIRED'=>true
                   ),
                   'axo_id'=>array(
                       'TYPE'=>'Integer',
                       'LABEL'=>'Id de target',
                       'SHORTLABEL'=>'Id Target',
                       'ISLABEL'=>'false',
                       'REQUIRED'=>true
                   ),
                     'allow'=>array(
                           'TYPE'=>'Boolean',
                           'DEFAULT'=>true,
                           'LABEL'=>"Permitir",
                           'SHORTLABEL'=>'Permitir',
                           'ISLABEL'=>'false'
                           ),
                   'enabled'=>array(
                       'TYPE'=>'Boolean',
                       'DEFAULT'=>true,
                       'LABEL'=>"Activa",
                       'SHORTLABEL'=>'Activa',
                       'ISLABEL'=>'false'
                   ),
                     'ACLDATE'=>array(
                           'MINLENGTH'=>'4',
                           'LABEL'=>'Fecha',
                           'SHORTLABEL'=>'Fecha',
                           'TYPE'=>'DateTime'
                           )
                     ),
               'PERMISSIONS'=>array(),
               'SOURCE'=>[
                    'STORAGE'=>array(
                         'MYSQL'=>array(
                           'ENGINE'=>'InnoDb',
                           'CHARACTER SET'=>'utf8',
                           'COLLATE'=>'utf8_general_ci',
                           'TABLE_OPTIONS'=>array('ROW_FORMAT'=>'FIXED')
                           )
                        )
                   ]
               );
}
