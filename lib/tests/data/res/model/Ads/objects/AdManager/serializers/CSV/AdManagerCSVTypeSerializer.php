<?php
/**
 * Class AdManagerTypeSerializer
 * @package model\ads\objects\AdManager\serializers
 *  (c) Smartclip
 */
namespace model\Ads\AdManager\serializers\CSV;



class AdManagerCSVTypeSerializer extends \lib\storage\TypeSerializer
{
    function __construct($parameters)
    {
        $parameters["columnMap"]=[
            "Date"=>"DATE",
            "Ad unit"=>"AD_UNIT_NAME",
            "Hour"=>"HOUR",
            "Ad unit ID"=>"AD_UNIT_ID",
            "Total impressions"=>"AD_SERVER_IMPRESSIONS",
            "Total CPM and CPC revenue (€)"=>"AD_SERVER_CPM_AND_CPC_REVENUE",
            "Total CTR"=>"TOTAL_LINE_ITEM_LEVEL_CTR",
            "Ad Exchange impressions"=>"AD_EXCHANGE_IMPRESSIONS",
            "Ad Exchange revenue (€)"=>"AD_EXCHANGE_ESTIMATED_REVENUE"
        ];
        parent::__construct($parameters,"ADMANAGER_CSV");
    }
    function getTypeNamespace()
    {
        return '\model\Ads\AdManager\serializers\CSV\types';
    }
    function includeTypeSerializer($className,$forType)
    {
        $target=__DIR__."/types/".$forType.".php";
        if(!is_file($target))
            return false;
        include_once($target);
        return true;
    }

}
