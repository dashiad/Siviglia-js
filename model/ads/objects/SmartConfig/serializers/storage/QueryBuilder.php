<?php
namespace model\ads\SmartConfig\serializers\storage;

use \lib\php\ParametrizableString;
use lib\datasource\BaseQueryBuilder;
use model\ads\SmartConfig\serializers\SmartConfigSerializer;
use model\ads\SmartConfig\serializers\SmartConfig\storage\SmartConfigException;


class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{
    
    public function __construct($serializer, $definition, $params=null, $pagingParams=null)
    {
        global $Config;
        $this->config = $Config['SERIALIZERS']['smartconfig'];
        
        parent::__construct($serializer, $definition, $params=null, $pagingParams=null);
    }
    
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

        $action = $query["definition"]["action"]??"getFileContent";
        
        switch ($action) {
            case "getFolderContent":
                $url = $this->config["BASE_URL"]."?action=".$action;
                break;
            case "getFileContent":
                $url = $this->config["BASE_URL"]."?action=".$action."&file=".$parameters['id'].".js";
                break;
            case "changeFileContent": // TODO: urlencode contenido
                $dataTemplate = "SMC.Config.process([%config%]);";
                $content = rawurlencode(ParametrizableString::getParametrizedString($dataTemplate, $parameters['config']));
                $url = $this->config["BASE_URL"];
                $headers = [
                    "Content-type" => "application/json",
                ];
                $body = json_encode([
                    "action" => "changeFileContent",
                    "token" => $this->config["SECRET"],
                    "file" => $parameters["id"],
                    "content" => $content,
                ]);
                $method = "POST";
                break;
            default:
                throw new SmartConfigException(\model\ads\SmartConfigException::INVALID_ACTION);
        }
        
        return [
            'url' => $url,
            'method'        => $method??"GET",
            'headers'       => $headers??[],
            'body'          => $body??"",
        ];
    }

    public function getDynamicParamValue($paramValue, $paramType)
    {
        //
    }

}
