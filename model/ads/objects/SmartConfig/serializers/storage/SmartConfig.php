<?php
namespace model\ads\SmartConfig\serializers\SmartConfig\storage;

require_once(__DIR__.'/QueryBuilder.php');

use \lib\php\ParametrizableString;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\RequestException;
use \GuzzleHttp\Exception\ConnectException;
use model\ads\lib\ApiRequest\ApiRequest;

class SmartConfigException extends \lib\model\BaseException
{
     const ERR_INVALID_METHOD     = 1;
     const ERR_INVALID_ACTION     = 2;
     const ERR_EMTPY_RESPONSE     = 3;
     const ERR_NETWORK_ERROR      = 4;
     const ERR_INVALID_URL        = 5;
}


class SmartConfig extends ApiRequest
{
    const VALID_METHODS = ['GET', 'POST'];
    
    public function __construct(?Array $definition)
    {
        $this->definition = $definition;
    }
    
    public function connect()
    {
        return true;
    }
    
    public function getConnection()
    {
        //
    }
    
    
    public function setMethod(String $method)
    {
        $method = strtoupper($method);
        if (in_array($method, static::VALID_METHODS)) {
            $this->method = $method;
        } else  {
            throw new SmartConfigException(SmartConfigException::ERR_INVALID_METHOD);
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
        $options = $q;
        $url = $options['url'];
        $method = $options['method'];
        unset($options['url']);
        unset($options['method']);
        
        $client = new Client();
        
        try {
            $response = $client->request($method, $url, $options);
            $result = $response->getBody()->getContents();
            
            if ($method=="GET" && $result=="") {
                throw new SmartConfigException(SmartConfigException::ERR_EMTPY_RESPONSE);
            }
        } catch (SmartConfigException $e) {
            throw new SmartConfigException(SmartConfigException::ERR_NETWORK_ERROR);
        } catch (RequestException $e) {
            switch ($e->getCode()) {
                case 3:
                    throw new SmartConfigException(SmartConfigException::ERR_INVALID_URL);
                    break;
                default:
                    throw $e;
            }
        } finally {
            unset($client); // destruyo el cliente http
        }
        return $result;
    }
    
    
}

