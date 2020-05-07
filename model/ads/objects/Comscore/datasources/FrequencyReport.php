<?php
namespace model\ads\Comscore\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/Comscore/datasources/FrequencyReport.php
 CLASS:Definition
*
*
**/

class FrequencyReport
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'region' => [
                'TYPE' => 'String',
                'LABEL' => 'Región',
                'DEFAULT' => 'spain',
            ],
            'start_date' => [
                'TYPE'  => 'Date',
                'LABEL' => 'Fecha inicial',
            ],
            'end_date'   => [
                'TYPE' => 'Date',
                'LABEL' => 'Fecha final',
            ],
            'campaigns'  => [
                'TYPE' => 'Array',
                'LABEL' => 'Campañas',
                'ELEMENTS' => [
                    'TYPE' => 'String',
                ],
            ],
        ],
        'FIELDS' => [
            "TimesExposed" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TimesExposed',
            ],
            "PercentImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentImpressions',
            ],
            "TotalImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalImpressions',
            ],
            "PercentUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentUniqueViewers',
            ],
            "UniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'UniqueViewers',
            ],
            "PercentValidateImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentValidateImpressions',
            ],
            "ValidateTotalImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidateTotalImpressions',
            ],
            "PercentValidateUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentValidateUniqueViewers',
            ],
            "ValidateUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidateUniqueViewers',
            ],
            "ModifiedAt" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ModifiedAt',
            ],
            "DataMethodology" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DataMethodology',
            ],
            "DataThrough" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DataThrough',
            ],
            "DataStatus" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DataStatus',
            ],
        ],
        'PERMISSIONS' => [],
        'SOURCE'      => [
            'STORAGE' => [
                'comscore' => [
                    'NAME'   => 'model\\ads\\Comscore',
                    'CLASS'  => 'model\\ads\\Comscore\\serializers\\ComscoreSerializer',
                    'DEFINITION' => [
                        'BASE'   => "ON Comscore CALL requestReport WITH (type='Frequency', region='[%region%]', start_date='[%start_date%]', end_date='[%end_date%]', campaigns='[%campaigns%]') RETURN *",
                        'CONDITIONS' => [
                            [
                                'FILTER' => '[%region%]',
                                'TRIGGER_VAR'=> 'region',
                            ],
                            [
                                'FILTER' => '[%start_date%]',
                                'TRIGGER_VAR'=> 'start_date',
                            ],
                            [
                                'FILTER' => '[%end_date%]',
                                'TRIGGER_VAR'=> 'end_date',
                            ],
                            [
                                'FILTER' => '[%campaigns%]',
                                'TRIGGER_VAR'=> 'campaigns',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
}


