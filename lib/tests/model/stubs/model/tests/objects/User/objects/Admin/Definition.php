<?php
namespace model\tests\User\Admin;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'EXTENDS'=>'\model\tests\User',
        'DEFAULT_SERIALIZER'=>'modeltests',
        'DEFAULT_WRITE_SERIALIZER'=>'modeltests',
        'INDEXFIELDS'=>array('id_user'),
        'TABLE'=>'admin',
        'LABEL'=>'Admin',
        'SHORTLABEL'=>'admin',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id_user'=>array(

                'FIELDS'=>array('id_user'=>'id'),
                'MODEL'=> '\model\tests\User',
                'LABEL'=>'User id',
                'SHORTLABEL'=>'User Id',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:1',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
            ),
            'adminrole'=>array(
                'TYPE'=>'Enum',
                'VALUES'=>['Normal','Master'],
                'DESCRIPTIVE'=>'true',
                'LABEL'=>'Role',
                'SHORTLABEL'=>'Role',
                'ISLABEL'=>'true'
            )
        )
    );
}
