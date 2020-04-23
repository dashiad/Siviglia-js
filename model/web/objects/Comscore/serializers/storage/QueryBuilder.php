<?php 
namespace model\web\Comscore\serializers\Comscore\storage;;

use \lib\php\ParametrizableString;

class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{
       
    const AUTH_DATA = [
        'spain' => [
            'url'       => 'https://adeffx-api.comscore.com/api/v3/',
            'client_id' => '18259431',
            'user'      => 'sp5_asmartclip',
            'password'  => '93ba6f07',
        ],
        'latam' => [
            'url'       => 'https://adeffx-api.comscore.com/api/v3/',
            'client_id' => '24939011',
            'user'      => 'agb_asmartclip',
            'password'  => '1cf7e3a5',
        ],
    ]; // TODO: mover configuraciones a un sitio más razonable
    
    protected $action;
    protected $params;
    protected $region;
    
    protected $url;
    protected $method;
    protected $headers = [];
    protected $body;
    
    protected $queryReady;
    
    function build(?Array $q=null, $onlyConditions=false)
    {
        $this->action = $q['action'];
        $this->params = $q['params'];
        $this->type = $q['type'];
        $this->region = $this->params['region'];
        $this->queryReady = false;
        
        switch ($this->action) {
            case 'requestReport':
                $this->queryReady = $this->requestReport();
                break;
            case 'checkReport':
                $this->queryReady = $this->checkReport($this->params['report_id']);
                break;
            case 'getReport':
                $this->queryReady = $this->getReport($this->params['report_id']);
                break;
            default:
                throw new ComscoreException(ComscoreException::ERR_INVALID_ACTION);
        }
        if ($this->queryReady) {
            return [
                'url'     => $this->url,
                'method'  => $this->method,
                'headers' => $this->headers,
                'body'    => $this->body,
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
        if (is_null($value))
            return static::AUTH_DATA[$this->region];
        else
            return static::AUTH_DATA[$this->region][$value];
    }
    
    protected function getAuthHeader()
    {
        return "Basic ".base64_encode($this->getAuthData('user').": ".$this->getAuthData('password'));
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
            "campaignIds"       => $this->params['campaigns'],
            "clientId"          => $this->getAuthData("client_id"),
            "populationId"      => "724",
            "viewByType"        => "Total", // TODO: parametrizar?
            "startDate"         => date("m-d-Y", strtotime($this->params['start_date'])),
            "endDate"           => date("m-d-Y", strtotime($this->params['end_date'])),
        ];
        $this->body = json_encode($params);
        return true;
    }
    
    protected function checkReport($report_id)
    {
        $urlPattern = "[%base_url%]clients/[%client_id%]/jobs/reporting/[%report_id%]";
        $urlParams = [
            'base_url' => $this->getBaseUrl(),
            'client_id' => $this->getAuthData("client_id"),
            'report_id' => $report_id,
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
            'report_id' => $report_id,
        ];
        $this->url = ParametrizableString::getParametrizedString($urlPattern, $urlParams);
        $this->method = "GET";
        $this->headers = [];
        $this->headers['Accept'] = 'text/csv';
        $this->headers['Authorization'] = $this->getAuthHeader();
        return true;
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
