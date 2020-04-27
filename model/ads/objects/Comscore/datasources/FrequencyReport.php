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
                'TYPE' => 'Enum',
                'LABEL' => 'Región',
                'VALUES' => ['spain', 'latam'],
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
                   'ACTION' => 'report',
                   'TYPE'   => 'Frequency',
               ],
           ],
        ],
    ];
}


