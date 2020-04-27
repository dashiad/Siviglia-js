<?php
namespace model\ads\Comscore;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/Comscore/Definition.php
 CLASS:Definition
 *
 *
 **/
use \lib\model\BaseModelDefinition;

class Definition extends BaseModelDefinition
{
    static $definition = [
        'FIELDS' => [
            // TODO: revisar formato de cada campo
            'DemographicId' => [
                'TYPE' => 'Integer',
            ],
            'DemographicName' => [
                'TYPE' => 'String',
            ],
            'DemographicMemberId' => [
                'TYPE' => 'Integer',
            ],
            'DemographicMemberName' => [
                'TYPE' => 'String',
            ],
            'GroupId'  => [
                'TYPE' => 'String',
            ],
            'GroupName'  => [
                'TYPE' => 'String',
            ],
            'GroupType' => [
                'TYPE' => 'String',
            ],
            'Id' => [
                'TYPE' => 'String',
            ],
            'DetailName' => [
                'TYPE' => 'String',
            ],
            "TotalDigitalPercentUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalPopulationReach" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalImpressions" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalPercentImpressions" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalAverageFrequency" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalGrp" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalCompositionIndexUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalValidatedUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalPercentValidatedUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalCompositionValidatedIndexUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalValidatedPopulationReach" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalValidatedImpressions" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalPercentValidatedImpressions" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalValidatedAverageFrequency" => [
                'TYPE' => 'String',
            ],
            "TotalDigitalValidatedGrp" => [
                'TYPE' => 'String',
            ],
            "UniqueViewers" => [
                'TYPE' => 'String',
            ],
            "PercentUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "CompositionIndexUV" => [
                'TYPE' => 'String',
            ],
            "PercentInternetReach" => [
                'TYPE' => 'String',
            ],
            "PercentPopulationReach" => [
                'TYPE' => 'String',
            ],
            "TotalImpressions" => [
                'TYPE' => 'String',
            ],
            "PercentImpressions" => [
                'TYPE' => 'String',
            ],
            "AverageFrequency" => [
                'TYPE' => 'String',
            ],
            "GRP" => [
                'TYPE' => 'String',
            ],
            "ValidatedGRP" => [
                'TYPE' => 'String',
            ],
            "ValidatedAverageFrequency" => [
                'TYPE' => 'String',
            ],
            "ValidatedImpressions" => [
                'TYPE' => 'String',
            ],
            "ValidatedCompositionIndexUV" => [
                'TYPE' => 'String',
            ],
            "ValidatedPopulationReach" => [
                'TYPE' => 'String',
            ],
            "ValidatedUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "ValidatedPercentUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "ValidatedPercentImpressions" => [
                'TYPE' => 'String',
            ],
            "MobileUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "MobileImpressions" => [
                'TYPE' => 'String',
            ],
            "MobileGrp" => [
                'TYPE' => 'String',
            ],
            "MobileAverageFrequency" => [
                'TYPE' => 'String',
            ],
            "MobilePercentUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "MobilePercentImpressions" => [
                'TYPE' => 'String',
            ],
            "MobilePopulationReach" => [
                'TYPE' => 'String',
            ],
            "MobileCompositionIndexUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "MobileValidatedUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "MobileValidatedImpressions" => [
                'TYPE' => 'String',
            ],
            "MobileValidatedGrp" => [
                'TYPE' => 'String',
            ],
            "MobileValidatedAverageFrequency" => [
                'TYPE' => 'String',
            ],
            "MobilePercentValidatedUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "MobilePercentValidatedImpressions" => [
                'TYPE' => 'String',
            ],
            "MobileValidatedPopulationReach" => [
                'TYPE' => 'String',
            ],
            "MobileCompositionValidatedIndexUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "ModifiedAt" => [
                'TYPE' => 'String',
            ],
            "DataMethodology" => [
                'TYPE' => 'String',
            ],
            "DataThrough" => [
                'TYPE' => 'String',
            ],
            "DataStatus" => [
                'TYPE' => 'String',
            ],
            "TimesExposed" => [
                'TYPE' => 'String',
            ],
            "PercentImpressions" => [
                'TYPE' => 'String',
           ],
            "TotalImpressions" => [
                'TYPE' => 'String',
            ],
            "PercentUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "UniqueViewers" => [
                'TYPE' => 'String',
            ],
            "PercentValidateImpressions" => [
                'TYPE' => 'String',
            ],
            "ValidateTotalImpressions" => [
                'TYPE' => 'String',
            ],
            "PercentValidateUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "ValidateUniqueViewers" => [
                'TYPE' => 'String',
            ],
            "ModifiedAt" => [
                'TYPE' => 'String',
            ],
            "DataMethodology" => [
                'TYPE' => 'String',
            ],
            "DataThrough" => [
                    'TYPE' => 'String',
                ],
            "DataStatus" => [
                'TYPE' => 'String',
            ],
            
        ],
    ];
}