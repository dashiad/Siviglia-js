<?php
namespace model\tests\User\datasources;
/**

CLASS:FullList
 *
 *
 **/

class FullListParam
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'PARAMS'=>array(
            'id'=>array(
                'MODEL'=>'\model\tests\User',
                'FIELD'=>'id',
                'TRIGGER_VAR'=>'id'
            ),
            'Name'=>array(
                'MODEL'=>'\model\tests\User',
                'FIELD'=>'Name',
                'TRIGGER_VAR'=>'Name'
            )
        ),
        'FIELDS'=>array(
            'id'=>array(
                'MODEL'=>'\model\tests\User',
                'FIELD'=>'id'
            ),
            'Name'=>array(
                'MODEL'=>'\model\tests\User',
                'FIELD'=>'Name'
            )
        ),
        "INCLUDE"=>array(
            'Posts'=>array(
                'MODEL'=>'\model\tests\Post',
                'DATASOURCE'=>'FullList',
                'JOINTYPE'=>'LEFT',
                'JOIN'=>array(
                    // OJO!!! REMOTO-LOCAL
                    'creator_id'=>'id'
                )
            )
        ),
        'SOURCE'=>[
        'STORAGE'=>array(
            'MYSQL'=>array(
                'DEFINITION'=>array(
                    'TABLE'=>'user',
                    'BASE'=>"SELECT id, Name from User where [%0%] and [%1%]",
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
                            'FILTER'=>"[%Name:Name={%Name%}%][%!Name:Name='User1'%]"
                        )
                    )
                )
            )
        )
            ]
    );
}
?>
