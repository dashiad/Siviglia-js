<?php
namespace model\ads\Comscore\datasources;

class KeyMeasures
{

    static $definition = array(
        'ROLE' => 'list',
        'DATAFORMAT' => 'table',
        'PARAMS' => array(
            'dataSource' => array(
                'LABEL' => 'Data Source',
                'REQUIRED' => false,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    25 => 'Multi-Platform'
                )
            ),
            'geo' => array(
                'LABEL' => 'Country',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    724 => 'Spain'
                )
            ),
            'loc' => array(
                'LABEL' => 'Universe',
                'REQUIRED' => false,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'String'
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
            'targetType' => array(
                'LABEL' => 'Target Type',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    0 => 'Simple',
                    1 => 'Advanced',
                    2 => 'Custom'
                )
            ),
            'targetGroup' => array(
                'LABEL' => 'Target Groups',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'universeTypeId' => array(
                'LABEL' => 'TargetUniverseType',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'targetCustom' => array(
                'LABEL' => 'Custom Target',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'target' => array(
                'LABEL' => 'Target',
                'REQUIRED' => false,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'String'
                )
            ),
            'mediaSetType' => array(
                'LABEL' => 'Media Set Type',
                'REQUIRED' => true,
                'TYPE' => 'String'
            ),
            'mediaSet' => array(
                'LABEL' => 'Media Catagory',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'excludeMediaLevel' => array(
                'LABEL' => 'This is to pass exclude media levels like Advertising Networks, Custom Entities and Custom Web Entities.               Passing this parameter will exclude data related to the mentioned media levels(Advertising Networks, Custom Entities and Custom Web Entities) from the report response when mediaSet is passed.              This paramater is not required when media is passed.',
                'REQUIRED' => false,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'String'
                )
            ),
            'media' => array(
                'LABEL' => 'Media',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'attributeId' => array(
                'LABEL' => 'Attribute Id',
                'REQUIRED' => false,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'Enum',
                    'VALUES' => array(
                        24937149 => 'Streaming Video',
                        24937150 => 'Newspaper',
                        24937151 => 'Streaming Audio',
                        24937152 => 'Cable/Broadcast TV',
                        24937153 => 'Blog',
                        24937154 => 'Magazine',
                        25504076 => 'Radio'
                    )
                )
            ),
            'attributeLogic' => array(
                'LABEL' => 'Attribute Logic',
                'REQUIRED' => false,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    0 => 'AND',
                    1 => 'OR',
                    2 => 'NOT'
                )
            ),
            'measure' => array(
                'LABEL' => 'Measures',
                'REQUIRED' => true,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'Integer',
                    /*'TYPE' => 'Enum',
                    'VALUES' => array(
                        1 => 'Total Unique Visitors (000)',
                        9 => '% Reach',
                        10 => '% Composition Unique Visitors',
                        70 => 'Composition Index UV',
                        71 => 'Composition Index PV',
                        7 => 'Average Daily Visitors (000)',
                        2 => 'Total Minutes (MM)',
                        14 => 'Average Minutes per Usage Day',
                        3 => 'Total Pages Viewed (MM)',
                        16 => 'Average Pages per Usage Day',
                        15 => 'Average Minutes per Page',
                        8 => 'Average Usage Days per Visitor',
                        6 => 'Average Minutes per Visitor',
                        5 => 'Average Pages per Visitor',
                        11 => '% Composition Pages',
                        12 => '% Composition Minutes',
                        143 => 'Total Visits (000)',
                        144 => 'Average Minutes per Visit',
                        145 => 'Average Visits per Visitor',
                        146 => 'Average Visits per Usage Day',
                        274 => 'Average Pages Per Visit'
                    )*/
                )
            ),
            'nestingOptions' => array(
                'LABEL' => 'Nesting Options',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'miscDataOption' => array(
                'LABEL' => 'Misc Data Option',
                'REQUIRED' => false,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    0 => 'Non-YT',
                    1 => 'YT'
                )
            )
        ),
        'FIELDS' => array(),
        'PERMISSIONS' => array(),
        'SOURCE' => array(
            'STORAGE' => array(
                'comscore' => array(
                    'NAME' => 'model\\ads\\Comscore',
                    'CLASS' => 'model\\ads\\Comscore\\serializers\\ComscoreSerializer',
                    'DEFINITION' => array(
                        'BASE' => 'ON ComscoreDemographics CALL KeyMeasures WITH (
            [%dataSource:dataSource="{%dataSource%}",%]
            [%geo:geo="{%geo%}",%]
            [%loc:loc="{%loc%}",%]
            [%timeType:timeType="{%timeType%}",%]
            [%timePeriod:timePeriod="{%timePeriod%}",%]
            [%targetType:targetType="{%targetType%}",%]
            [%targetGroup:targetGroup="{%targetGroup%}",%]
            [%universeTypeId:universeTypeId="{%universeTypeId%}",%]
            [%targetCustom:targetCustom="{%targetCustom%}",%]
            [%target:target="{%target%}",%]
            [%mediaSetType:mediaSetType="{%mediaSetType%}",%]
            [%mediaSet:mediaSet="{%mediaSet%}",%]
            [%excludeMediaLevel:excludeMediaLevel="{%excludeMediaLevel%}",%]
            [%media:media="{%media%}",%]
            [%attributeId:attributeId="{%attributeId%}",%]
            [%attributeLogic:attributeLogic="{%attributeLogic%}",%]
            [%measure:measure="{%measure%}",%]
            [%nestingOptions:nestingOptions="{%nestingOptions%}",%]
            [%miscDataOption:miscDataOption="{%miscDataOption%}",%])',
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
                                'FILTER' => '[%loc%]',
                                'TRIGGER_VAR' => 'loc'
                            ),
                            3 => array(
                                'FILTER' => '[%timeType%]',
                                'TRIGGER_VAR' => 'timeType'
                            ),
                            4 => array(
                                'FILTER' => '[%timePeriod%]',
                                'TRIGGER_VAR' => 'timePeriod'
                            ),
                            5 => array(
                                'FILTER' => '[%targetType%]',
                                'TRIGGER_VAR' => 'targetType'
                            ),
                            6 => array(
                                'FILTER' => '[%targetGroup%]',
                                'TRIGGER_VAR' => 'targetGroup'
                            ),
                            7 => array(
                                'FILTER' => '[%universeTypeId%]',
                                'TRIGGER_VAR' => 'universeTypeId'
                            ),
                            8 => array(
                                'FILTER' => '[%targetCustom%]',
                                'TRIGGER_VAR' => 'targetCustom'
                            ),
                            9 => array(
                                'FILTER' => '[%target%]',
                                'TRIGGER_VAR' => 'target'
                            ),
                            10 => array(
                                'FILTER' => '[%mediaSetType%]',
                                'TRIGGER_VAR' => 'mediaSetType'
                            ),
                            11 => array(
                                'FILTER' => '[%mediaSet%]',
                                'TRIGGER_VAR' => 'mediaSet'
                            ),
                            12 => array(
                                'FILTER' => '[%excludeMediaLevel%]',
                                'TRIGGER_VAR' => 'excludeMediaLevel'
                            ),
                            13 => array(
                                'FILTER' => '[%media%]',
                                'TRIGGER_VAR' => 'media'
                            ),
                            14 => array(
                                'FILTER' => '[%attributeId%]',
                                'TRIGGER_VAR' => 'attributeId'
                            ),
                            15 => array(
                                'FILTER' => '[%attributeLogic%]',
                                'TRIGGER_VAR' => 'attributeLogic'
                            ),
                            16 => array(
                                'FILTER' => '[%measure%]',
                                'TRIGGER_VAR' => 'measure'
                            ),
                            17 => array(
                                'FILTER' => '[%nestingOptions%]',
                                'TRIGGER_VAR' => 'nestingOptions'
                            ),
                            18 => array(
                                'FILTER' => '[%miscDataOption%]',
                                'TRIGGER_VAR' => 'miscDataOption'
                            )
                        )
                    )
                )
            )
        )
    );
}