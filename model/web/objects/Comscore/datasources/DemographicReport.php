<?php
namespace model\web\Comscore\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Comscore/datasources/DemographicReport.php
 CLASS:Definition
*
*
**/

class DemographicReport
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
            // TODO: revisar formato de cada campo
            'DemographicId' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DemographicId',
            ],
            'DemographicName' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DemographicName',
            ],
            'DemographicMemberId' => [
                'TYPE' => 'Integer',
            ],
            'DemographicMemberName' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DemographicMemberId',
            ],
            'GroupId'  => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'GroupId',
            ],
            'GroupName'  => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'GroupName',
            ],
            'GroupType' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'GroupType',
            ],
            'Id' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'Id',
            ],
            'DetailName' => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'DetailName',
            ],
            "TotalDigitalPercentUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalPercentUniqueViewers',
            ],
            "TotalDigitalUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalUniqueViewers',
            ],
            "TotalDigitalPopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalPopulationReach',
            ],
            "TotalDigitalImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalImpressions',
            ],
            "TotalDigitalPercentImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalPercentImpressions',
            ],
            "TotalDigitalAverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalAverageFrequency',
            ],
            "TotalDigitalGrp" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalGrp',
            ],
            "TotalDigitalCompositionIndexUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalCompositionIndexUniqueViewers',
            ],
            "TotalDigitalValidatedUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalValidatedUniqueViewers',
            ],
            "TotalDigitalPercentValidatedUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalPercentValidatedUniqueViewers',
            ],
            "TotalDigitalCompositionValidatedIndexUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalCompositionValidatedIndexUniqueViewers',
            ],
            "TotalDigitalValidatedPopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalValidatedPopulationReach',
            ],
            "TotalDigitalValidatedImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalValidatedImpressions',
            ],
            "TotalDigitalPercentValidatedImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalPercentValidatedImpressions',
            ],
            "TotalDigitalValidatedAverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalValidatedAverageFrequency',
            ],
            "TotalDigitalValidatedGrp" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalDigitalValidatedGrp',
            ],
            "UniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'UniqueViewers',
            ],
            "PercentUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentUniqueViewers',
            ],
            "CompositionIndexUV" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'CompositionIndexUV',
            ],
            "PercentInternetReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentInternetReach',
            ],
            "PercentPopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentPopulationReach',
            ],
            "TotalImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'TotalImpressions',
            ],
            "PercentImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'PercentImpressions',
            ],
            "AverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'AverageFrequency',
            ],
            "GRP" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'GRP',
            ],
            "ValidatedGRP" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedGRP',
            ],
            "ValidatedAverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedAverageFrequency',
            ],
            "ValidatedImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedImpressions',
            ],
            "ValidatedCompositionIndexUV" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedCompositionIndexUV',
            ],
            "ValidatedPopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedPopulationReach',
            ],
            "ValidatedUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedUniqueViewers',
            ],
            "ValidatedPercentUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedPercentUniqueViewers',
            ],
            "ValidatedPercentImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'ValidatedPercentImpressions',
            ],
            "MobileUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileUniqueViewers',
            ],
            "MobileImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileImpressions',
            ],
            "MobileGrp" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileGrp',
            ],
            "MobileAverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileAverageFrequency',
            ],
            "MobilePercentUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobilePercentUniqueViewers',
            ],
            "MobilePercentImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobilePercentImpressions',
            ],
            "MobilePopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobilePopulationReach',
            ],
            "MobileCompositionIndexUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileCompositionIndexUniqueViewers',
            ],
            "MobileValidatedUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileValidatedUniqueViewers',
            ],
            "MobileValidatedImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileValidatedImpressions',
            ],
            "MobileValidatedGrp" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileValidatedGrp',
            ],
            "MobileValidatedAverageFrequency" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileValidatedAverageFrequency',
            ],
            "MobilePercentValidatedUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobilePercentValidatedUniqueViewers',
            ],
            "MobilePercentValidatedImpressions" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobilePercentValidatedImpressions',
            ],
            "MobileValidatedPopulationReach" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileValidatedPopulationReach',
            ],
            "MobileCompositionValidatedIndexUniqueViewers" => [
                'MODEL' => '\model\web\Comscore',
                'FIELD' => 'MobileCompositionValidatedIndexUniqueViewers',
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
                'comscore' => [
                    'NAME'   => 'model\\web\\Comscore',
                    'CLASS'  => 'model\\web\\Comscore\\serializers\\ComscoreSerializer',
                    'ACTION' => 'report',
                    'TYPE'   => 'Demographic',
                ],
            ],
        ]
    ];
}


