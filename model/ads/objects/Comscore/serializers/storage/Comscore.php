<?php
namespace model\ads\Comscore\serializers\Comscore\storage;

require_once(__DIR__.'/QueryBuilder.php');

use \lib\php\ParametrizableString;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use model\ads\lib\ApiRequest\ApiRequest;
use model\ads\lib\ApiRequest\ApiRequestException;

class ComscoreException extends \lib\model\BaseException
{
    const ERR_REQUEST_EXISTS     = 1;
}

class Comscore extends ApiRequest
{
    const VALID_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];   

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
        } catch (ConnectException $e) {
            throw new ApiRequestException(ApiRequestException::ERR_NETWORK_ERROR);
        } catch (RequestException $e) {
            switch ($e->getCode()) {
                case 3:
                    throw new ApiRequestException(ApiRequestException::ERR_INVALID_URL);
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
