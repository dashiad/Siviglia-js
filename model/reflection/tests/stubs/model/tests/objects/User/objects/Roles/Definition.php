<?php
namespace model\tests\User\Roles;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id_role'),
        'TABLE'=>'Roles',
        'LABEL'=>'Roles',
        'SHORTLABEL'=>'Roles',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id_role'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'User Id',
                'SHORTLABEL'=>'Id',
                'DESCRIPTIVE'=>'false',
                'ISLABEL'=>'false'
            ),
            'role'=>array(
                'TYPE'=>'String',
                'DESCRIPTIVE'=>'true',
                'LABEL'=>'Role',
                'SHORTLABEL'=>'Role',
                'ISLABEL'=>'true'
            )
        )
    );
}