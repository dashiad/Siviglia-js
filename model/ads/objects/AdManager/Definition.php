<?php
namespace model\ads\AdManager;
include_once(LIBPATH . "/data/DataTransform.php");
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 02/09/2016
 * Time: 13:30
 */


class Definition extends \lib\model\BaseModelDefinition
{
    function __construct()
    {
        parent::__construct(Definition::$definition);

    }

    static $definition = array(
        "tables" => array(
            "Default" => array(
                "label" => "Query por defecto",
                "fields"=>array (
                    "MONTH_AND_YEAR" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            'fieldgroup' => 'DATE_TIME',
                            'description' => 'Breaks down reporting data by month and year in the network time zone. Can
                    be used to filter on month using ISO 4601 format \'YYYY-MM\'.',
                            "TYPE" => 'String',
                        ),
                    "WEEK" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            'fieldgroup' => 'DATE_TIME',
                            'description' => 'Breaks down reporting data by week of the year in the network time zone.
                    Cannot be used for filtering.
                ',
                            "TYPE" => 'Integer',
                        ),
                    "DATE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            'fieldgroup' => 'DATE_TIME',
                            'description' => 'Breaks down reporting data by date in the network time zone. Can be used to
                    filter by date using ISO 8601\'s format \'YYYY-MM-DD\'".
                ',
                            "TYPE" => 'Date',
                        ),
                    "DAY" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            'fieldgroup' => 'DATE_TIME',
                            'description' => 'Breaks down reporting data by day of the week in the network time zone. Can
                    be used to filter by day of the week using the index of the day (from 1 for
                    Monday is 1 to 7 for Sunday).
                ',
                            "TYPE" => 'Integer',
                        ),
                    "HOUR" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'DATE_TIME',
                            'description' => 'Breaks down reporting data by hour of the day in the network time zone. Can
                    be used to filter by hour of the day (from 0 to 23).
                ',
                        ),
                    "LINE_ITEM_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'LINE_ITEM',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#id">LineItem.id</a>. Can be used to
                    filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#id">LineItem.id</a>.
                ',
                        ),
                    "LINE_ITEM_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'LINE_ITEM',
                            'description' => 'Breaks down reporting data by line item. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#name">LineItem.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#id">LineItem.id</a> are automatically included as columns in the report.
                    Can be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#name">LineItem.name</a>.
                ',
                        ),
                    "LINE_ITEM_TYPE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'LINE_ITEM',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html#lineItemType">LineItem.lineItemType</a>. Can be used
                    to filter by line item type using <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProductTemplateService.LineItemType.html">LineItemType</a> enumeration names.
                ',
                        ),
                    "ORDER_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'ORDER',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/OrderService.Order.html#id">Order.id</a>. Can be used to filter by
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/OrderService.Order.html#id">Order.id</a>.
                ',
                        ),
                    "ORDER_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'ORDER',
                            'description' => 'Breaks down reporting data by order. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/OrderService.Order.html#name">Order.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/OrderService.Order.html#id">Order.id</a> are automatically included as columns in the report. Can
                    be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/OrderService.Order.html#name">Order.name</a>.
                ',
                        ),
                    "ORDER_DELIVERY_STATUS" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'ORDER',
                            'description' => 'Delivery status of the order. Not available as a dimension to report on,
                    but exists as a dimension in order to filter on it using PQL.
                    Valid values are \'STARTED\', \'NOT_STARTED\' and \'COMPLETED\'.
                ',
                        ),
                    "ADVERTISER_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'ADVERTISER',
                            'description' => 'Breaks down reporting data by advertising company <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CompanyService.Company.html#id">Company.id</a>. Can
                    be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CompanyService.Company.html#id">Company.id</a>.
                ',
                        ),
                    "ADVERTISER_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'ADVERTISER',
                            'description' => 'Breaks down reporting data by advertising company. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CompanyService.Company.html#name">Company.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CompanyService.Company.html#id">Company.id</a> are automatically included as columns in the report.
                    Can be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CompanyService.Company.html#name">Company.name</a>.
                ',
                        ),
                    "AD_NETWORK_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'AD_NETWORK',
                            'description' => '
                    The network that provided the ad for SDK ad mediation.
                    <p>If selected for a report, that report will include only SDK
                        mediation ads and will not contain non-SDK mediation ads.</p>
                    <p>SDK mediation ads are ads for mobile devices. They have a list
                        of ad networks which can provide ads to serve. Not every ad network
                        will have an ad to serve so the device will try each network
                        one-by-one until it finds an ad network with an ad to serve. The ad
                        network that ends up serving the ad will appear here. Note that
                        this id does not correlate to anything in the companies table and
                        is not the same id as is served by <a class="codelink" href="#ADVERTISER_ID">ADVERTISER_ID</a>.</p>
                ',
                        ),
                    "AD_NETWORK_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'AD_NETWORK',
                            'description' => 'The name of the network defined in <a class="codelink" href="#AD_NETWORK_ID">AD_NETWORK_ID</a>.
                ',
                        ),
                    "CREATIVE_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CREATIVE',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#id">Creative.id</a> or creative set id
                    (master\'s <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#id">Creative.id</a>) if the creative is part of a creative set.
                    Can be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#id">Creative.id</a>.
                ',
                        ),
                    "CREATIVE_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CREATIVE',
                            'description' => 'Breaks down reporting data by creative. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#name">Creative.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#id">Creative.id</a> are automatically included as columns in the report.
                    Can be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#name">Creative.name</a>.',
                        ),
                    "CREATIVE_TYPE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CREATIVE',
                            'description' => 'Breaks down reporting data by creative type.',
                        ),
                    "CREATIVE_BILLING_TYPE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CREATIVE',
                            'description' => 'Breaks down reporting data by creative billing type.',
                        ),
                    "CUSTOM_EVENT_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CUSTOM_EVENT',
                            'description' => 'Breaks down reporting data by custom event ID.',
                        ),
                    "CUSTOM_EVENT_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CUSTOM_EVENT',
                            'description' => 'Breaks down reporting data by custom event name.',
                        ),
                    "CUSTOM_EVENT_TYPE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CUSTOM_EVENT',
                            'description' => 'Breaks down reporting data by custom event type (timer/exit/counter).',
                        ),
                    "CREATIVE_SIZE" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CREATIVE',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CreativeService.Creative.html#size">Creative.size</a>. Cannot be used for
                    filtering.',
                        ),
                    "AD_UNIT_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'AD_UNIT',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#id">AdUnit.id</a>. Can be used to filter by
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#id">AdUnit.id</a>. <a class="codelink" href="#AD_UNIT_NAME">AD_UNIT_NAME</a>, i.e. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#name">AdUnit.name</a>, is
                    automatically included as a dimension in the report.',
                        ),
                    "AD_UNIT_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'AD_UNIT',
                            'description' => 'Breaks down reporting data by ad unit. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#name">AdUnit.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#id">AdUnit.id</a> are automatically included as columns in the report. Can
                    be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/InventoryService.AdUnit.html#name">AdUnit.name</a>.',
                        ),
                    "PLACEMENT_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'PLACEMENT',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#id">Placement.id</a>. Can be used to filter
                    by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#id">Placement.id</a>.',
                        ),
                    "PLACEMENT_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'PLACEMENT',
                            'description' => 'Breaks down reporting data by placement. <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#name">Placement.name</a> and
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#id">Placement.id</a> are automatically included as columns in the report.
                    Can be used to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#name">Placement.name</a>.',
                        ),
                    "PLACEMENT_STATUS" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'PLACEMENT',
                            'description' => 'Status of the placement. Not available as a dimension to report on, but
                    exists as a dimension in order to filter on it using PQL. Can be used to
                    filter on <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.Placement.html#status">Placement.status</a> by using <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/PlacementService.InventoryStatus.html">InventoryStatus</a>
                    enumeration names.',
                        ),
                    "TARGETING" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'description' => 'Breaks down reporting data by criteria predefined by DoubleClick For
                    Publishers like the operating system, browser etc. Cannot be used for
                    filtering.',
                        ),
                    "DEVICE_CATEGORY_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            'fieldgroup' => 'DEVICE',
                            "TYPE" => 'String',
                            'description' => 'The ID of the device category to which an ad is being targeted.
                    Can be used to filter by device category ID.',
                        ),
                    "DEVICE_CATEGORY_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'DEVICE',
                            'description' => 'The category of device (smartphone, feature phone, tablet, or desktop) to which an ad is being
                    targeted.

                    Can be used to filter by device category name.
                ',
                        ),
                    "COUNTRY_NAME" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'GEO',
                            'description' => 'Breaks down reporting data by country name. The country name and the
                    country criteria ID are automatically included as columns in the report.
                    Can be used to filter by country name using the US English name.',
                        ),
                    "CUSTOM_TARGETING_VALUE_ID" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'CUSTOM_TARGETING',
                            'description' => 'Breaks down reporting data by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CustomTargetingService.CustomTargetingValue.html#id">CustomTargetingValue.id</a>. Can be used
                    to filter by <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/CustomTargetingService.CustomTargetingValue.html#id">CustomTargetingValue.id</a>.
                ',
                        ),
                    "AD_REQUEST_AD_UNIT_SIZES" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'fieldgroup' => 'AD_REQUEST',
                            'description' => '
                    Breaks down reporting data by the ad unit sizes specified in ad
                    requests.
                    <p>Formatted as comma separated values, e.g.
                        "300x250,300x250v,300x60".</p>
                    <p>This dimension is supported only for sell-through columns.</p>',
                        ),
                    "IS_FIRST_LOOK_DEAL" =>
                        array (
                            'groupable' => true,
                            'filtrable' => true,
                            "TYPE" => 'String',
                            'description' => 'Whether the report contains only Ad Exchange traffic fulfilled by First Look Deals
                    or omits it. If this filter isn\'t included, the report will include
                    First Look Deals traffic in addition to any other traffic.
                    Not available as a dimension to report on.',
                        ),
                    "AD_SERVER_IMPRESSIONS" =>
                        array (
                            'description' => 'The number of impressions delivered by the ad server.',
                            'fieldgroup' => 'AD_SERVER',
                            "TYPE" => 'Integer',
                        ),
                    "AD_SERVER_TARGETED_IMPRESSIONS" =>
                        array (
                            'description' => 'The number of impressions delivered by the ad server by explicit custom criteria targeting.',
                            'fieldgroup' => 'AD_SERVER',
                            "TYPE" => 'Integer',
                        ),
                    "AD_SERVER_CLICKS" =>
                        array (
                            'description' => 'The number of clicks delivered by the ad server.',
                            'fieldgroup' => 'AD_SERVER',
                            "TYPE" => 'Integer',
                        ),
                    "AD_SERVER_TARGETED_CLICKS" =>
                        array (
                            'description' => 'The number of clicks delivered by the ad server by explicit custom criteria targeting.',
                            'fieldgroup' => 'AD_SERVER',
                            "TYPE" => 'Integer',
                        ),
                    "AD_SERVER_CTR" =>
                        array (
                            'description' => 'The CTR for an ad delivered by the ad server.',
                            'fieldgroup' => 'AD_SERVER',
                            "TYPE" => 'Float',
                        ),
                    "AD_SERVER_CPM_AND_CPC_REVENUE" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The CPM and CPC revenue earned, calculated in publisher currency,
                    for the ads delivered by the ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_CPD_REVENUE" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The CPD revenue earned, calculated in publisher currency,
                    for the ads delivered by the ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_CPA_REVENUE" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The CPA revenue earned, calculated in publisher currency, for the ads delivered by the
                    ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_ALL_REVENUE" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The CPM, CPC and CPD revenue earned, calculated in publisher currency,
                    for the ads delivered by the ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_WITHOUT_CPD_AVERAGE_ECPM" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The average estimated cost-per-thousand-impressions earned from the CPM and CPC ads
                    delivered by the ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_WITH_CPD_AVERAGE_ECPM" =>
                        array (
                            'fieldgroup' => 'AD_SERVER',
                            'description' => 'The average estimated cost-per-thousand-impressions earned from the CPM, CPC and CPD ads
                    delivered by the ad server.',
                            "TYPE" => 'Float',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "AD_SERVER_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/LINE_ITEM_LEVEL',
                            'description' => 'The ratio of the number of impressions delivered to the total impressions
                    delivered by the ad server for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "AD_SERVER_LINE_ITEM_LEVEL_PERCENT_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/LINE_ITEM_LEVEL',
                            'description' => 'The ratio of the number of clicks delivered to the total clicks delivered
                    by the ad server for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "AD_SERVER_INVENTORY_LEVEL_WITH_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/INVENTORY_LEVEL',
                            'description' => 'The ratio of revenue generated by ad server to the total CPM, CPC and CPD revenue earned
                    by ads delivered when no <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html">LineItem</a> reservation could be found by
                    the ad server for inventory-level dynamic allocation.
                    For premium networks, this includes line item-level dynamic allocation as well.
                    Represented as a percentage.',
                        ),
                    "AD_SERVER_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/LINE_ITEM_LEVEL',
                            'description' => 'The ratio of revenue generated by ad server to the total CPM and CPC revenue earned by
                    the ads delivered for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "AD_SERVER_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/LINE_ITEM_LEVEL',
                            'description' => 'The ratio of revenue generated by ad server to the total CPM, CPC and CPD revenue earned by
                    the ads delivered for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The number of impressions an AdSense ad delivered for line item-level dynamic allocation.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The number of impressions an AdSense ad delivered for line item-level dynamic allocation by
                    explicit custom criteria targeting.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The number of clicks an AdSense ad delivered for line item-level dynamic allocation.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_TARGETED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The number of clicks an AdSense ad delivered for line item-level dynamic allocation by
                    explicit custom criteria targeting.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The ratio of clicks an AdSense reservation ad delivered to the number of
                    impressions it delivered, including line item-level dynamic allocation.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'Revenue generated from AdSense ads delivered for line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_AVERAGE_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The average estimated cost-per-thousand-impressions earned from the ads
                    delivered by AdSense for line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The ratio of the number of impressions delivered by AdSense reservation
                    ads to the total impressions delivered for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_PERCENT_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The ratio of the number of clicks delivered by AdSense reservation ads to
                    the total clicks delivered for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "DYNAMIC_ALLOCATION_INVENTORY_LEVEL_WITH_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION',
                            'description' => 'The ratio of revenue to the total revenue earned from the dynamic allocation
                    CPM, CPC and CPD ads delivered when no <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html">LineItem</a> reservation could be found
                    by the ad server for inventory-level dynamic allocation.
                    For premium networks, this includes line item-level dynamic allocation as well.
                    Represented as a percentage.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The ratio of revenue to the total revenue earned from the CPM and CPC ads
                    delivered by AdSense for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "ADSENSE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE',
                            'description' => 'The ratio of revenue to the total revenue earned from the CPM, CPC and CPD ads
                    delivered by AdSense for line item-level dynamic allocation.
                    Represented as a percentage.',
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total number of impressions delivered including line item-level dynamic allocation.',
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total number of impressions delivered including line item-level dynamic allocation by
                    explicit custom criteria targeting.',
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total number of clicks delivered including line item-level dynamic allocation.',
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_TARGETED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total number of clicks delivered including line item-level dynamic allocation by
                    explicit custom criteria targeting',
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The ratio of total clicks on ads delivered by the ad servers to the total number
                    of impressions delivered for an ad including line item-level dynamic allocation.',
                        ),
                    "TOTAL_INVENTORY_LEVEL_ALL_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/INVENTORY_LEVEL',
                            'description' => 'The total CPM, CPC and CPD revenue generated by the ad servers
                    including inventory-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_CPM_AND_CPC_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total CPM and CPC revenue generated by the ad servers
                    including line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_ALL_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'The total CPM, CPC and CPD revenue generated by the ad servers
                    including line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_INVENTORY_LEVEL_WITH_CPD_AVERAGE_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/INVENTORY_LEVEL',
                            'description' => 'Estimated cost-per-thousand-impressions (eCPM) of CPM, CPC and CPD ads delivered by the
                    ad servers including inventory-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_WITHOUT_CPD_AVERAGE_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'Estimated cost-per-thousand-impressions (eCPM) of CPM and CPC ads delivered by the
                    ad servers including line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_LINE_ITEM_LEVEL_WITH_CPD_AVERAGE_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/LINE_ITEM_LEVEL',
                            'description' => 'Estimated cost-per-thousand-impressions (eCPM) of CPM, CPC and CPD ads delivered by the
                    ad servers including line item-level dynamic allocation.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_CODE_SERVED_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL',
                            'description' => 'The total number of times that the code for an ad is served by the ad server
                    including inventory-level dynamic allocation.',
                        ),
                    "TOTAL_INVENTORY_LEVEL_UNFILLED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'TOTAL/INVENTORY_LEVEL',
                            'description' => 'The total number of missed impressions due to the ad servers\' inability to
                    find ads to serve, including inventory-level dynamic allocation.',
                        ),
                    "VIDEO_VIEWERSHIP_START" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of impressions where the video was played.',
                        ),
                    "VIDEO_VIEWERSHIP_FIRST_QUARTILE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times the video played to 25% of its length.',
                        ),
                    "VIDEO_VIEWERSHIP_MIDPOINT" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times the video reached its midpoint during play.',
                        ),
                    "VIDEO_VIEWERSHIP_THIRD_QUARTILE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times the video played to 75% of its length.',
                        ),
                    "VIDEO_VIEWERSHIP_COMPLETE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times the video played to completion.',
                        ),
                    "VIDEO_VIEWERSHIP_AVERAGE_VIEW_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Average percentage of the video watched by users.',
                        ),
                    "VIDEO_VIEWERSHIP_AVERAGE_VIEW_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Average time(seconds) users watched the video.',
                        ),
                    "VIDEO_VIEWERSHIP_COMPLETION_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Percentage of times the video played to the end.',
                        ),
                    "VIDEO_VIEWERSHIP_TOTAL_ERROR_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times an error occurred, such as a VAST redirect error, a video playback error,
                    or an invalid response error.',
                        ),
                    "VIDEO_VIEWERSHIP_VIDEO_LENGTH" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Duration of the video creative.',
                        ),
                    "VIDEO_VIEWERSHIP_SKIP_BUTTON_SHOWN" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of times a skip button is shown in video.',
                        ),
                    "VIDEO_VIEWERSHIP_ENGAGED_VIEW" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'The number of engaged views i.e. ad is viewed to completion
                    or for 30s, whichever comes first.',
                        ),
                    "VIDEO_VIEWERSHIP_VIEW_THROUGH_RATE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'View-through rate represented as a percentage.',
                        ),
                    "VIDEO_VIEWERSHIP_AUTO_PLAYS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Number of times that the publisher specified a video ad played automatically.',
                        ),
                    "VIDEO_VIEWERSHIP_CLICK_TO_PLAYS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Number of times that the publisher specified a video ad was clicked to play.',
                        ),
                    "VIDEO_VIEWERSHIP_TOTAL_ERROR_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/VIEWERSHIP',
                            'description' => 'Error rate is the percentage of video error count from (error count + total impressions).',
                        ),
                    "VIDEO_INTERACTION_PAUSE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times user paused ad clip.',
                        ),
                    "VIDEO_INTERACTION_RESUME" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times the user unpaused the video.',
                        ),
                    "VIDEO_INTERACTION_REWIND" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times a user rewinds the video.',
                        ),
                    "VIDEO_INTERACTION_MUTE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times video player was in mute state during play
                    of ad clip.',
                        ),
                    "VIDEO_INTERACTION_UNMUTE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times a user unmutes the video.',
                        ),
                    "VIDEO_INTERACTION_COLLAPSE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times a user collapses a video,
                    either to its original size or to a different size.',
                        ),
                    "VIDEO_INTERACTION_EXPAND" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times a user expands a video.',
                        ),
                    "VIDEO_INTERACTION_FULL_SCREEN" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times ad clip played in full screen mode.',
                        ),
                    "VIDEO_INTERACTION_AVERAGE_INTERACTION_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of user interactions with a video, on average,
                    such as pause, full screen, mute, etc.',
                        ),
                    "VIDEO_INTERACTION_VIDEO_SKIPS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/INTERACTION',
                            'description' => 'Video interaction event: The number of times a skippable video is skipped.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_STARTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The number of control starts.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_STARTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The number of optimized starts.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_COMPLETES" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The number of control completes.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETES" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The number of optimized completes.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_COMPLETION_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The rate of control completions.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETION_RATE" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The rate of optimized completions.',
                        ),
                    "VIDEO_OPTIMIZATION_COMPLETION_RATE_LIFT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION',
                            'description' => 'The percentage by which optimized completion rate is greater than the unoptimized completion
                    rate. This is calculated as (( <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ReportService.Column.html#VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETION_RATE">Column.VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETION_RATE</a>/
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ReportService.Column.html#VIDEO_OPTIMIZATION_CONTROL_COMPLETION_RATE">Column.VIDEO_OPTIMIZATION_CONTROL_COMPLETION_RATE</a>) - 1) * 100 for an ad for which the
                    optimization feature has been enabled.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_SKIP_BUTTON_SHOWN" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The number of control skip buttons shown.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_SKIP_BUTTON_SHOWN" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The number of optimized skip buttons shown.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_ENGAGED_VIEW" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The number of control engaged views.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_ENGAGED_VIEW" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The number of optimized engaged views.',
                        ),
                    "VIDEO_OPTIMIZATION_CONTROL_VIEW_THROUGH_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/CONTROL',
                            'description' => 'The control view-through rate.',
                        ),
                    "VIDEO_OPTIMIZATION_OPTIMIZED_VIEW_THROUGH_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION/OPTIMIZED',
                            'description' => 'The optimized view-through rate.',
                        ),
                    "VIDEO_OPTIMIZATION_VIEW_THROUGH_RATE_LIFT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'VIDEO/OPTIMIZATION',
                            'description' => 'The percentage by which optimized view-through rate is greater than the unoptimized
                    view-through rate. This is calculated as ((
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ReportService.Column.html#VIDEO_OPTIMIZATION_OPTIMIZED_VIEW_THROUGH_RATE">Column.VIDEO_OPTIMIZATION_OPTIMIZED_VIEW_THROUGH_RATE</a>/
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ReportService.Column.html#VIDEO_OPTIMIZATION_CONTROL_VIEW_THROUGH_RATE">Column.VIDEO_OPTIMIZATION_CONTROL_VIEW_THROUGH_RATE</a>) - 1) * 100 for an ad for which the
                    optimization feature has been enabled.',
                        ),
                    "TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'The total number of impressions viewed on the user\'s screen.',
                        ),
                    "TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'The total number of impressions that were sampled and measured by active view.',
                        ),
                    "TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'The percentage of total impressions viewed on the user\'s screen (out of the total impressions
                    measurable by active view).',
                        ),
                    "TOTAL_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'Total number of impressions that were eligible to measure viewability.',
                        ),
                    "TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'The percentage of total impressions that were measurable by active view (out of all the total
                    impressions sampled for active view).',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'The number of impressions delivered by the ad server viewed on the user\'s screen.',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'The number of impressions delivered by the ad server that were sampled, and measurable by
                    active view.',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'The percentage of impressions delivered by the ad server viewed on the user\'s screen (out of
                    the ad server impressions measurable by active view).',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'Total number of impressions delivered by the ad server that were eligible to measure
                    viewability.',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'The percentage of impressions delivered by the ad server that were measurable by active view (
                    out of all the ad server impressions sampled for active view).',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'AD_SERVER/ACTIVE_VIEW',
                            'description' => 'Active View ad server revenue.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'The number of impressions delivered by AdSense viewed on the user\'s screen,',
                        ),
                    "ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'The number of impressions delivered by AdSense that were sampled, and measurable by
                    active view.',
                        ),
                    "ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'The percentage of impressions delivered by AdSense viewed on the user\'s screen (out of
                    AdSense impressions measurable by active view).',
                        ),
                    "ADSENSE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'Total number of impressions delivered by AdSense that were eligible to measure
                    viewability.',
                        ),
                    "ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'The percentage of impressions delivered by AdSense that were measurable by active view (
                    out of all AdSense impressions sampled for active view).',
                        ),
                    "ADSENSE_ACTIVE_VIEW_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'ADSENSE/ACTIVE_VIEW',
                            'description' => 'Active View AdSense revenue.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_ACTIVE_VIEW_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'TOTAL/ACTIVE_VIEW',
                            'description' => 'Active View total revenue.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "VIEW_THROUGH_CONVERSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Number of view-through conversions.',
                        ),
                    "CONVERSIONS_PER_THOUSAND_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Number of view-through conversions per thousand impressions.',
                        ),
                    "CLICK_THROUGH_CONVERSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Number of click-through conversions.',
                        ),
                    "CONVERSIONS_PER_CLICK" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Number of click-through conversions per click.',
                        ),
                    "VIEW_THROUGH_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Revenue for view-through conversions.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "CLICK_THROUGH_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Revenue for click-through conversions.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "TOTAL_CONVERSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Total number of conversions.',
                        ),
                    "TOTAL_CONVERSION_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONVERSIONS',
                            'description' => 'Total revenue for conversions.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_COMPETING_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The number of impressions sent to Ad Exchange / AdSense, regardless of whether they
                    won or lost (total number of dynamic allocation impressions).',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_UNFILLED_IMPRESSIONS_COMPETING" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The number of unfilled queries that attempted dynamic allocation by Ad Exchange / AdSense.',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_ELIGIBLE_IMPRESSIONS_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The number of Ad Exchange / AdSense and DFP impressions.',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The difference between eligible impressions and competing impressions in dynamic allocation.',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_PERCENT_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The percentage of eligible impressions that are not competing in dynamic allocation.',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_SATURATION_RATE_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The percent of eligible impressions participating in dynamic allocation.',
                        ),
                    "DYNAMIC_ALLOCATION_OPPORTUNITY_MATCH_RATE_TOTAL" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DYNAMIC_ALLOCATION/OPPORTUNITY',
                            'description' => 'The percent of total dynamic allocation queries that won.',
                        ),
                    "CONTRACTED_REVENUE_CONTRACTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "CONTRACTED_REVENUE_LOCAL_CONTRACTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted net revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.

                    See <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_NET_REVENUE">CONTRACTED_REVENUE_CONTRACTED_NET_REVENUE</a>',
                        ),
                    "CONTRACTED_REVENUE_CONTRACTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>, including agency commission.',
                        ),
                    "CONTRACTED_REVENUE_LOCAL_CONTRACTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted gross revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>, including
                    agency commission.

                    See <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_GROSS_REVENUE">CONTRACTED_REVENUE_CONTRACTED_GROSS_REVENUE</a>',
                        ),
                    "CONTRACTED_REVENUE_CONTRACTED_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The value added tax on contracted net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.',
                        ),
                    "CONTRACTED_REVENUE_LOCAL_CONTRACTED_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The value added tax on contracted net revenue in the local currency of the
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.

                    See <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_VAT">CONTRACTED_REVENUE_CONTRACTED_VAT</a>',
                        ),
                    "CONTRACTED_REVENUE_CONTRACTED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.',
                        ),
                    "CONTRACTED_REVENUE_LOCAL_CONTRACTED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'CONTRACTED_REVENUE',
                            'description' => 'The contracted agency commission in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.

                    See <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_AGENCY_COMMISSION">CONTRACTED_REVENUE_CONTRACTED_AGENCY_COMMISSION</a>',
                        ),
                    "SALES_CONTRACT_CONTRACTED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The contracted impressions of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SALES_CONTRACT_CONTRACTED_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The contracted clicks of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SALES_CONTRACT_CONTRACTED_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The contracted volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. Volume represents impressions for
                    rate type CPM, clicks for CPC, and days for CPD.',
                        ),
                    "SALES_CONTRACT_BUDGET" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The budget of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.',
                        ),
                    "SALES_CONTRACT_REMAINING_BUDGET" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The remaining budget of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>. It is calculated by subtracting the contracted
                    net revenue from the budget.',
                        ),
                    "SALES_CONTRACT_BUFFERED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The buffered impressions of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SALES_CONTRACT_BUFFERED_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_CONTRACT',
                            'description' => 'The buffered clicks of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SCHEDULED_SCHEDULED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled impressions of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the sum of
                    <a class="codelink" href="#SALES_CONTRACT_CONTRACTED_IMPRESSIONS">SALES_CONTRACT_CONTRACTED_IMPRESSIONS</a> and
                    <a class="codelink" href="#SALES_CONTRACT_BUFFERED_IMPRESSIONS">SALES_CONTRACT_BUFFERED_IMPRESSIONS</a>.',
                        ),
                    "SCHEDULED_SCHEDULED_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled clicks of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the sum of
                    <a class="codelink" href="#SALES_CONTRACT_CONTRACTED_CLICKS">SALES_CONTRACT_CONTRACTED_CLICKS</a> and <a class="codelink" href="#SALES_CONTRACT_BUFFERED_CLICKS">SALES_CONTRACT_BUFFERED_CLICKS</a>.',
                        ),
                    "SCHEDULED_SCHEDULED_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled volume of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the sum of
                    <a class="codelink" href="#SALES_CONTRACT_CONTRACTED_VOLUME">SALES_CONTRACT_CONTRACTED_VOLUME</a> and buffered volume.',
                        ),
                    "SCHEDULED_SCHEDULED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled net revenue of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SCHEDULED_LOCAL_SCHEDULED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled net revenue in the local currency of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SCHEDULED_SCHEDULED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled gross revenue of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SCHEDULED_LOCAL_SCHEDULED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SCHEDULED',
                            'description' => 'The scheduled gross revenue in the local currency of a <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "SALES_TOTAL_TOTAL_BUDGET" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total budget of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>. It differs from <a class="codelink" href="#SALES_CONTRACT_BUDGET">SALES_CONTRACT_BUDGET</a> since
                    it always contains the total budget, not the prorated budget.',
                        ),
                    "SALES_TOTAL_TOTAL_REMAINING_BUDGET" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total remaining budget of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>. It differs from
                    <a class="codelink" href="#SALES_CONTRACT_REMAINING_BUDGET">SALES_CONTRACT_REMAINING_BUDGET</a> since it always contains the total
                    remaining budget, not the prorated remaining budget.',
                        ),
                    "SALES_TOTAL_TOTAL_CONTRACTED_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#SALES_CONTRACT_CONTRACTED_VOLUME">SALES_CONTRACT_CONTRACTED_VOLUME</a> that the volume is not prorated with regard to the
                    date range.',
                        ),
                    "SALES_TOTAL_TOTAL_CONTRACTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_NET_REVENUE">CONTRACTED_REVENUE_CONTRACTED_NET_REVENUE</a> that the revenue is not prorated with
                    regard to the date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_CONTRACTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted net revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It
                    differs from <a class="codelink" href="#CONTRACTED_REVENUE_LOCAL_CONTRACTED_NET_REVENUE">CONTRACTED_REVENUE_LOCAL_CONTRACTED_NET_REVENUE</a> that the revenue is not
                    prorated with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_CONTRACTED_NET_REVENUE">SALES_TOTAL_TOTAL_CONTRACTED_NET_REVENUE</a>',
                        ),
                    "SALES_TOTAL_TOTAL_CONTRACTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_GROSS_REVENUE">CONTRACTED_REVENUE_CONTRACTED_GROSS_REVENUE</a> that the revenue is not prorated with
                    regard to the date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_CONTRACTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted gross revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It
                    differs from <a class="codelink" href="#CONTRACTED_REVENUE_LOCAL_CONTRACTED_GROSS_REVENUE">CONTRACTED_REVENUE_LOCAL_CONTRACTED_GROSS_REVENUE</a> that the revenue is
                    not prorated with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_CONTRACTED_GROSS_REVENUE">SALES_TOTAL_TOTAL_CONTRACTED_GROSS_REVENUE</a>',
                        ),
                    "SALES_TOTAL_TOTAL_CONTRACTED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#CONTRACTED_REVENUE_CONTRACTED_AGENCY_COMMISSION">CONTRACTED_REVENUE_CONTRACTED_AGENCY_COMMISSION</a> that the revenue is not prorated with
                    regard to the date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_CONTRACTED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total contracted agency commission in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.
                    It differs from <a class="codelink" href="#CONTRACTED_REVENUE_LOCAL_CONTRACTED_AGENCY_COMMISSION">CONTRACTED_REVENUE_LOCAL_CONTRACTED_AGENCY_COMMISSION</a> that the
                    revenue is not prorated with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_CONTRACTED_AGENCY_COMMISSION">SALES_TOTAL_TOTAL_CONTRACTED_AGENCY_COMMISSION</a>',
                        ),
                    "SALES_TOTAL_TOTAL_CONTRACTED_NET_REVENUE_WITH_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total net revenue plus its value added tax of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. The revenue is
                    not prorated with regard to the date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_CONTRACTED_NET_REVENUE_WITH_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total net revenue plus its value added tax in the local currency of the
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. The revenue is not prorated with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_CONTRACTED_WITH_VAT">SALES_TOTAL_TOTAL_CONTRACTED_WITH_VAT</a>',
                        ),
                    "SALES_TOTAL_TOTAL_SCHEDULED_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total scheduled volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#SCHEDULED_SCHEDULED_VOLUME">SCHEDULED_SCHEDULED_VOLUME</a> that the volume is not prorated with regard to the date
                    range.',
                        ),
                    "SALES_TOTAL_TOTAL_SCHEDULED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total scheduled net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#SCHEDULED_SCHEDULED_NET_REVENUE">SCHEDULED_SCHEDULED_NET_REVENUE</a> that the revenue is not prorated with regard to the
                    date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_SCHEDULED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total scheduled net revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It
                    differs from <a class="codelink" href="#SCHEDULED_LOCAL_SCHEDULED_NET_REVENUE">SCHEDULED_LOCAL_SCHEDULED_NET_REVENUE</a> that the revenue is not prorated
                    with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_SCHEDULED_NET_REVENUE">SALES_TOTAL_TOTAL_SCHEDULED_NET_REVENUE</a>',
                        ),
                    "SALES_TOTAL_TOTAL_SCHEDULED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total scheduled gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It differs from
                    <a class="codelink" href="#SCHEDULED_SCHEDULED_GROSS_REVENUE">SCHEDULED_SCHEDULED_GROSS_REVENUE</a> that the revenue is not prorated with regard to the
                    date range.',
                        ),
                    "SALES_TOTAL_LOCAL_TOTAL_SCHEDULED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_TOTAL',
                            'description' => 'The total scheduled gross revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It
                    differs from <a class="codelink" href="#SCHEDULED_LOCAL_SCHEDULED_GROSS_REVENUE">SCHEDULED_LOCAL_SCHEDULED_GROSS_REVENUE</a> that the revenue is not prorated
                    with regard to the date range.

                    See <a class="codelink" href="#SALES_TOTAL_TOTAL_SCHEDULED_GROSS_REVENUE">SALES_TOTAL_TOTAL_SCHEDULED_GROSS_REVENUE</a>',
                        ),
                    "UNIFIED_REVENUE_UNRECONCILED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'multiplyBy' => 9.9999999999999995E-7,
                            'description' => 'The unreconciled net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the portion of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_UNIFIED_NET_REVENUE</a> coming from unreconciled DFP volume.',
                        ),
                    "UNIFIED_REVENUE_LOCAL_UNRECONCILED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unreconciled net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is the
                    portion of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE</a> coming from unreconciled DFP
                    volume.

                    See <a class="codelink" href="#UNIFIED_REVENUE_UNRECONCILED_NET_REVENUE">UNIFIED_REVENUE_UNRECONCILED_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_UNRECONCILED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unreconciled gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the portion of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE</a> coming from unreconciled DFP volume.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_LOCAL_UNRECONCILED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unreconciled gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is the
                    portion of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE</a> coming from unreconciled DFP
                    volume.

                    See <a class="codelink" href="#UNIFIED_REVENUE_UNRECONCILED_GROSS_REVENUE">UNIFIED_REVENUE_UNRECONCILED_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_FORECASTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The forecasted net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the portion of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_UNIFIED_NET_REVENUE</a> coming from forecasted DFP volume.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_LOCAL_FORECASTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The forecasted net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is the portion
                    of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE</a> coming from forecasted DFP volume.

                    See <a class="codelink" href="#UNIFIED_REVENUE_FORECASTED_NET_REVENUE">UNIFIED_REVENUE_FORECASTED_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_FORECASTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The forecasted gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is the portion of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE</a> coming from forecasted DFP volume.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_LOCAL_FORECASTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The forecasted gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is the
                    portion of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE</a> coming from forecasted DFP
                    volume.

                    See <a class="codelink" href="#UNIFIED_REVENUE_FORECASTED_GROSS_REVENUE">UNIFIED_REVENUE_FORECASTED_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_UNIFIED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is a combination of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNRECONCILED_NET_REVENUE">UNIFIED_REVENUE_UNRECONCILED_NET_REVENUE</a>, <a class="codelink" href="#BILLING_BILLABLE_NET_REVENUE">BILLING_BILLABLE_NET_REVENUE</a>,
                    and <a class="codelink" href="#UNIFIED_REVENUE_FORECASTED_NET_REVENUE">UNIFIED_REVENUE_FORECASTED_NET_REVENUE</a> when query date range spans historical
                    delivery and forecasted delivery.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is a
                    combination of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNRECONCILED_NET_REVENUE">UNIFIED_REVENUE_LOCAL_UNRECONCILED_NET_REVENUE</a>,
                    <a class="codelink" href="#BILLING_LOCAL_BILLABLE_NET_REVENUE">BILLING_LOCAL_BILLABLE_NET_REVENUE</a>,
                    and <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_FORECASTED_NET_REVENUE">UNIFIED_REVENUE_LOCAL_FORECASTED_NET_REVENUE</a> when query date range spans
                    historical delivery and forecasted delivery.

                    See <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_UNIFIED_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is a combination of
                    <a class="codelink" href="#UNIFIED_REVENUE_UNRECONCILED_GROSS_REVENUE">UNIFIED_REVENUE_UNRECONCILED_GROSS_REVENUE</a>, <a class="codelink" href="#BILLING_BILLABLE_GROSS_REVENUE">BILLING_BILLABLE_GROSS_REVENUE</a>,
                    and <a class="codelink" href="#UNIFIED_REVENUE_FORECASTED_GROSS_REVENUE">UNIFIED_REVENUE_FORECASTED_GROSS_REVENUE</a> when query date range spans historical
                    delivery and forecasted delivery.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is a
                    combination of <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNRECONCILED_GROSS_REVENUE">UNIFIED_REVENUE_LOCAL_UNRECONCILED_GROSS_REVENUE</a>,
                    <a class="codelink" href="#BILLING_LOCAL_BILLABLE_GROSS_REVENUE">BILLING_LOCAL_BILLABLE_GROSS_REVENUE</a>, and
                    <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_FORECASTED_GROSS_REVENUE">UNIFIED_REVENUE_LOCAL_FORECASTED_GROSS_REVENUE</a> when query date range spans historical
                    delivery and forecasted delivery.

                    See <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "UNIFIED_REVENUE_UNIFIED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is a combination of the
                    unreconciled agency commission, the <a class="codelink" href="#BILLING_BILLABLE_AGENCY_COMMISSION">BILLING_BILLABLE_AGENCY_COMMISSION</a>,
                    and the forecasted agency commission when query date range spans historical delivery and
                    forecasted delivery.',
                        ),
                    "UNIFIED_REVENUE_LOCAL_UNIFIED_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'UNIFIED_REVENUE',
                            'description' => 'The unified agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is a
                    combination of the unreconciled agency commission, the
                    <a class="codelink" href="#BILLING_BILLABLE_AGENCY_COMMISSION">BILLING_BILLABLE_AGENCY_COMMISSION</a>, and the forecasted agency commission when query
                    date range spans historical delivery and forecasted delivery.

                    See <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_AGENCY_COMMISSION">UNIFIED_REVENUE_UNIFIED_AGENCY_COMMISSION</a>',
                        ),
                    "EXPECTED_REVENUE_EXPECTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'EXPECTED_REVENUE',
                            'description' => 'The expected revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is equivalent to
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_UNIFIED_NET_REVENUE</a> when the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> is sold and
                    <a class="codelink" href="#SALES_PIPELINE_PIPELINE_NET_REVENUE">SALES_PIPELINE_PIPELINE_NET_REVENUE</a> otherwise.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "EXPECTED_REVENUE_LOCAL_EXPECTED_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'EXPECTED_REVENUE',
                            'description' => 'The expected revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is equivalent to
                    <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_NET_REVENUE</a> when the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> is sold
                    and <a class="codelink" href="#SALES_PIPELINE_LOCAL_PIPELINE_NET_REVENUE">SALES_PIPELINE_LOCAL_PIPELINE_NET_REVENUE</a> otherwise.

                    See <a class="codelink" href="#EXPECTED_REVENUE_EXPECTED_NET_REVENUE">EXPECTED_REVENUE_EXPECTED_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "EXPECTED_REVENUE_EXPECTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'EXPECTED_REVENUE',
                            'description' => 'The expected gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is equivalent to
                    <a class="codelink" href="#UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_UNIFIED_GROSS_REVENUE</a> when the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> is sold and
                    <a class="codelink" href="#SALES_PIPELINE_PIPELINE_GROSS_REVENUE">SALES_PIPELINE_PIPELINE_GROSS_REVENUE</a> otherwise.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "EXPECTED_REVENUE_LOCAL_EXPECTED_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'EXPECTED_REVENUE',
                            'description' => 'The expected gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> in local currency. It is
                    equivalent to <a class="codelink" href="#UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE">UNIFIED_REVENUE_LOCAL_UNIFIED_GROSS_REVENUE</a> when the
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> is sold and <a class="codelink" href="#SALES_PIPELINE_LOCAL_PIPELINE_GROSS_REVENUE">SALES_PIPELINE_LOCAL_PIPELINE_GROSS_REVENUE</a>
                    otherwise.

                    See <a class="codelink" href="#EXPECTED_REVENUE_EXPECTED_GROSS_REVENUE">EXPECTED_REVENUE_EXPECTED_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "SALES_PIPELINE_PIPELINE_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is calculated by multiplying
                    probability to close by the contracted revenue for those unsold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.
                    There is no revenue for those sold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "SALES_PIPELINE_LOCAL_PIPELINE_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline net revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is
                    calculated by multiplying probability to close by the contracted revenue for those unsold
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s. There is no revenue for those sold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.

                    See <a class="codelink" href="#SALES_PIPELINE_PIPELINE_NET_REVENUE">SALES_PIPELINE_PIPELINE_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "SALES_PIPELINE_PIPELINE_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is calculated by multiplying
                    probability to close by the contracted revenue including agency commission for those
                    unsold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s. There is no revenue for those sold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "SALES_PIPELINE_LOCAL_PIPELINE_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline gross revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is
                    calculated by multiplying probability to close by the contracted revenue including agency
                    commission for those unsold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s. There is no revenue for those sold
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.

                    See <a class="codelink" href="#SALES_PIPELINE_PIPELINE_GROSS_REVENUE">SALES_PIPELINE_PIPELINE_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "SALES_PIPELINE_PIPELINE_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is calculated by multiplying
                    probability to close by the contracted agency commission for those unsold
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s. There is no revenue for those sold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.',
                        ),
                    "SALES_PIPELINE_LOCAL_PIPELINE_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'SALES_PIPELINE',
                            'description' => 'The pipeline agency commission in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is
                    calculated by multiplying probability to close by the contracted agency commission for those
                    unsold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s. There is no revenue for those sold <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>s.

                    See <a class="codelink" href="#SALES_PIPELINE_PIPELINE_AGENCY_COMMISSION">SALES_PIPELINE_PIPELINE_AGENCY_COMMISSION</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "RECONCILIATION_DFP_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The DFP volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>, which is used for reconciliation. Volume
                    represents impressions for rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "RECONCILIATION_THIRD_PARTY_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The third party volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>, which is used for reconciliation.
                    Volume represents impressions for rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "RECONCILIATION_RECONCILED_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The reconciled volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>, which is used for reconciliation. Volume
                    represents impressions for rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "RECONCILIATION_DISCREPANCY_PERCENTAGE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The discrepancy percentage between DFP volume and third party volume.',
                        ),
                    "RECONCILIATION_RECONCILED_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The reconciled revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ForecastService.LineItem.html">LineItem</a>.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "RECONCILIATION_IMPRESSION_DISCREPANCY" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The discrepancy between DFP impressions and third party impressions.',
                        ),
                    "RECONCILIATION_CLICK_DISCREPANCY" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The discrepancy between DFP clicks and third party clicks.',
                        ),
                    "RECONCILIATION_REVENUE_DISCREPANCY" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'RECONCILIATION',
                            'description' => 'The discrepancy between DFP revenue and third party revenue.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_BILLABLE_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is calculated from reconciled
                    volume and rate, with cap applied.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_LOCAL_BILLABLE_NET_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable net revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is
                    calculated from reconciled volume and rate, with cap applied.

                    See <a class="codelink" href="#BILLING_BILLABLE_NET_REVENUE">BILLING_BILLABLE_NET_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_BILLABLE_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is calculated from reconciled
                    volume and rate, with cap applied, and including agency commission.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_LOCAL_BILLABLE_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable gross revenue in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>. It is
                    calculated from reconciled volume and rate, with cap applied, and including agency commission.

                    See <a class="codelink" href="#BILLING_BILLABLE_GROSS_REVENUE">BILLING_BILLABLE_GROSS_REVENUE</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_BILLABLE_NET_REVENUE_BEFORE_MANUAL_ADJUSTMENT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> before manual adjustment. It is
                    calculated from reconciled volume and rate, with cap applied, before manual adjustment.',
                        ),
                    "BILLING_LOCAL_BILLABLE_NET_REVENUE_BEFORE_MANUAL_ADJUSTMENT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable net revenue in local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> before manual
                    adjustment. It is calculated from reconciled volume and rate, with cap applied, before manual
                    adjustment.

                    See <a class="codelink" href="#BILLING_BILLABLE_NET_REVENUE_BEFORE_MANUAL_ADJUSTMENT">BILLING_BILLABLE_NET_REVENUE_BEFORE_MANUAL_ADJUSTMENT</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_BILLABLE_GROSS_REVENUE_BEFORE_MANUAL_ADJUSTMENT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable gross revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> before manual adjustment. It is
                    calculated from reconciled volume and rate, with cap applied, before manual adjustment.',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_LOCAL_BILLABLE_GROSS_REVENUE_BEFORE_MANUAL_ADJUSTMENT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable net revenue in local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> before manual
                    adjustment. It is calculated from reconciled volume and rate, with cap applied, before manual
                    adjustment.

                    See <a class="codelink" href="#BILLING_BILLABLE_GROSS_REVENUE_BEFORE_MANUAL_ADJUSTMENT">BILLING_BILLABLE_GROSS_REVENUE_BEFORE_MANUAL_ADJUSTMENT</a>',
                            'multiplyBy' => 9.9999999999999995E-7,
                        ),
                    "BILLING_BILLABLE_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The value added tax on billable net revenue of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.',
                        ),
                    "BILLING_LOCAL_BILLABLE_VAT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The value added tax on billable net revenue in the local currency of the
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.

                    See <a class="codelink" href="#BILLING_BILLABLE_VAT">BILLING_BILLABLE_VAT</a>',
                        ),
                    "BILLING_BILLABLE_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable agency commission of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.',
                        ),
                    "BILLING_LOCAL_BILLABLE_AGENCY_COMMISSION" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable agency commission in the local currency of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> or
                    <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalService.Proposal.html">Proposal</a>.

                    See <a class="codelink" href="#BILLING_BILLABLE_AGENCY_COMMISSION">BILLING_BILLABLE_AGENCY_COMMISSION</a>',
                        ),
                    "BILLING_CAP_QUANTITY" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The cap quantity of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> for each cycle. Quantity represents
                    impressions for rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "BILLING_BILLABLE_VOLUME" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The billable volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> for each cycle. Billable volumes are
                    calculated by applying cap quantity to reconciled volumes. Volume represents impressions for
                    rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "BILLING_DELIVERY_ROLLOVER" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The delivery rollover volume of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a> from previous cycle. Volume
                    represents impressions for rate type CPM, clicks for CPC and days for CPD.',
                        ),
                    "BILLING_REALIZED_CPM" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The CPM calcuated by <a class="codelink" href="#BILLING_BILLABLE_NET_REVENUE">BILLING_BILLABLE_NET_REVENUE</a> and <a class="codelink" href="#AD_SERVER_IMPRESSIONS">AD_SERVER_IMPRESSIONS</a>.',
                        ),
                    "BILLING_REALIZED_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'BILLING',
                            'description' => 'The rate calcuated by <a class="codelink" href="#BILLING_BILLABLE_NET_REVENUE">BILLING_BILLABLE_NET_REVENUE</a> and DFP volume.',
                        ),
                    "DISCOUNTS_BREAKDOWN_CONTRACTED_NET_OVERALL_DISCOUNT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DISCOUNTS_BREAKDOWN',
                            'description' => 'The contracted net overall discount of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "DISCOUNTS_BREAKDOWN_BILLABLE_NET_OVERALL_DISCOUNT" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DISCOUNTS_BREAKDOWN',
                            'description' => 'The billable net overall discount of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.',
                        ),
                    "DISCOUNTS_BREAKDOWN_CONTRACTED_NET_NON_BILLABLE" =>
                        array (
                            "TYPE" => 'Float',
                            'fieldgroup' => 'DISCOUNTS_BREAKDOWN',
                            'description' => 'The contracted non-billable (net) of the <a class="codelink" href="https://developers.google.com/doubleclick-publishers/docs/reference/v201605/ProposalLineItemService.ProposalLineItem.html">ProposalLineItem</a>.
                    The non-billable means revenue that marked as make good, added value or barter.',
                        ),
                    "INVOICED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'INVOICED',
                            'description' => 'The number of invoiced impressions.',
                        ),
                    "INVOICED_UNFILLED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'fieldgroup' => 'INVOICED',
                            'description' => 'The number of invoiced unfilled impressions.',
                        ),
                    "AD_EXCHANGE_PRICING_RULE_ID" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange pricing rule ID.',
                        ),
                    "AD_EXCHANGE_PRICING_RULE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange pricing rule.',
                        ),
                    "AD_EXCHANGE_TAG_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange tag.',
                        ),
                    "AD_EXCHANGE_CREATIVE_SIZES" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange creative size.',
                        ),
                    "AD_EXCHANGE_CHANNEL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange channel.',
                        ),
                    "AD_EXCHANGE_PRODUCT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange product.',
                        ),
                    "AD_EXCHANGE_SITE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange site.',
                        ),
                    "AD_EXCHANGE_REQUEST_SOURCES" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange request source.',
                        ),
                    "AD_EXCHANGE_ADVERTISER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by the Ad Exchange advertiser name that bids on ads.',
                        ),
                    "AD_EXCHANGE_AGENCY" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange agency.',
                        ),
                    "AD_EXCHANGE_BRANDING_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange branding type code.',
                        ),
                    "AD_EXCHANGE_BUYER_NETWORK_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange ad network name. Example: Google Adwords.',
                        ),
                    "AD_EXCHANGE_DATE" =>
                        array (
                            "TYPE" => 'Date',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange date.',
                        ),
                    "AD_EXCHANGE_DEAL_ID" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange deal id.',
                        ),
                    "AD_EXCHANGE_DEAL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange deal name.',
                        ),
                    "AD_EXCHANGE_DSP_BUYER_NETWORK_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange DSP buyer network name.',
                        ),
                    "AD_EXCHANGE_EXPANSION_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange expansion type.',
                        ),
                    "AD_EXCHANGE_COUNTRY_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange country code.',
                        ),
                    "AD_EXCHANGE_COUNTRY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange country name.',
                        ),
                    "AD_EXCHANGE_INVENTORY_OWNERSHIP" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange inventory ownership.',
                        ),
                    "AD_EXCHANGE_MOBILE_APP_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange mobile app name.',
                        ),
                    "AD_EXCHANGE_MOBILE_CARRIER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange mobile carrier name.',
                        ),
                    "AD_EXCHANGE_MOBILE_DEVICE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange mobile device name.',
                        ),
                    "AD_EXCHANGE_MOBILE_INVENTORY_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange mobile inventory type.',
                        ),
                    "AD_EXCHANGE_MONTH" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange month.',
                        ),
                    "AD_EXCHANGE_NETWORK_PARTNER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange network partner name.',
                        ),
                    "AD_EXCHANGE_TAG_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange tags.',
                        ),
                    "AD_EXCHANGE_TARGETING_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange targeting type code.',
                        ),
                    "AD_EXCHANGE_USER_BANDWIDTH_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange user bandwidth.',
                        ),
                    "AD_EXCHANGE_VIDEO_AD_DURATION" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange video ad duration.',
                        ),
                    "AD_EXCHANGE_VIDEO_AD_DURATION_RAW" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange raw video ad duration.',
                        ),
                    "AD_EXCHANGE_WEEK" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'Breaks down mapped Ad Exchange web property data by Ad Exchange week.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of impressions an Ad Exchange ad delivered for line item-level dynamic allocation.',
                        ),
                    "AD_EXCHANGE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'Ad impressions (legacy) on mapped Ad Exchange properties. In the case of text ads, you may have one matched request which yields more than one ad impression, since multiple text ads can serve in place of one display ad.',
                        ),
                    "AD_EXCHANGE_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of clicks delivered by mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_ESTIMATED_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The estimated net revenue generated by mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_COVERAGE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The coverage reported by mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_LIFT" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The total lift generated by mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The click-through rate of impressions issued by mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_VIDEO_DROPOFF_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The video ad drop off rate issued by Ad Exchange properties linked to DFP.',
                        ),
                    "AD_EXCHANGE_VIDEO_ABANDONMENT_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The video ad abandonment rate issued by Ad Exchange properties linked to DFP.',
                        ),
                    "AD_EXCHANGE_VIDEO_QUARTILE_1" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'A count of how many users watch the first 25% of a video ad, for mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_VIDEO_QUARTILE_3" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'A count of how many users watch the first 75% of a video ad, for mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_VIDEO_TRUEVIEW_SKIP_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'Percentage of times a user clicked Skip, for mapped Ad Exchange properties.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of impressions an Ad Exchange ad delivered for line item-level dynamic allocation by explicit custom criteria targeting.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of clicks an Ad Exchange ad delivered for line item-level dynamic allocation.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of clicks an Ad Exchange ad delivered for line item-level dynamic allocation by explicit custom criteria targeting.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The ratio of clicks an Ad Exchange ad delivered to the number of impressions it delivered for line item-level dynamic allocation.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The ratio of the number of impressions delivered to the total impressions delivered by Ad Exchange for line item-level dynamic allocation. Represented as a percentage.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_CLICKS" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The ratio of the number of clicks delivered to the total clicks delivered by Ad Exchange for line item-level dynamic allocation. Represented as a percentage.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'Revenue generated from Ad Exchange ads delivered for line item-level dynamic allocation. Represented in publisher currency and time zone.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The ratio of revenue generated by Ad Exchange to the total revenue earned by CPM and CPC ads delivered for line item-level dynamic allocation. Represented as a percentage.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The ratio of revenue generated by Ad Exchange to the total revenue earned by CPM, CPC and CPD ads delivered for line item-level dynamic allocation. Represented as a percentage.',
                        ),
                    "AD_EXCHANGE_LINE_ITEM_LEVEL_AVERAGE_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The average estimated cost-per-thousand-impressions earned from the delivery of Ad Exchange ads for line item-level dynamic allocation.',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of impressions delivered by Ad Exchange viewed on the user\'s screen,',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'The number of impressions delivered by Ad Exchange that were sampled, and measurable by active view.',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'The percentage of impressions delivered by Ad Exchange viewed on the user\'s screen (out of Ad Exchange impressions measurable by active view).',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => 'Total number of impressions delivered by Ad Exchange that were eligible to measure viewability.',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE" =>
                        array (
                            "TYPE" => 'String',
                            'description' => 'The percentage of impressions delivered by Ad Exchange that were measurable by active view ( out of all Ad Exchange impressions sampled for active view).',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => 'Active View AdExchange revenue.',
                        ),
                    "SALESPERSON_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "SALESPERSON_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARENT_AD_UNIT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARENT_AD_UNIT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "COUNTRY_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "REGION_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "REGION_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CITY_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CITY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "METRO_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "METRO_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "POSTAL_CODE_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "POSTAL_CODE" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CUSTOM_CRITERIA" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ACTIVITY_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ACTIVITY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ACTIVITY_GROUP_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ACTIVITY_GROUP_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CONTENT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CONTENT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CONTENT_BUNDLE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CONTENT_BUNDLE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_METADATA_KEY_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_METADATA_KEY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_FALLBACK_POSITION" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "POSITION_OF_POD" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "POSITION_IN_POD" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_REDIRECT_THIRD_PARTY" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_BREAK_TYPE" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_BREAK_TYPE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_VAST_VERSION" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_AD_REQUEST_DURATION_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_AD_REQUEST_DURATION" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_LABEL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_LABEL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_ASSIGNMENT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_ASSIGNMENT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "GRP_DEMOGRAPHICS" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => 'Breaks down reporting data by gender and age group',
                        ),
                    "AD_REQUEST_CUSTOM_CRITERIA" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => 'Breaks down reporting data by the custom criteria specified in ad requests.Formatted as comma separated key-values, where a key-value is formatted as key1=value_1|...|value_n,key2=value_1|...|value_n,....',
                        ),
                    "YIELD_GROUP_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "YIELD_GROUP_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "YIELD_PARTNER" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "YIELD_PARTNER_TAG" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CLASSIFIED_ADVERTISER_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CLASSIFIED_ADVERTISER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CLASSIFIED_BRAND_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CLASSIFIED_BRAND_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "MEDIATION_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => 'Breaks down reporting data by mediation type. A mediation type can be web, mobile app or video.Corresponds to "Mediation type" in the Ad Manager UI. Compatible with any of the following report types: Historical, Reach.',
                        ),
                    "NATIVE_TEMPLATE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NATIVE_TEMPLATE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NATIVE_STYLE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NATIVE_STYLE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "MOBILE_APP_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "MOBILE_DEVICE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "MOBILE_INVENTORY_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => 'Breaks down reporting data by inventory type. Can be used for filtering.',
                        ),
                    "REQUEST_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => 'Breaks down reporting data by request type. Can be used for filtering.',
                        ),
                    "AD_UNIT_STATUS" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => 'Status of the ad unit. Not available as a dimension to report on, but exists as a dimension in order to filter on it using PQL.',
                        ),
                    "MASTER_COMPANION_CREATIVE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "MASTER_COMPANION_CREATIVE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_LINE_ITEM_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_LINE_ITEM_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ALL_SALESPEOPLE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "ALL_SALESPEOPLE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "SALES_TEAM_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "SALES_TEAM_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_AGENCY_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROPOSAL_AGENCY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_TEMPLATE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_TEMPLATE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "RATE_CARD_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "RATE_CARD_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "WORKFLOW_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "WORKFLOW_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PACKAGE_ID" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PACKAGE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_PACKAGE_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PRODUCT_PACKAGE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AUDIENCE_SEGMENT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AUDIENCE_SEGMENT_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AUDIENCE_SEGMENT_DATA_PROVIDER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_INVENTORY_SIZE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_INVENTORY_SIZE_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DEVICE_CATEGORY" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_URL" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_WEB_PROPERTY_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_PRODUCT_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_BRAND_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_BID_TYPE_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_BRANDING_TYPE_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_BUYER_NETWORK_ID" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_CUSTOM_CHANNEL_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_CUSTOM_CHANNEL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_TRANSACTION_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DFP_AD_UNIT_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DFP_AD_UNIT" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ADVERTISER_DOMAIN" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_OPERATING_SYSTEM" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_OPTIMIZATION_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_TARGETING_TYPE_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_TRANSACTION_TYPE_CODE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_URL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_VIDEO_AD_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_LOCATION" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ADVERTISER_VERTICAL" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_SEGMENT" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_DEMOGRAPHICS" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_RESTATEMENT_DATE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROGRAMMATIC_BUYER_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROGRAMMATIC_BUYER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "REQUESTED_AD_SIZES" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CREATIVE_SIZE_DELIVERED" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROGRAMMATIC_CHANNEL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "PROGRAMMATIC_CHANNEL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_DATE" =>
                        array (
                            "TYPE" => 'Date',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_WEEK" =>
                        array (
                            "TYPE" => 'Week',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_MONTH_YEAR" =>
                        array (
                            "TYPE" => 'MonthYear',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_COUNTRY_CRITERIA_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_COUNTRY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_INVENTORY_TYPE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_CREATIVE_SIZE" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_BRAND_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_ADVERTISER_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_ADX_BUYER_NETWORK_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_MOBILE_DEVICE_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_DEVICE_CATEGORY_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_TAG_ID" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_DEAL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DP_APP_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "CUSTOM_DIMENSION" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DEMAND_CHANNEL_ID" =>
                        array (
                            "TYPE" => 'Integer',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "DEMAND_CHANNEL_NAME" =>
                        array (
                            "TYPE" => 'String',
                            'groupable' => 'True',
                            'description' => '##ADD##',
                        ),
                    "AD_SERVER_UNFILTERED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_SERVER_UNFILTERED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_MATCHED_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_CPC" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_REQUEST_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_REQUEST_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AD_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_MATCHED_ECPM" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_AVERAGE_VIEWABLE_TIME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_ENABLED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_MEASURED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_VIEWED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DEALS_BID_RESPONSES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DEALS_MATCHED_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DEALS_AD_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_DEALS_MATCH_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_VIDEO_TRUEVIEW_VIEWS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_VIDEO_TRUEVIEW_VTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "MEDIATION_THIRD_PARTY_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "TOTAL_AD_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "TOTAL_RESPONSES_SERVED" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "TOTAL_UNMATCHED_AD_REQUESTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "TOTAL_FILL_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_SERVER_RESPONSES_SERVED" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "ADSENSE_RESPONSES_SERVED" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_RESPONSES_SERVED" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_CONTROL_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_CONTROL_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_CONTROL_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_OPTIMIZED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_OPTIMIZED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_NON_OPTIMIZED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_NON_OPTIMIZED_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_EXTRA_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_OPTIMIZED_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_LIFT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_COVERAGE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_BEHIND_SCHEDULE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_NO_CLICKS_RECORDED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_SPONSORSHIP_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_AS_FAST_AS_POSSIBLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_NO_ABSOLUTE_LIFETIME_GOAL_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_CONTROL_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_OPTIMIZED_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_CONTROL_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_OPTIMIZED_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_FREED_UP_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "OPTIMIZATION_ECPM_LIFT" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "REACH_FREQUENCY" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "REACH_AVERAGE_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "REACH" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "GRP_POPULATION" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "GRP_UNIQUE_AUDIENCE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "GRP_UNIQUE_AUDIENCE_SHARE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "GRP_AUDIENCE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "GRP_AUDIENCE_IMPRESSIONS_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "GRP_AUDIENCE_REACH" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "GRP_AUDIENCE_AVERAGE_FREQUENCY" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "GRP_GROSS_RATING_POINTS" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "SDK_MEDIATION_CREATIVE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "SDK_MEDIATION_CREATIVE_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "SELL_THROUGH_FORECASTED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "SELL_THROUGH_AVAILABLE_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "SELL_THROUGH_RESERVED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "SELL_THROUGH_SELL_THROUGH_RATE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_BACKUP_IMAGES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_DISPLAY_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_AVERAGE_DISPLAY_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_EXPANSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_EXPANDING_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_INTERACTION_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_INTERACTION_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_INTERACTION_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_AVERAGE_INTERACTION_TIME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_INTERACTION_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_MANUAL_CLOSES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_FULL_SCREEN_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_INTERACTIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_INTERACTION_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_MUTES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_PAUSES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_PLAYES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_MIDPOINTS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_COMPLETES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_REPLAYS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_STOPS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_UNMUTES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_VIEW_TIME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_VIDEO_VIEW_RATE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_CUSTOM_EVENT_TIME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "RICH_MEDIA_CUSTOM_EVENT_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_100_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_101_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_102_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_200_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_201_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_202_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_203_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_300_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_301_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_302_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_303_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_400_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_401_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_402_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_403_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_405_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_500_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_501_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_502_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_503_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_600_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_601_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_602_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_603_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_604_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_900_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "VIDEO_ERRORS_VAST_ERROR_901_COUNT" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "TOTAL_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_SERVER_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "ADSENSE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "AD_EXCHANGE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "UNIFIED_REVENUE_UNRECONCILED_VOLUME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "UNIFIED_REVENUE_FORECASTED_VOLUME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "UNIFIED_REVENUE_UNIFIED_VOLUME" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_POPULATION_BASE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_POPULATION_BASE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_UNIQUE_AUDIENCE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_UNIQUE_AUDIENCE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_PERCENT_AUDIENCE_REACH" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_PERCENT_AUDIENCE_REACH" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_AVERAGE_FREQUENCY" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_AVERAGE_FREQUENCY" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_GROSS_RATING_POINTS" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_GROSS_RATING_POINTS" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_PERCENT_IMPRESSIONS_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_PERCENT_IMPRESSIONS_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_PERCENT_POPULATION_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_PERCENT_POPULATION_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_PERCENT_AUDIENCE_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_PERCENT_AUDIENCE_SHARE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_AUDIENCE_INDEX" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_AUDIENCE_INDEX" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IMPRESSIONS_INDEX" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "NIELSEN_IN_TARGET_IMPRESSIONS_INDEX" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "DP_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "DP_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "DP_QUERIES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "DP_MATCHED_QUERIES" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "DP_COST" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_HOST_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_HOST_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_HOST_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_UNFILLED_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_CLICKS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_PARTNER_CTR" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_MANAGEMENT_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_HOST_IMPRESSIONS" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_HOST_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_HOST_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_PARTNER_REVENUE" =>
                        array (
                            "TYPE" => 'Integer',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_PARTNER_ECPM" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        ),
                    "PARTNER_FINANCE_GROSS_REVENUE" =>
                        array (
                            "TYPE" => 'Float',
                            'description' => '##ADD##',
                        )
                )
            )
        ),

    );
}
