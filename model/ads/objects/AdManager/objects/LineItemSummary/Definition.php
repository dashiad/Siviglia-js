<?php
namespace model\ads\AdManager\LineItemSummary;


class Definition extends \lib\model\BaseModelDefinition
{
    function __construct()
    {
        parent::__construct(Definition::$definition);

    }

    static $definition = array(
        'ROLE'=>'ENTITY',
        'DEFAULT_SERIALIZER'=>'web',
        'DEFAULT_WRITE_SERIALIZER'=>'web',
        'INDEXFIELDS'=>array('id'),
        'TABLE'=>'lineitemsummary',
        'LABEL'=>'LineItemSummary',
        'SHORTLABEL'=>'LineItem',
        'CARDINALITY'=>'3000',
        'CARDINALITY_TYPE'=>'FIXED',
        "FIELDS"=>[
            "orderId"=>["LABEL"=>"orderId","TYPE"=>"Integer"],
            "id"=>["LABEL"=>"id","TYPE"=>"Integer"],
            "name"=>["LABEL"=>"name","TYPE"=>"String","MAXLENGTH"=>255],
            "externalId"=>["LABEL"=>"externalId","TYPE"=>"String","MAXLENGTH"=>255],
            "orderName"=>["LABEL"=>"orderName","TYPE"=>"String","MAXLENGTH"=>255],
            "startDateTime"=>["LABEL"=>"startDateTime","TYPE"=>"DateTime"],
            "startDateTime_timeZoneId"=>["LABEL"=>"startDateTime_timeZoneId","TYPE"=>"String","MAXLENGTH"=>40],
            "startDateTimeType"=>["LABEL"=>"startDateTimeType","TYPE"=>"Enum","VALUES"=>['USE_START_DATE_TIME','IMMEDIATELY','ONE_HOUR_FROM_NOW','UNKNOWN']],
            "endDateTime"=>["LABEL"=>"endDateTime","TYPE"=>"DateTime"],
            "endDateTime_timeZoneId"=>["LABEL"=>"endDateTime_timeZoneId","TYPE"=>"String","MAXLENGTH"=>40],
            "autoExtensionDays"=>["LABEL"=>"autoExtensionDays","TYPE"=>"Integer"],
            "unlimitedEndDateTime"=>["LABEL"=>"unlimitedEndDateTime","TYPE"=>"Boolean"],
            "creativeRotationType"=>["LABEL"=>"creativeRotationType","TYPE"=>"Enum","VALUES"=>['EVEN','OPTIMIZED','MANUAL','SEQUENTIAL']],
            "deliveryRateType"=>["LABEL"=>"deliveryRateType","TYPE"=>"Enum","VALUES"=>['EVENLY','FRONTLOADED','AS_FAST_AS_POSSIBLE']],
            "deliveryForecastSource"=>["LABEL"=>"deliveryForecastSource","TYPE"=>"Enum","VALUES"=>['HISTORICAL','FORECASTING','UNKNOWN']],
            "roadblockingType"=>["LABEL"=>"roadblockingType","TYPE"=>"Enum","VALUES"=>['ONLY_ONE','ONE_OR_MORE','AS_MANY_AS_POSSIBLE','ALL_ROADBLOCK','CREATIVE_SET']],
            "lineItemType"=>["LABEL"=>"lineItemType","TYPE"=>"Enum","VALUES"=>['SPONSORSHIP','STANDARD','NETWORK','BULK','PRICE_PRIORITY','HOUSE','LEGACY_DFP','CLICK_TRACKING','ADSENSE','AD_EXCHANGE','BUMPER','ADMOB','PREFERRED_DEAL','UNKNOWN']],
            "priority"=>["LABEL"=>"priority","TYPE"=>"Integer"],
            "costPerUnit"=>["LABEL"=>"costPerUnit","TYPE"=>"Decimal","NINTEGERS"=>14,"NDECIMALS"=>4],
            "costPerUnit_currencyCode"=>["LABEL"=>"costPerUnit_currencyCode","TYPE"=>"String","MAXLENGTH"=>4],
            "valueCostPerUnit"=>["LABEL"=>"valueCostPerUnit","TYPE"=>"Decimal","NINTEGERS"=>14,"NDECIMALS"=>4],
            "valueCostPerUnit_currencyCode"=>["LABEL"=>"valueCostPerUnit_currencyCode","TYPE"=>"String","MAXLENGTH"=>4],
            "costType"=>["LABEL"=>"costType","TYPE"=>"Enum","VALUES"=>['CPA','CPC','CPD','CPM','VCPM','UNKNOWN']],
            "discountType"=>["LABEL"=>"discountType","TYPE"=>"Enum","VALUES"=>['ABSOLUTE_VALUE','PERCENTAGE']],
            "discount"=>["LABEL"=>"discount","TYPE"=>"Decimal","NINTEGERS"=>12,"NDECIMALS"=>4],
            "contractedUnitsBought"=>["LABEL"=>"contractedUnitsBought","TYPE"=>"Integer"],
            "environmentType"=>["LABEL"=>"environmentType","TYPE"=>"Enum","VALUES"=>['BROWSER','VIDEO_PLAYER']],
            "companionDeliveryOption"=>["LABEL"=>"companionDeliveryOption","TYPE"=>"Enum","VALUES"=>['OPTIONAL','AT_LEAST_ONE','ALL','UNKNOWN']],
            "allowOverbook"=>["LABEL"=>"allowOverbook","TYPE"=>"Boolean"],
            "skipInventoryCheck"=>["LABEL"=>"skipInventoryCheck","TYPE"=>"Boolean"],
            "skipCrossSellingRuleWarningChecks"=>["LABEL"=>"skipCrossSellingRuleWarningChecks","TYPE"=>"Boolean"],
            "reserveAtCreation"=>["LABEL"=>"reserveAtCreation","TYPE"=>"Boolean"],
            "budget"=>["LABEL"=>"budget","TYPE"=>"Decimal","NINTEGERS"=>14,"NDECIMALS"=>4],
            "budget_currencyCode"=>["LABEL"=>"budget_currencyCode","TYPE"=>"String","MAXLENGTH"=>4],
            "status"=>["LABEL"=>"status","TYPE"=>"Enum","VALUES"=>['DELIVERY_EXTENDED','DELIVERING','READY','PAUSED','INACTIVE','PAUSED_INVENTORY_RELEASED','PENDING_APPROVAL','COMPLETED','DISAPPROVED','DRAFT','CANCELED']],
            "reservationStatus"=>["LABEL"=>"reservationStatus","TYPE"=>"Enum","VALUES"=>['RESERVED','UNRESERVED']],
            "isArchived"=>["LABEL"=>"isArchived","TYPE"=>"Boolean"],
            "webPropertyCode"=>["LABEL"=>"webPropertyCode","TYPE"=>"String","MAXLENGTH"=>255],
            "disableSameAdvertiserCompetitiveExclusion"=>["LABEL"=>"disableSameAdvertiserCompetitiveExclusion","TYPE"=>"Boolean"],
            "lastModifiedByApp"=>["LABEL"=>"lastModifiedByApp","TYPE"=>"String","MAXLENGTH"=>255],
            "notes"=>["LABEL"=>"notes","TYPE"=>"String","MAXLENGTH"=>255],
            "lastModifiedDateTime"=>["LABEL"=>"lastModifiedDateTime","TYPE"=>"DateTime"],
            "lastModifiedDateTime_timeZoneId"=>["LABEL"=>"lastModifiedDateTime_timeZoneId","TYPE"=>"String","MAXLENGTH"=>40],
            "creationDateTime"=>["LABEL"=>"creationDateTime","TYPE"=>"DateTime"],
            "creationDateTime_timeZoneId"=>["LABEL"=>"creationDateTime_timeZoneId","TYPE"=>"String","MAXLENGTH"=>40],
            "isPrioritizedPreferredDealsEnabled"=>["LABEL"=>"isPrioritizedPreferredDealsEnabled","TYPE"=>"Boolean"],
            "adExchangeAuctionOpeningPriority"=>["LABEL"=>"adExchangeAuctionOpeningPriority","TYPE"=>"Integer"],
            "isSetTopBoxEnabled"=>["LABEL"=>"isSetTopBoxEnabled","TYPE"=>"Boolean"],
            "isMissingCreatives"=>["LABEL"=>"isMissingCreatives","TYPE"=>"Boolean"],
            "programmaticCreativeSource"=>["LABEL"=>"programmaticCreativeSource","TYPE"=>"Enum","VALUES"=>['PUBLISHER','ADVERTISER','UNKNOWN']],
            "videoMaxDuration"=>["LABEL"=>"videoMaxDuration","TYPE"=>"Integer"],
            "viewabilityProviderCompanyId"=>["LABEL"=>"viewabilityProviderCompanyId","TYPE"=>"Integer"],
            "userConsentEligibility"=>["LABEL"=>"userConsentEligibility","TYPE"=>"Enum","VALUES"=>['NONE','CONSENTED_OR_NPA','CONSENTED_ONLY','UNKNOWN']],
            "childContentEligibility"=>["LABEL"=>"childContentEligibility","TYPE"=>"Enum","VALUES"=>['UNKNOWN','DISALLOWED','ALLOWED']],
            "_remoteTable"=>["LABEL"=>"_remoteTable","TYPE"=>"String","MAXLENGTH"=>40],
            "remoteId"=>["LABEL"=>"remoteId","TYPE"=>"String"],
            "_remoteField"=>["LABEL"=>"_remoteField","TYPE"=>"String","MAXLENGTH"=>40]
        ]
    );
}
