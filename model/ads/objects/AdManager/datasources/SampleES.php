<?php


namespace model\ads\AdManager\datasources;


class SampleES
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'start'=>array(
                "TYPE"=>"Timestamp"
            ),
            'end'=>array(
                "TYPE"=>"Timestamp"
            ),
            'domain'=>array(
                "TYPE"=>"String"
            )
        ),
        'FIELDS'=>array(
            'device'=>array(
                "LABEL"=>"device",
                'TYPE'=>'String',
            ),
            'count'=>array(
                "LABEL"=>"Count",
                'TYPE'=>"Integer"
            ),
            'timestamp'=>array(
                "LABEL"=>"Time",
                'TYPE'=>'Timestamp'
            ),
            'domain'=>array(
                "LABEL"=>"domain",
                'TYPE'=>"String"
            )
        ),
        'SOURCE'=>[
            'STORAGE'=>[
                'es'=>[
                    "DEFINITION"=>[
                        'INDEX'=>"gpt*",
                        'BASE'=>[],
                        'GROUPBY'=>"domain => device => (3600000)timestamp",
                        'CONDITIONS'=>array(
                            array(
                                'FILTER'=>array(
                                    'F'=>'timestamp',
                                    'OP'=>'>',
                                    'V'=>'[%/start%]'
                                ),
                                'TRIGGER_VAR'=>'start'
                            ),
                            array(
                                'FILTER'=>array(
                                    'F'=>'timestamp',
                                    'OP'=>'<=',
                                    'V'=>'[%/end%]'
                                ),
                                'TRIGGER_VAR'=>'end'
                            ),
                            array(
                                'FILTER'=>array(
                                    'F'=>'domain',
                                    'OP'=>'=',
                                    'V'=>'[%/domain%]'
                                ),
                                'TRIGGER_VAR'=>'domain'
                            )
                        )
                    ]

                    ]
                ]
        ]
    );

}
?>
