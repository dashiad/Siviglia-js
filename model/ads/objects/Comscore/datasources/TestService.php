<?php
namespace model\ads\Comscore\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/Comscore/datasources/DemographicsTestCall.php
 CLASS:Definition
*
*
**/

class TestService
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table', 
        'PARAMS' => [
            'message' => [
                'TYPE' => 'String',
		'LABEL' => 'Mensaje',
		'REQUIRED'=>'true',
            ],
        ],
        'FIELDS' => [
            // TODO: revisar formato de cada campo
            'DemographicId' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DemographicId',
            ],
            'DemographicName' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DemographicName',
            ],
            'DemographicMemberId' => [
                'TYPE' => 'Integer',
            ],
            'DemographicMemberName' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DemographicMemberId',
            ],
            'GroupId'  => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'GroupId',
            ],
            'GroupName'  => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'GroupName',
            ],
            'GroupType' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'GroupType',
            ],
            'Id' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'Id',
            ],
            'DetailName' => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'DetailName',
            ],
            "TotalDigitalPercentUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalPercentUniqueViewers',
            ],
            "TotalDigitalUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalUniqueViewers',
            ],
            "TotalDigitalPopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalPopulationReach',
            ],
            "TotalDigitalImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalImpressions',
            ],
            "TotalDigitalPercentImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalPercentImpressions',
            ],
            "TotalDigitalAverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalAverageFrequency',
            ],
            "TotalDigitalGrp" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalGrp',
            ],
            "TotalDigitalCompositionIndexUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalCompositionIndexUniqueViewers',
            ],
            "TotalDigitalValidatedUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalValidatedUniqueViewers',
            ],
            "TotalDigitalPercentValidatedUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalPercentValidatedUniqueViewers',
            ],
            "TotalDigitalCompositionValidatedIndexUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalCompositionValidatedIndexUniqueViewers',
            ],
            "TotalDigitalValidatedPopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalValidatedPopulationReach',
            ],
            "TotalDigitalValidatedImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalValidatedImpressions',
            ],
            "TotalDigitalPercentValidatedImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalPercentValidatedImpressions',
            ],
            "TotalDigitalValidatedAverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalValidatedAverageFrequency',
            ],
            "TotalDigitalValidatedGrp" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalDigitalValidatedGrp',
            ],
            "UniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'UniqueViewers',
            ],
            "PercentUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentUniqueViewers',
            ],
            "CompositionIndexUV" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'CompositionIndexUV',
            ],
            "PercentInternetReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentInternetReach',
            ],
            "PercentPopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentPopulationReach',
            ],
            "TotalImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'TotalImpressions',
            ],
            "PercentImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'PercentImpressions',
            ],
            "AverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'AverageFrequency',
            ],
            "GRP" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'GRP',
            ],
            "ValidatedGRP" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedGRP',
            ],
            "ValidatedAverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedAverageFrequency',
            ],
            "ValidatedImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedImpressions',
            ],
            "ValidatedCompositionIndexUV" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedCompositionIndexUV',
            ],
            "ValidatedPopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedPopulationReach',
            ],
            "ValidatedUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedUniqueViewers',
            ],
            "ValidatedPercentUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedPercentUniqueViewers',
            ],
            "ValidatedPercentImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'ValidatedPercentImpressions',
            ],
            "MobileUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileUniqueViewers',
            ],
            "MobileImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileImpressions',
            ],
            "MobileGrp" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileGrp',
            ],
            "MobileAverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileAverageFrequency',
            ],
            "MobilePercentUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobilePercentUniqueViewers',
            ],
            "MobilePercentImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobilePercentImpressions',
            ],
            "MobilePopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobilePopulationReach',
            ],
            "MobileCompositionIndexUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileCompositionIndexUniqueViewers',
            ],
            "MobileValidatedUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileValidatedUniqueViewers',
            ],
            "MobileValidatedImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileValidatedImpressions',
            ],
            "MobileValidatedGrp" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileValidatedGrp',
            ],
            "MobileValidatedAverageFrequency" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileValidatedAverageFrequency',
            ],
            "MobilePercentValidatedUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobilePercentValidatedUniqueViewers',
            ],
            "MobilePercentValidatedImpressions" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobilePercentValidatedImpressions',
            ],
            "MobileValidatedPopulationReach" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileValidatedPopulationReach',
            ],
            "MobileCompositionValidatedIndexUniqueViewers" => [
                'MODEL' => '\model\ads\Comscore',
                'FIELD' => 'MobileCompositionValidatedIndexUniqueViewers',
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
                        'BASE'   => "ON ComscoreDemographics CALL TestService WITH (message='[%message%]') RETURN *",
                        'CONDITIONS' => [
                            [
                                'FILTER' => '[%message%]',
                                'TRIGGER_VAR'=> 'message',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
}


