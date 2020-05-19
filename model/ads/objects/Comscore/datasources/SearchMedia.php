<?php
namespace model\ads\Comscore\datasources;

class SearchMedia
{

    static $definition = array(
        'ROLE' => 'list',
        'DATAFORMAT' => 'table',
        'PARAMS' => array(
            'ExactMatch' => array(
                'LABEL' => 'Data Source',
                'REQUIRED' => true,
                'TYPE' => 'Boolean',
                'DEFAULT' => false
            ),
            'Critera' => array(
                'LABEL' => 'Search',
                'REQUIRED' => true,
                'TYPE' => 'String'
            ),
            'dataSource' => array(
                'LABEL' => 'Data Source',
                'REQUIRED' => false,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    25 => 'Multi-Platform'
                )
            ),
            'geo' => array(
                'LABEL' => NULL,
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    724 => 'Spain'
                )
            ),
            'timeType' => array(
                'LABEL' => 'Time Types',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    1 => 'Month'
                )
            ),
            'timePeriod' => array(
                'LABEL' => 'TimePeriod',
                'REQUIRED' => true,
                'TYPE' => '/model/ads/Comscore/types/Month'
            ),
            'mediaSetType' => array(
                'LABEL' => 'MediaSet Type',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    1 => 'Media'
                )
            )
        ),
        'FIELDS' => array(
            'name'      => 'Name',
            'id'        => 'Id',
            'mediaType' => 'MediaType',
        ),
        'PERMISSIONS' => array(),
        'SOURCE' => array(
            'STORAGE' => array(
                'comscore' => array(
                    'NAME' => 'model\\ads\\Comscore',
                    'CLASS' => 'model\\ads\\Comscore\\serializers\\ComscoreSerializer',
                    'DEFINITION' => array(
                        'BASE' => 'ON ComscoreDemographics CALL SearchMedia WITH (
                            [%dataSource:dataSource="{%dataSource%}",%]
                            [%geo:geo="{%geo%}",%]
                            [%timeType:timeType="{%timeType%}",%]
                            [%timePeriod:timePeriod="{%timePeriod%}",%]
                            [%ExactMatch:ExactMatch="{%ExactMatch%}",%]
                            [%Critera:Critera="{%Critera%}",%])',
                        'CONDITIONS' => array(
                            0 => array(
                                'FILTER' => '[%dataSource%]',
                                'TRIGGER_VAR' => 'dataSource'
                            ),
                            1 => array(
                                'FILTER' => '[%geo%]',
                                'TRIGGER_VAR' => 'geo'
                            ),
                            2 => array(
                                'FILTER' => '[%timeType%]',
                                'TRIGGER_VAR' => 'timeType'
                            ),
                            3 => array(
                                'FILTER' => '[%timePeriod%]',
                                'TRIGGER_VAR' => 'timePeriod'
                            ),
                            4 => array(
                                'FILTER' => '[%ExactMatch%]',
                                'TRIGGER_VAR' => 'ExactMatch'
                            ),
                            5 => array(
                                'FILTER' => '[%Critera%]',
                                'TRIGGER_VAR' => 'Critera',
                            ),
                        )
                    )
                )
            )
        )
    );
}
