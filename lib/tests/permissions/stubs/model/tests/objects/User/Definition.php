<?php
namespace model\tests\User;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'modeltests',
        'DEFAULT_WRITE_SERIALIZER'=>'modeltests',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'User',
        'LABEL'=>'User',
        'SHORTLABEL'=>'User',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'User Id',
                'SHORTLABEL'=>'Id',
                'DESCRIPTIVE'=>'false',
                'ISLABEL'=>'false'
            ),
            'Name'=>array(
                'TYPE'=>'String',
                'MAXLENGTH'=>40,
                'DESCRIPTIVE'=>'true',
                'LABEL'=>'Name',
                'SHORTLABEL'=>'Name',
                'ISLABEL'=>'true'
            )
        ),
        'ALIASES'=>array(
            'posts'=>array(
                'TYPE'=>'InverseRelation',
                'MODEL'=>'\model\tests\Post',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICITY'=>'1:N',
                'CARDINALITY'=>100,
                'FIELDS'=>array('id'=>'creator_id')
            ),
            'comments'=>array(
                'TYPE'=>'InverseRelation',
                'MODEL'=>'\model\tests\Post\Comment',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICITY'=>'1:N',
                'CARDINALITY'=>100,
                'FIELDS'=>array('id'=>'id_user')
            ),
            'roles'=>array(
                'TYPE'=>'RelationMxN',
                'MODEL'=>'\model\tests\User\UserRole',
                'REMOTE_MODEL'=>'\model\tests\User\Roles',
                'FIELDS'=>array('id'=>'id_user'),
                //'REMOTE_MODEL'=>'ps_feature_value',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICTY'=>'M:N',
                'CARDINALITY'=>100,
                //'UNIQUE_RELATIONS'=>1
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
