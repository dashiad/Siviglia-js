<?php
namespace model\web\Comscore\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Comscore/datasources/FrequencyReport.php
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
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TimesExposed',
            ],
            "PercentImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentImpressions',
            ],
            "TotalImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalImpressions',
            ],
            "PercentUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentUniqueViewers',
            ],
            "UniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'UniqueViewers',
            ],
            "PercentValidateImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentValidateImpressions',
            ],
            "ValidateTotalImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidateTotalImpressions',
            ],
            "PercentValidateUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentValidateUniqueViewers',
            ],
            "ValidateUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidateUniqueViewers',
            ],
            "ModifiedAt" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ModifiedAt',
            ],
            "DataMethodology" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DataMethodology',
            ],
            "DataThrough" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DataThrough',
            ],
            "DataStatus" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DataStatus',
            ],
        ],
        'PERMISSIONS' => [],
        'SOURCE'      => [
           'STORAGE' => [
               'COMSCORE' => [
                   'ACTION' => 'report',
                   'TYPE'   => 'Frequency',
               ],
           ],
        ],
    ];
}


