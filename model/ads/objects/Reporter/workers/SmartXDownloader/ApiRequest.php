<?php
namespace model\ads\Reporter\workers\SmartXDownloader;

class ApiRequestException extends \Exception {
    
}

class ApiRequest {
    
    const VALID_METHODS =  [
        'GET',
        'POST',
        'PUT',
        'DELETE',
    ];
    const DEFAULT_TIMEOUT = 10*1000; // milliseconds
    
    protected $request;
    protected $method;
    protected $headers = [];
    protected $options = [];
    protected $params;
    
    protected $response = [
        'code' => null,
        'data' => null,
        'info' => null,
    ];
    
    public function __construct($url=null, $method="GET", $params=[], $options=[])
    {
        $this->options = $options;
        if (!is_null($url)) $this->setOption(CURLOPT_URL, $url);
        $this->params = $params;
        $this->method = $method;
        $this->timeout = self::DEFAULT_TIMEOUT;
    }
    
    private function connect() {
        $this->request = curl_init();
    }
    
    private function disconnect()
    {
        curl_close($this->request);
    }
    
    public function setUrl(String $url)
    {
        $this->setOption(CURLOPT_URL, $url);
    }
    
    public function getUrl() : String
    {
        return $this->options[CURLOPT_URL];
    }
    
    public function setMethod(String $method)
    {
        $method = strtoupper($method);
        if (in_array($method, static::VALID_METHODS)) {
            $this->method = $method;
        } else {
            throw new \Exception("Method not supported");
        }
    }
    
    public function getMethod() : String
    {
        return $this->method;
    }
    
    public function addHeader($header)
    {
        $this->headers[] = $header;
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }
    
    public function addBearerToken(String $token)
    {
        $this->addHeader("Authorization: Bearer $token");
    }
    
    public function setOption(String $option, $value)
    {
        $this->options[$option] = $value;
    }
    
    public function setOptions(Array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
    
    public function getOptions() : Array
    {
        return $this->options;
    }
    
    public function setTimeout(Int $seconds)
    {
        $this->timeout = $seconds * 1000;
    }
    
    protected function completeRequest()
    {
        switch ($this->method) {
            case 'GET':
                //case 'PUT':     // TODO: descomentar, comentado para pruebas en producción
                //case 'DELETE':
                $params = (count($this->params)) ? '?'.http_build_query($this->params) : '';
                $this->setUrl($this->getUrl().$params);
                break;
            case 'POST':
                $this->setOption(CURLOPT_POST, true);
                $this->setOption(CURLOPT_POSTFIELDS, http_build_query($this->params));
                break;
        }
        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->setOption(CURLOPT_TIMEOUT_MS, $this->timeout);
        $this->setOption(CURLOPT_HTTPHEADER, $this->headers);
        
        curl_setopt_array($this->request, $this->options);
    }
    
    public function exec()
    {
        $this->connect();
        $this->completeRequest();
        try {
            $response = curl_exec($this->request);
            $code = curl_getinfo($this->request, CURLINFO_HTTP_CODE);
            $err = curl_error($this->request);
            $this->response = [
                'code' => $code,
                'data' => $response,
                'info' => curl_getinfo($this->request),
                'err'  => $err,
            ];
            switch (intdiv($code, 100)) {
                case 0: // error de conexión de curl
                case 4: // http forbidden
                case 5: // http server error
                    throw new ApiRequestException($code.": ".$err, $code);
                    break;
            }
        } finally {
            $this->disconnect();
        }
        return $this->response;
    }
    
}