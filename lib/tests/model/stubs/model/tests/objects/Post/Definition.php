<?php
namespace model\tests\Post;
class Definition extends \lib\model\BaseModelDefinition
{
    /*
     * `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  `created_on` datetime DEFAULT NULL,
     */
    static  $definition=array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'post',
        'LABEL'=>'Post',
        'SHORTLABEL'=>'Post',
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
            'creator_id'=>array(
                'DEFAULT'=>'NULL',
                'FIELDS'=>array('creator_id'=>'id'),
                'MODEL'=> '\model\tests\User',
                'LABEL'=>'Creator',
                'SHORTLABEL'=>'Creator',
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
            ],
            'created_on'=>[
                'TYPE'=>'DateTime',
                'LABEL'=>'Created on',
                'SHORTLABEL'=>'Created',
                'ISLABEL'=>'false'
            ],
            'likes'=>[
                'TYPE'=>'Integer',
                'LABEL'=>'Likes'
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
