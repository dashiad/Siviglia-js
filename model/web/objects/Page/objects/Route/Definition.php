<?php
/**
 * Class Definition
 * @package model\web\Page\Route
 *  (c) Smartclip
 */


namespace model\web\Page\Route;


class Definition extends \lib\model\BaseModelDefinition
{
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'default',
        'DEFAULT_WRITE_SERIALIZER'=>'default',
        'INDEXFIELDS'=>array('id_route'),
        'TABLE'=>'route',
        'LABEL'=>'route',
        'SHORTLABEL'=>'Ruta',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id_route'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'Route Id',
                'SHORTLABEL'=>'Route Id',
                'DESCRIPTIVE'=>'true',
                'ISLABEL'=>false
            ),
            'route'=>array(
                'TYPE'=>'String',
                'LABEL'=>'Tag',
                'MAXLENGTH'=>200,
                'DESCRIPTIVE'=>'true',
                'SHORTLABEL'=>'Route',
                'ISLABEL'=>true
            ),
            'id_page'=>array(

                'FIELDS'=>array('id_page'=>'id_page'),
                'MODEL'=>'\model\web\Page',
                'LABEL'=>'id_route',
                'SHORTLABEL'=>'id_route',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
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
