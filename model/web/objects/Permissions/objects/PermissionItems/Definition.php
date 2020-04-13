<?php
namespace model\web\Permissions\PermissionItems;
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
               'TABLE'=>'_permission_items',
               'LABEL'=>'Permission Items',
               'SHORTLABEL'=>'Permission Items',
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
                     'item_type'=>array(
                           'TYPE'=>'Enum',
                           'MYSQL'=>["STORE_AS_INTEGER"=>true],
                         'VALUES'=>[
                             "User",
                             "Permission",
                             "Target"
                         ],
                         'LABEL'=>'Tipo de item',
                         'SHORTLABEL'=>'Tipo Item',
                         'ISLABEL'=>'false',
                         'REQUIRED'=>true
                     ),
                   'item_name'=>array(
                       'TYPE'=>'String',
                       'LABEL'=>'Item name',
                       'SHORTLABEL'=>'Item name',
                       'MINLENGTH'=>2,
                       'MAXLENGTH'=>20,
                       'ISLABEL'=>'true',
                       'REQUIRED'=>true
                   ),
                   'item_value'=>array(
                       'TYPE'=>'String',
                       'LABEL'=>'Item value',
                       'SHORTLABEL'=>'Item value',
                       'MINLENGTH'=>2,
                       'MAXLENGTH'=>50,
                       'ISLABEL'=>'true',
                       'REQUIRED'=>true
                   )

               ),
               'ALIASES'=>[
                   'groups'=>array(
                       'TYPE'=>'RelationMxN',
                       'MODEL'=>'\model\web\Permissions\PermissionGroupItems',
                       'REMOTE_MODEL'=>'\model\web\Permissions\PermissionGroups',
                       'FIELDS'=>array('id'=>'item_id'),
                       //'REMOTE_MODEL'=>'ps_feature_value',
                       'ROLE'=>'HAS_MANY',
                       'MULTIPLICTY'=>'M:N',
                       'CARDINALITY'=>100
                       //'UNIQUE_RELATIONS'=>1
                   )
               ],
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
