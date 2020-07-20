<?php
namespace model\tests\User\Roles\datasources;
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
            'id_role'=>array(
                'MODEL'=>'\model\tests\User\Roles',
                'FIELD'=>'id_role',
                'TRIGGER_VAR'=>'id_role'
            ),
            'role'=>array(
                'MODEL'=>'\model\tests\User\Roles',
                'FIELD'=>'role',
                'TRIGGER_VAR'=>'role'
            )
        ),
        'FIELDS'=>array(
            'id_role'=>array(
                'MODEL'=>'\model\tests\User\Roles',
                'FIELD'=>'id_role'

            ),
            'role'=>array(
                'MODEL'=>'\model\tests\User\Roles',
                'FIELD'=>'role'

            )
        ),

        'SOURCE'=>[
        'STORAGE'=>array(
            'MYSQL'=>array(
                'DEFINITION'=>array(
                    'TABLE'=>'Roles',
                    'BASE'=>array(
                        'id_role','role'
                    ),
                    'CONDITIONS'=>array(
                        array(
                            'FILTER'=>array(
                                'F'=>'id_role',
                                'OP'=>'=',
                                'V'=>'[%id_role%]'
                            ),
                            'TRIGGER_VAR'=>'id_role',
                            'DISABLE_IF'=>'0',
                            'FILTERREF'=>'id_role'
                        ),
                        array(
                            'FILTER'=>array(
                                'F'=>'role',
                                'OP'=>'=',
                                'V'=>'[%role%]'
                            ),
                            'TRIGGER_VAR'=>'role',
                            'DISABLE_IF'=>'0',
                            'FILTERREF'=>'role'
                        )
                    )
                )
            )
        )
            ]
    );
}
