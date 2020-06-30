<?php
namespace model\tests\User\UserRole;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'MULTIPLE_RELATION',
        'DEFAULT_SERIALIZER'=>'modeltests',
        'DEFAULT_WRITE_SERIALIZER'=>'modeltests',
        'MULTIPLE_RELATION'=>array(
            'FIELDS'=>array(
                'id_user',
                'id_role'
            )
        ),
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'UserRole',
        'LABEL'=>'UserRole',
        'SHORTLABEL'=>'UserRole',
        'CARDINALITY'=>'100000',
        'CARDINALITY_TYPE'=>'VARIABLE',
        'FIELDS'=>array(
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'id',
                'SHORTLABEL'=>'id',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'id_user'=>array(

                'FIELDS'=>array('id_user'=>'id'),
                'MODEL'=>'\model\tests\User',
                'LABEL'=>'id_user',
                'SHORTLABEL'=>'id_user',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1,
                'DESCRIPTIVE'=>'true'
            ),
            'id_role'=>array(

                'FIELDS'=>array('id_role'=>'id_role'),
                'MODEL'=>'\model\tests\User\Roles',
                'LABEL'=>'id_role',
                'SHORTLABEL'=>'id_role',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1,
                'DESCRIPTIVE'=>'true'
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
