<?php
namespace model\web\Permissions\PermissionGroups;
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
        'TABLE'=>'_permission_groups',
        'LABEL'=>'Permission Groups',
        'SHORTLABEL'=>'Permission Groups',
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
            'group_type'=>array(
                'TYPE'=>'Enum',
                'MYSQL'=>["STORE_AS_INTEGER"=>true],
                'VALUES'=>[
                    "User",
                    "Permission",
                    "Target"
                ],
                'LABEL'=>'Tipo de grupo',
                'SHORTLABEL'=>'Tipo Grupo',
                'ISLABEL'=>'false',
                'REQUIRED'=>true
            ),
            'group_name'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Nombre del grupo',
                'SHORTLABEL'=>'Group name',
                'MINLENGTH'=>2,
                'MAXLENGTH'=>20,
                'ISLABEL'=>'true',
                'REQUIRED'=>true
            ),
            'group_parent'=>array(
                'TYPE'=>'Integer',
                'LABEL'=>'Group parent',
                'SHORTLABEL'=>'Group parent',
                'REQUIRED'=>true
            ),
            'group_path'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Path del grupo',
                'SHORTLABEL'=>'Path',
                'MINLENGTH'=>2,
                'MAXLENGTH'=>200,
                'ISLABEL'=>'false',
                'REQUIRED'=>true
            ),
            'group_charPath'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Path Completo',
                'SHORTLABEL'=>'Path Completo',
                'MINLENGTH'=>2,
                'MAXLENGTH'=>255,
                'ISLABEL'=>'true',
                'REQUIRED'=>false
            )
        ),
        'ALIASES'=>[
            'items'=>array(
                'TYPE'=>'RelationMxN',
                'MODEL'=>'\model\web\Permissions\PermissionGroupItems',
                'REMOTE_MODEL'=>'\model\web\Permissions\PermissionItems',
                'FIELDS'=>array('id'=>'group_id'),
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
