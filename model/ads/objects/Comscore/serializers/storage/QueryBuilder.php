<?php 
namespace model\ads\Comscore\serializers\Comscore\storage;

use \lib\php\ParametrizableString;
use \model\ads\lib\ApiCallParser;
use lib\datasource\BaseQueryBuilder;
use lib\storage\Comscore\ComscoreSerializerException;
use lib\storage\Comscore\ComscoreException;


class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{
       
    protected $config;
    
    protected $action;
    protected $params;
    protected $region;
    
    protected $api;
    protected $url;
    protected $method;
    protected $headers = [];
    protected $body;
    protected $soapParams;
    protected $waitForResult;
    protected $testing;
    
    protected $queryReady;
    
    protected $parser;
    
    const DO_NOT_WAIT = ["TestService", "SearchMedia"];
    const IS_TEST = ["TestService"];
    
    public function __construct($serializer, $definition, $params, $pagingParams)
    {
        global $Config;
        
        $this->config = $Config['SERIALIZERS']['comscore']['CONFIG'];
        parent::__construct($serializer, $definition, $params, $pagingParams);
        
        $this->parser = new ApiCallParser();
    }
    
    function build(?String $query=null, $onlyConditions=false)
    {
        
        $query = ParametrizableString::getParametrizedString($query, $this->data);
        
        $this->params = $this->parser->parse($query);
        $this->api    = $this->params['api'];
        $this->action = $this->params['call'];
        $this->type   = $this->params['params']['type']['value'];
        $this->region = $this->params['params']['region']['value'];
        $this->soapParams = null;
        
        $this->queryReady = false;
        
        switch (strtolower($this->api)) {
            case 'comscore':
                switch ($this->action) {
                    case 'requestReport':
                        $this->queryReady = $this->requestReport();
                        break;
                    case 'checkReport':
                        $this->queryReady = $this->checkReport($this->params['params']['report_id']);
                        break;
                    case 'getReport':
                        $this->queryReady = $this->getReport($this->params['params']['report_id']);
                        break;
                    default:
                        throw new \model\ads\ComscoreException(\model\ads\ComscoreException::ERR_INVALID_ACTION);
                    }
                break;
            case 'comscoredemographics':
                switch ($this->action) {
                    case 'SearchMedia':
                        $this->queryReady = $this->searchMedia();
                        break;
                    default:
                        $this->queryReady = $this->getDemographicsReport();
                }
                break;
            default:
                throw new \model\ads\ComscoreException(\model\ads\ComscoreException::ERR_UNKNOWN_API);
        }
        
        if ($this->queryReady) {
            return [
                'api'           => $this->api,
                'url'           => $this->url,
                'method'        => $this->method,
                'headers'       => $this->headers,
                'body'          => $this->body,
                'soapParams'    => $this->soapParams,
                'waitForResult' => $this->waitForResult,
                'testing'       => $this->testing??false,
            ];  
        } else {
            return false;
        }
    }
    
    protected function getBaseUrl()
    {
        return $this->getAuthData('url');
    }
    
    protected function getAuthData(?String $value=null)
    {
        switch (strtolower($this->api)) {
            case "comscore":
                $authData = $this->region;
                break;
            case "comscoredemographics":
                $authData = "demographics";
                break;
            default:
                throw new \model\ads\ComscoreException(\model\ads\ComscoreException::ERR_INVALID_ACTION);
        }
        
        if (is_null($value))
            return $this->config[$authData];
        else
            return $this->config[$authData][$value];
    }
    
    protected function getAuthHeader()
    {
        return "Basic ".base64_encode($this->getAuthData('user').":".$this->getAuthData('password'));
    }
    
    protected function requestReport()
    {
        $urlPattern = "[%base_url%]clients/[%client_id%]/jobs/reporting/[%type%]";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'client_id' => $this->getAuthData("client_id"),
            'type' => $this->type,
        ];
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams); 
        $this->method = "POST";
        $this->headers['Authorization'] = $this->getAuthHeader();
        $this->headers['Accept'] = 'application/json';
        $this->headers['Content-type'] = 'application/json';
        $params = [
            "responseMediaType" => "text/csv",
            "includeMobile"     => true,
            "campaignIds"       => $this->params['params']['campaigns']['value'],
            "clientId"          => $this->getAuthData("client_id"),
            "populationId"      => "724",   // TODO: parametrizar?
            "viewByType"        => "Total", // TODO: parametrizar?
            "startDate"         => date("m-d-Y", strtotime($this->params['params']['start_date']['value'])),
            "endDate"           => date("m-d-Y", strtotime($this->params['params']['end_date']['value'])),
        ];
        $this->body = json_encode($params, JSON_UNESCAPED_SLASHES);
        return true;
    }
    
    protected function checkReport($report_id)
    {
        $urlPattern = "[%base_url%]clients/[%client_id%]/jobs/reporting/[%report_id%]";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'client_id' => $this->getAuthData("client_id"),
            'report_id' => $report_id['value'],
        ];
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams);
        $this->method = "GET";
        $this->headers = [];
        $this->headers['Authorization'] = $this->getAuthHeader();
        $this->headers['Accept'] = 'application/json';
        return true;
    }
    
    protected function getReport($report_id)
    {
        $urlPattern = "[%base_url%]clients/[%client_id%]/jobs/reporting/[%report_id%]/result";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'client_id' => $this->getAuthData("client_id"),
            'report_id' => $report_id['value'],
        ];
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams);
        $this->method = "GET";
        $this->headers = [];
        $this->headers['Accept'] = 'text/csv';
        $this->headers['Authorization'] = $this->getAuthHeader();
        return true;
    }
    
    protected function getDemographicsReport()
    {
        $urlPattern = "[%base_url%][%action%].asmx?wsdl";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'action'   => $this->action,
        ];
        
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams);
        $this->method = "POST";
        $this->headers = [];
        $this->waitForResult = (!in_array($this->action, self::DO_NOT_WAIT));
        $this->testing = (in_array($this->action, self::IS_TEST));
        
        $this->soapParams = [
            'trace'      => true,
            'login'      => $this->getAuthData('user'),
            'password'   => $this->getAuthData('password'),
            //'header'     => "Content-Type: text/xml",
            'parameters' => [],
        ];
        foreach ($this->params['params'] as $param=>$value) {
            $this->soapParams['parameters'][$param] = $value['value'];
        }
        return true;
    }
    
    protected function searchMedia()
    {
        $urlPattern = "[%base_url%][%action%]";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'action'   => "/mediaratings/digital/v1/mmx/KeyMeasures/Media/media",
        ];
        
        $params = $this->params['params'];
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams);
        $this->method = "POST";
        $this->headers = [];
        $this->headers['Authorization'] = $this->getAuthHeader();
        $this->headers['Content-Type'] = 'application/json';
        $this->waitForResult = false;
        
        
        $params = [
            'fetchMediaQuery' => [
                'SearchCritera' => [
                    ['ExactMatch' => ($params['ExactMatch']['value']?true:false),
                        'Critera' => ($params['Critera']['value']??''),
                    ]
                ]
            ],
            'reportQuery' => [
                'Parameter' => [
                    [
                        'KeyId' => 'dataSource',
                        'Value' => $params['dataSource']['value']??25,
                    ],
                    [
                        'KeyId' => 'geo',
                        'Value' => $params['geo']['value']??724,
                    ],
                    [
                        'KeyId' => 'timeType',
                        'Value' => $params['timeType']['value']??1,
                    ],
                    [
                        'KeyId' => 'timePeriod',
                        'Value' => $params['timePeriod']['value']??$this->getLastMonth(),
                    ],
                    [
                        'KeyId' => 'mediaSetType',
                        'Value' => ($params['dataSource']['value']??'1'),
                    ],
                ],
            ],
        ];
        
        $this->body = json_encode($params, JSON_UNESCAPED_SLASHES);
        return true;
    }
    
    protected function getLastMonth() : String
    {
        $origin = new \DateTime("2000-01-01");
        $lastMonth = new \DateTime("-1 months");
        $diff = $origin->diff($lastMonth);
        return 12*$diff->y + $diff->m;
    }
    
    function getSerializerType()
    {
        return \lib\storage\Comscore\ComscoreSerializer::COMSCORE_SERIALIZER_TYPE;
    }
    
    function getDynamicParamValue($paramValue, $paramType)
    {
        // TODO: revisar para qué se usa esto en mysql y si hay un equivalente o devolver $paramValue directamente
        return ($paramType=="BOTH") ? "%$paramValue%" : "$paramValue%";
    }
    
    
    /*protected function createRequestContent($params, &$options)
    {
        if ($this->headers['Content-type']=="application/json") {
            $options['body'] = json_encode($params);
        } else {
            $options['form_params'] = $params;
        }
    }
    
    protected function parseParams()
    {
        $params = [];
        foreach ($this->params as $key=>$value) {
            if (is_string($this->params[$key])) {
                $params[$key] = ParametrizableString::getParametrizedString($value, $this->getAuthData());
            } else {
                $params[$key] = $value;
            }
        }
        return $params;
    }
    
    protected function getReport()
    {
        $options = [
            'headers' => $this->headers,
        ];
        switch ($this->method) {
            case "GET":
            case "PATCH":
            case "DELETE":
                $options['query'] = http_build_query($this->parseParams());
                break;
            case "PUT":
            case "POST":
                $this->createRequestContent($this->parseParams(), $options);
                break;
            default:
                throw new ComscoreException(ComscoreException::ERR_INVALID_METHOD);
        }
        return $options;
    }*/
    
    
    
}
