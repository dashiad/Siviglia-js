<?php


namespace model\ads\AdManager\datasources;


class DomainsES
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
            )
        ),
        'FIELDS'=>array(

            'count'=>array(
                "LABEL"=>"Count",
                'TYPE'=>"Integer"
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
                        'GROUPBY'=>"domain",
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
                            )
                        )
                    ]

                    ]
                ]
        ]
    );

}
?>
