<?php
namespace model\tests\Post\Comment;
class Definition extends \lib\model\BaseModelDefinition
{
    /*
     * `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` varchar(45) DEFAULT NULL,
  `comment` varchar(45) DEFAULT NULL,

     */
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'comment',
        'LABEL'=>'Comments',
        'SHORTLABEL'=>'Comments',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        'FIELDS'=>array(
            'id'=>array(
                'TYPE'=>'AutoIncrement',
                'MIN'=>0,
                'MAX'=>9999999999,
                'LABEL'=>'Post Id',
                'SHORTLABEL'=>'Id',
                'DESCRIPTIVE'=>'false',
                'ISLABEL'=>'false'
            ),
            'id_user'=>array(

                'FIELDS'=>array('id_user'=>'id'),
                'MODEL'=> '\model\tests\User',
                'LABEL'=>'Creator',
                'SHORTLABEL'=>'Creator',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
            ),
            'id_post'=>array(

                'FIELDS'=>array('id_post'=>'id'),
                'MODEL'=> '\model\tests\Post',
                'LABEL'=>'Post',
                'SHORTLABEL'=>'Post',
                'TYPE'=>'Relationship',
                'MULTIPLICITY'=>'1:N',
                'ROLE'=>'HAS_ONE',
                'CARDINALITY'=>1
            ),
            'title'=>[
                'TYPE'=>'String',
                'MAXLENGTH'=>45,
                'DESCRIPTIVE'=>'true',
                'LABEL'=>'Title',
                'SHORTLABEL'=>'Title',
                'ISLABEL'=>'true'
            ],
            'content'=>[
                'TYPE'=>'Text',
                'LABEL'=>'Content',
                'SHORTLABEL'=>'Content',
                'ISLABEL'=>'true'
            ]
        ),
        'ALIASES'=>array(
            'comments'=>array(
                'TYPE'=>'InverseRelation',
                'MODEL'=>'\model\tests\Post\Comment',
                'ROLE'=>'HAS_MANY',
                'MULTIPLICITY'=>'1:N',
                'CARDINALITY'=>100,
                'FIELDS'=>array('id'=>'id_post')
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
