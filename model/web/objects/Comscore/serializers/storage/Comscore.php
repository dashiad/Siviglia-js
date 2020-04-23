<?php
namespace model\web\Comscore\serializers\Comscore\storage;

require_once(__DIR__.'/QueryBuilder.php');

use \lib\php\ParametrizableString;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ComscoreException extends \lib\model\BaseException
{
    const ERR_INVALID_METHOD     = 1;
    const ERR_INVALID_URL        = 2;
    const ERR_CONNECTION_FAILED  = 3;
    const ERR_FORBIDDEN          = 4;
    const ERR_AUTH_FAILURE       = 5;
    const ERR_INVALID_ACTION     = 6;
    const ERR_REQUEST_EXISTS     = 7;
}

class Comscore
{
    const VALID_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];   
    
    protected $region;
    protected $baseUrl;
    protected $url;
    protected $method  = "GET";
    protected $headers = [
        'Accept' => '*/*',
    ];
    protected $params  = [];
    protected $queryBuilder;
    
    public function __construct(?Array $definition)
    {
        $this->definition = $definition;
        $this->queryBuilder = new QueryBuilder(null, $definition);
       
    }
    
    
    
    public function setMethod(String $method)
    {
        $method = strtoupper($method);
        if (in_array($method, static::VALID_METHODS)) {
            $this->method = $method;
        } else  {
            throw new ComscoreException(ERR_INVALID_METHOD);
        }
        return $this;
    }
    
    public function getMethod() : String {
        return $this->method;
    }
    
    public function setUrl(String $url)
    {
        $this->url = $this->baseUrl.$url;
        return $this;
    }
    
    public function getParametrizedUrl() : String 
    {
        $params = array_merge($this->getAuthData(), $this->params);
        return $this->baseUrl.ParametrizableString::getParametrizedString($this->url, $params);
    }
    
    public function getParams() : ?Array 
    {
        return $this->params;
    }
    
    public function connect()
    {
        // $this->conn = ?
    }
    
    public function getConnection()
    {
        //return $this->conn;
    }
    
    protected function createRequestContent($params, &$options)
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
    
    public function request($q="", $field="")
    {
        $options = $this->queryBuilder->build($q);
        $url = $options['url'];
        $method = $options['method'];
        unset($options['url']);
        unset($options['method']);
        $client = new Client();
        
        try {
            $response = $client->request($method, $url, $options);
            $result = $response->getBody()->getContents();
        } catch (RequestException $e) {
            switch ($e->getCode()) {
                case 3:
                    throw new ComscoreException(ComscoreException::ERR_INVALID_URL);
                    break;
                case 409:
                    $result = $e->getResponse()->getBody()->getContents();
                    break;
                default:
                    throw $e;
            }
        }
        return $result;
    }
    
}
