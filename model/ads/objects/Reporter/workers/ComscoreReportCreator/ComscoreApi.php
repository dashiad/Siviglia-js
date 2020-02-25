<?php
namespace model\ads\Reporter\workers\ComscoreReportCreator;

use model\ads\Reporter\workers\SmartXDownloader\ApiRequest;

class ComscoreApi
{       
    // TODO: mover a fichero de configuración
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
    ];
    
    /**
     * 
     * @param String $region
     * @return array
     */
    protected function getAuthData(String $region) : Array
    {
        return self::AUTH_DATA[$region]; // TODO: sacarlo de fichero de configuración 
    }
    
    protected function getAuthString(String $region) : String
    {
        $authData = $this->getAuthData($region);
        return base64_encode($authData['user'] . ':' . $authData['password']);
    }
    
    /**
     * 
     * @param String $url
     * @param array $params
     * @param String $region
     * @return Array
     */
    public function createReportingJob(String $url, Array $params=[], String $region='spain') : Array
    {
        $authData = $this->getAuthData($region);
        $api = new ApiRequest();
        $url = $authData['url']."clients/".$authData['client_id']."/".$url;
        $api->jsonBody = true;
        
        $api->addHeader('Accept: application/json');
        $api->addHeader('Content-Type: application/json');
        $api->addHeader('Authorization: Basic ' . $this->getAuthString($region));
        $api->setMethod("POST");
        $api->setUrl($url);
        $api->setParam("responseMediaType", 'text/csv');
        $api->setParam("clientId", $authData['client_id']);
        $api->setParam("includeMobile", true);
        $api->setParam("populationId", 724); // FIXME: should depend on region/account! (???)
        foreach ($params as $name=>$value)
            $api->setParam($name, $value);
        return $api->exec();
    }
    
    /**
     * 
     * @param String $id
     * @param String $region
     * @return array
     */
    public function getReportingJob(String $id, String $region='spain') : Array
    {
        $authData = $this->getAuthData($region);
        $api = new ApiRequest();
        $api->addHeader('Authorization: Basic ' . $this->getAuthString($region));
        $api->setMethod("GET");
        $url = $authData['url']."clients/".$authData['client_id']."/jobs/reporting/".$id;
        $api->setUrl($url);
        return $api->exec();
    }
    
    /**
     * 
     * @param String $id
     * @param String $region
     * @return NULL[]|mixed[]|string[]
     */
    public function deleteReportingJob(String $id, String $region='spain') : Array
    {
        $authData = $this->getAuthData($region);
        $api = new ApiRequest();
        $api->setMethod("DELETE");
        $api->addHeader('Authorization: Basic ' . $this->getAuthString($region));
        $url = $authData['url']."clients/".$authData['client_id']."/jobs/reporting/".$id;
        $api->setUrl($url);
        return $api->exec();
    }
    
    /**
     * 
     * @param String $id
     * @param String $region
     * @return array
     */
    public function getReportingJobResult(String $id, String $region='spain') : Array
    {
        $authData = $this->getAuthData($region);
        $api = new ApiRequest();
        $api->setMethod("GET");
        $api->addHeader('Authorization: Basic ' . $this->getAuthString($region));
        $api->addHeader('Accept: text/csv');
        $url = $authData['url']."clients/".$authData['client_id']."/jobs/reporting/".$id."/result";
        $api->setUrl($url);
        return $api->exec();
    }
   
    /**
     * 
     * @param array $params
     * @param String $region
     * @return array
     */
    public function locateReportingJob(Array $params, String $region='spain') : Array
    {
        $authData = $this->getAuthData($region);
        $api = new ApiRequest();
        $api->addHeader('Authorization: Basic ' . $this->getAuthString($region));
        $api->setMethod("GET");
        // TODO: cargar trabajos creados, buscar uno con los mismos parámetros y eliminar
    }
   
}

