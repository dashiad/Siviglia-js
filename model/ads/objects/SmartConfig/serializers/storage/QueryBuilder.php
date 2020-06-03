<?php
namespace model\ads\SmartConfig\serializers\storage;

use \lib\php\ParametrizableString;
use lib\datasource\BaseQueryBuilder;
use model\ads\SmartConfig\serializers\SmartConfigSerializer;
use model\ads\SmartConfig\serializers\SmartConfig\storage\SmartConfigException;


class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{
    
    // TODO: mover a config
    const BASE_URL = "http://cdn.smartclip-services.com/v1/Storage-a482323/smartclip-services/HeaderBidding/js/configs/editor/entryPoint.php";
    const SECRET = "Lu9eil3Eek3eemi3ahvei,Y3gah9aGie";
    
    public function getSerializerType()
    {
        return SmartConfigSerializer::SMARTCONFIG_SERIALIZER_TYPE;
    }

    public function build(?Array $query=null, $onlyConditions=false)
    {
        if ($onlyConditions) {
            $parameters = $query;
        } else {
            $parameters = $query['parameters'];
        }

        if (!isset($query["action"])) {
            $query["action"] = "getFileContent";
        }
        
        switch ($query["action"]) {
            case "getFolderContent":
                $url = self::BASE_URL."?action=".$query['action'];
                $method = "GET";
                break;
            case "getFileContent":
                $url = self::BASE_URL."?action=".$query['action']."&file=".$parameters['domain'].".js";
                $method = "GET";
                break;
            case "changeFileContent": // TODO: urlencode contenido
                $url = self::BASE_URL."?action=".$query['action']."&secret=".self::SECRET."file=".$parameters['domain'].".js&content=".$parameters['content'];
                $method = "POST";
                break;
            default:
                throw new SmartConfigException(\model\ads\SmartConfigException::INVALID_ACTION);
        }
        
        return [
            'url' => $url,
            'method'        => $method,
            'headers'       => [],
            'body'          => "",
        ];
    }

    public function getDynamicParamValue($paramValue, $paramType)
    {
        //
    }

}

/*
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
    }*/