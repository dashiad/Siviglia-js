<?php
namespace model\tests\ClassA;
class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id_site'),
        'TABLE'=>'Websites',
        'LABEL'=>'Site',
        'SHORTLABEL'=>'Website',
        'CARDINALITY'=>'300',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id_site'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'id_website',
                'SHORTLABEL'=>'id_website',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'host'=>array(
                'TYPE'=>'String',
                'MAXLENGTH'=>40,
                'DESCRIPTIVE'=>'true',
                'LABEL'=>'Host',
                'SHORTLABEL'=>'host',
                'ISLABEL'=>'true'
            ),
            'canonical_url'=>array(
                'TYPE'=>'String',
                'MAXLENGTH'=>255,
                'LABEL'=>'Canonical url',
                'SHORTLABEL'=>'canonical_url',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'hasSSL'=>array(
                'TYPE'=>'Boolean',
                'LABEL'=>'Has SSL',
                'SHORTLABEL'=>'hasSSL',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'namespace'=>array(
                'DEFAULT'=>'',
                'MINLENGTH'=>'4',
                'LABEL'=>'namespace',
                'SHORTLABEL'=>'namespace',
                'MAXLENGTH'=>'45',
                'TYPE'=>'String',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true'
            ),
            'websiteName'=>array(
                'DEFAULT'=>'',
                'MINLENGTH'=>'4',
                'LABEL'=>'name',
                'SHORTLABEL'=>'name',
                'MAXLENGTH'=>'45',
                'TYPE'=>'String',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>'true',
                'SEARCHABLE'=>1
            )
        ),
        'ALIASES'=>array(
            'Pages'=>array(
                'TYPE'=>'InverseRelation',
                'MODEL'=>'\model\web\Page',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICITY'=>'1:N',
                'CARDINALITY'=>100,
                'FIELDS'=>array('id_site'=>'id_site')
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
