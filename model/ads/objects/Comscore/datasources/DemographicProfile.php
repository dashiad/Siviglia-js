<?php
namespace model\ads\Comscore\datasources;

class DemographicProfile
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
                'LABEL' => NULL,
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    724 => 'Spain'
                )
            ),
            'loc' => array(
                'LABEL' => 'Location',
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
            'targetGroupBase' => array(
                'LABEL' => 'Base Target Groups',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    318 => "Persons - Age",
                    319 => "Males - Age",
                    320 => "Females - Age",
                    186 => "Region",
                    71 => "Presence of Children"
                ),
            ),
            'targetBase' => array(
                'LABEL' => 'Base Target',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array (
                    1747 => 'Persons: 13+',
                    713 => 'Persons: 15+',
                    722 => 'Persons: 18+',
                    720 => 'Persons: 25+',
                    714 => 'Persons: 35+',
                    721 => 'Persons: 45+',
                    1972 => 'Persons: 4-12',
                    990 => 'Persons: 4-14',
                    1864 => 'Persons: 13-17',
                    1463 => 'Persons: 15-17',
                    716 => 'Persons: 15-24',
                    19 => 'Persons: 18-24',
                    717 => 'Persons: 25-34',
                    718 => 'Persons: 35-44',
                    719 => 'Persons: 45-54',
                    715 => 'Persons: 55+',
                    991 => 'All Males',
                    1755 => 'Males: 13+',
                    749 => 'Males: 15+',
                    732 => 'Males: 18+',
                    730 => 'Males: 25+',
                    724 => 'Males: 35+',
                    731 => 'Males: 45+',
                    1973 => 'Males: 4-12',
                    992 => 'Males: 4-14',
                    1860 => 'Males: 13-17',
                    1464 => 'Males: 15-17',
                    726 => 'Males: 15-24',
                    53 => 'Males: 18-24',
                    727 => 'Males: 25-34',
                    728 => 'Males: 35-44',
                    729 => 'Males: 45-54',
                    725 => 'Males: 55+',
                    993 => 'All Females',
                    1762 => 'Females: 13+',
                    750 => 'Females: 15+',
                    742 => 'Females: 18+',
                    740 => 'Females: 25+',
                    734 => 'Females: 35+',
                    741 => 'Females: 45+',
                    1974 => 'Females: 4-12',
                    994 => 'Females: 4-14',
                    1862 => 'Females: 13-17',
                    1465 => 'Females: 15-17',
                    736 => 'Females: 15-24',
                    87 => 'Females: 18-24',
                    737 => 'Females: 25-34',
                    738 => 'Females: 35-44',
                    739 => 'Females: 45-54',
                    735 => 'Females: 55+',
                    1819 => 'Region: Catalunya',
                    1820 => 'Region: Centro',
                    1821 => 'Region: Este',
                    1822 => 'Region: Madrid',
                    1823 => 'Region: Norte',
                    1824 => 'Region: Sur',
                    932 => 'Children:No',
                    931 => 'Children:Yes',
                ),
            ),
            'targetGroup' => array(
                'LABEL' => 'Target Groups',
                'REQUIRED' => true,
                'VALUES' => array(
                    318 => "Persons - Age",
                    319 => "Males - Age",
                    320 => "Females - Age",
                    186 => "Region",
                    71 => "Presence of Children"
                ),
            ),
            'target' => array(
                'LABEL' => 'Target',
                'REQUIRED' => false,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    'TYPE' => 'Enum',
                    'VALUES' => array (
                        -1 => 'All',
                        1747 => 'Persons: 13+',
                        713 => 'Persons: 15+',
                        722 => 'Persons: 18+',
                        720 => 'Persons: 25+',
                        714 => 'Persons: 35+',
                        721 => 'Persons: 45+',
                        1972 => 'Persons: 4-12',
                        990 => 'Persons: 4-14',
                        1864 => 'Persons: 13-17',
                        1463 => 'Persons: 15-17',
                        716 => 'Persons: 15-24',
                        19 => 'Persons: 18-24',
                        717 => 'Persons: 25-34',
                        718 => 'Persons: 35-44',
                        719 => 'Persons: 45-54',
                        715 => 'Persons: 55+',
                        991 => 'All Males',
                        1755 => 'Males: 13+',
                        749 => 'Males: 15+',
                        732 => 'Males: 18+',
                        730 => 'Males: 25+',
                        724 => 'Males: 35+',
                        731 => 'Males: 45+',
                        1973 => 'Males: 4-12',
                        992 => 'Males: 4-14',
                        1860 => 'Males: 13-17',
                        1464 => 'Males: 15-17',
                        726 => 'Males: 15-24',
                        53 => 'Males: 18-24',
                        727 => 'Males: 25-34',
                        728 => 'Males: 35-44',
                        729 => 'Males: 45-54',
                        725 => 'Males: 55+',
                        993 => 'All Females',
                        1762 => 'Females: 13+',
                        750 => 'Females: 15+',
                        742 => 'Females: 18+',
                        740 => 'Females: 25+',
                        734 => 'Females: 35+',
                        741 => 'Females: 45+',
                        1974 => 'Females: 4-12',
                        994 => 'Females: 4-14',
                        1862 => 'Females: 13-17',
                        1465 => 'Females: 15-17',
                        736 => 'Females: 15-24',
                        87 => 'Females: 18-24',
                        737 => 'Females: 25-34',
                        738 => 'Females: 35-44',
                        739 => 'Females: 45-54',
                        735 => 'Females: 55+',
                        1819 => 'Region: Catalunya',
                        1820 => 'Region: Centro',
                        1821 => 'Region: Este',
                        1822 => 'Region: Madrid',
                        1823 => 'Region: Norte',
                        1824 => 'Region: Sur',
                        932 => 'Children:No',
                        931 => 'Children:Yes',
                    ),
                )
            ),
            'universeTypeId' => array(
                'LABEL' => 'TargetUniverseType',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'targetType' => array(
                'LABEL' => 'Target Type',
                'REQUIRED' => true,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    0 => 'Simple',
                    //2 => 'Custom' // no da ninguna opción válida
                )
            ),
            'targetCustom' => array(
                'LABEL' => 'Custom Target',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'mediaSetType' => array(
                'LABEL' => 'Used for media queries only, not required by report.',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'media' => array(
                'LABEL' => 'Media',
                'REQUIRED' => false,
                'TYPE' => 'String'
            ),
            'measure' => array(
                'LABEL' => 'Measures',
                'REQUIRED' => true,
                'TYPE' => 'Array',
                'ELEMENTS' => array(
                    // 'TYPE' => 'Enum',
                    'TYPE' => 'String',
                    'VALUES' => array(
                        73 => 'Target Audience (000)',
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
                    )
                )
            ),
            'nestingOptions' => array(
                'LABEL' => 'Nesting Options',
                'REQUIRED' => false,
                'TYPE' => 'Enum',
                'VALUES' => array(
                    0 => 'Measures/Media',
                    1 => 'Media/Measures'
                )
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
                        'BASE' => 'ON ComscoreDemographics CALL DemographicProfile WITH (
            [%dataSource:dataSource="{%dataSource%}",%]
            [%geo:geo="{%geo%}",%]
            [%loc:loc="{%loc%}",%]
            [%timeType:timeType="{%timeType%}",%]
            [%timePeriod:timePeriod="{%timePeriod%}",%]
            [%targetGroupBase:targetGroupBase="{%targetGroupBase%}",%]
            [%targetBase:targetBase="{%targetBase%}",%]
            [%targetGroup:targetGroup="{%targetGroup%}",%]
            [%target:target="{%target%}",%]
            [%universeTypeId:universeTypeId="{%universeTypeId%}",%]
            [%targetType:targetType="{%targetType%}",%]
            [%targetCustom:targetCustom="{%targetCustom%}",%]
            [%mediaSetType:mediaSetType="{%mediaSetType%}",%]
            [%media:media="{%media%}",%]
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
                                'FILTER' => '[%targetGroupBase%]',
                                'TRIGGER_VAR' => 'targetGroupBase'
                            ),
                            6 => array(
                                'FILTER' => '[%targetBase%]',
                                'TRIGGER_VAR' => 'targetBase'
                            ),
                            7 => array(
                                'FILTER' => '[%targetGroup%]',
                                'TRIGGER_VAR' => 'targetGroup'
                            ),
                            8 => array(
                                'FILTER' => '[%target%]',
                                'TRIGGER_VAR' => 'target'
                            ),
                            9 => array(
                                'FILTER' => '[%universeTypeId%]',
                                'TRIGGER_VAR' => 'universeTypeId'
                            ),
                            10 => array(
                                'FILTER' => '[%targetType%]',
                                'TRIGGER_VAR' => 'targetType'
                            ),
                            11 => array(
                                'FILTER' => '[%targetCustom%]',
                                'TRIGGER_VAR' => 'targetCustom'
                            ),
                            12 => array(
                                'FILTER' => '[%mediaSetType%]',
                                'TRIGGER_VAR' => 'mediaSetType'
                            ),
                            13 => array(
                                'FILTER' => '[%media%]',
                                'TRIGGER_VAR' => 'media'
                            ),
                            14 => array(
                                'FILTER' => '[%measure%]',
                                'TRIGGER_VAR' => 'measure'
                            ),
                            15 => array(
                                'FILTER' => '[%nestingOptions%]',
                                'TRIGGER_VAR' => 'nestingOptions'
                            ),
                            16 => array(
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
