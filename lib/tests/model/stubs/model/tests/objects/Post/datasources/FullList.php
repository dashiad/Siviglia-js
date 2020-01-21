<?php
namespace model\tests\Post\datasources;
/**

CLASS:FullList
 *
 *
 **/

class FullList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'PARAMS'=>array(
            'id'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'id',
                'TRIGGER_VAR'=>'id'
            ),
            //"id","creator_id","title","content"
            'creator_id'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'creator_id',
                'TRIGGER_VAR'=>'creator_id'
            )

        ),
        'FIELDS'=>array(
            'id'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'id'
            ),
            'creator_id'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'creator_id'
            ),
            'title'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'title'
            ),
            'content'=>array(
                'MODEL'=>'\model\tests\Post',
                'FIELD'=>'content'
            )
        ),
        'SOURCE'=>[
        'STORAGE'=>array(
            'MYSQL'=>array(
                'DEFINITION'=>array(
                    'TABLE'=>'post',
                    'BASE'=>array(
                        'id','creator_id','title','content'
                    ),
                    'CONDITIONS'=>array(
                        array(
                            'FILTER'=>array(
                                'F'=>'id',
                                'OP'=>'=',
                                'V'=>'[%id%]'
                            ),
                            'TRIGGER_VAR'=>'id',
                            'DISABLE_IF'=>'0',
                            'FILTERREF'=>'id'
                        ),
                        array(
                            'FILTER'=>array(
                                'F'=>'creator_id',
                                'OP'=>'=',
                                'V'=>'[%creator_id%]'
                            ),
                            'TRIGGER_VAR'=>'creator_id',
                            'DISABLE_IF'=>'0',
                            'FILTERREF'=>'Name'
                        )
                    )
                )
            )
        )
            ]
    );
}
?>
