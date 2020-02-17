<?php
namespace model\ads\Reporter\workers\SmartXDownloader;

class SmartXApi {
    
    const CLIENT_ID         = "spain";
    const CLIENT_SECRET     = "a44114a990c12fef8c4b6e8028fbe20f";
    const USER_NAME         = "dgarcia@smartclip.com";
    const USER_PASSWORD     = "3XNUfqk2";
    const BOOKING_URL       = "https://smart-api-booking.sxp.smartclip.net/api/";
    const REPORTING_URL     = "https://smart-api-reporting.sxp.smartclip.net/api/";
    const TRANSCODING_URL   = "https://smart-api-transcoding.sxp.smartclip.net/api/";
    const FORECASTING_URL   = "https://smart-api-forecasting.sxp.smartclip.net/api/";
    const TEARSHEET_URL     = "https://smart-api-tearsheet.sxp.smartclip.net/api/";
    const AUTH_URL          = "https://smart-api-authentication.sxp.smartclip.net/api/token";
    const TIMEOUT           =  180; // seconds
    const REFRESH_PRELOAD   =   5; // minutes
    const MAX_ROWS_RETURNED = 100;
    
    private static $instance;
    
    protected static $url;
    protected static $client_id;
    protected static $client_secret;
    protected static $user_name;
    protected static $user_password;
    protected static $params;
    
    protected static $token = null;
    protected static $token_expiration;
    
    const TOKEN_FILE = __DIR__."/token.txt";
    
    protected function __construct() {}
    protected function __clone() {}
    protected function __wakeup() {}
    
    public static function getInstance(): SmartXApi
    {
        if (!isset(static::$instance)) {
            static::$instance    = new static();
            self::$url           = self::AUTH_URL;
            self::$client_id     = self::CLIENT_ID;
            self::$client_secret = self::CLIENT_SECRET;
            self::$user_name     = self::USER_NAME;
            self::$user_password = self::USER_PASSWORD;
            self::$params = [
                'client_id'     => self::$client_id,
                'client_secret' => self::$client_secret,
                'username'      => self::$user_name,
                'password'      => self::$user_password,
                'grant_type'    => 'password',
            ];
            static::getAuthToken();
        }
        return static::$instance;
    }
    
    public static function get($url, $params = [], $method = "GET", $options=[], $callback=null)
    {
        self::renewAuthTokenIfExpired();
        $request = new ApiRequest($url, $method, $params, $options);
        $request->setTimeout(self::TIMEOUT);
        $request->addBearerToken(self::$token);
        $request->setUrl(self::BOOKING_URL.$url);
        try {
            $result = $request->exec();
            $result['data'] = json_decode($result['data'], true);
            if (!isset($result['data']['count']) && isset($result['data']['id'])) {
                $result['data'] = [
                    'data'  => [$result['data']],
                    'count' => 1,
                ];
            }
            $result['data']['data'] = array_values($result['data']['data']);
        } catch (ApiRequestException $e) {
            if ($e->getCode()==401) { // si rechaza el token, lo elimino y genero uno nuevo
                static::clearAuthToken();
                static::getAuthToken();
                $result = static::get($url, $params, $method, $options, $callback);
            } else {
                throw $e;
            }
        }
        $result['data'] = self::__callback($callback, $result['data']);
        
        return $result ?? null;
    }
    
    public function getAll($url, $params = [], $method = "GET", $options=[], $callback=null)
    {
        $params['page_size'] = self::MAX_ROWS_RETURNED;
        $page = 0;
        $result = [
            'data'  => [],
        ];
        do {
            $params['page'] = ++$page;
            $response = $this->get($url, $params, $method, $options, null, true);
            if (!isset($numElements)) {
                $numElements = $response['data']['count'];
                $result['count'] = $numElements;
            }
            $lastElement = $page*self::MAX_ROWS_RETURNED;
            $result['data'] = array_merge($result['data'], $response['data']['data']);
        } while ($lastElement<$numElements);
        
        self::__callback($callback, $result);
        
        return $result;
    }
    
    protected static function __callback($callback, $result, $hasMetaData=true)
    {
        if (!empty($callback)) {
            $params = (array) $callback;
            $func = array_shift($params);
            if ($hasMetaData) $result = $result['data'];
            array_unshift($params, $result);
            $result = call_user_func_array($func, $params);
        }
        return $result;
    }
    
    protected static function loadAuthToken()
    {
        try {
            if (file_exists(self::TOKEN_FILE)) {
                $fileData = file_get_contents(self::TOKEN_FILE);
                $tokenInfo = json_decode($fileData);
                self::$token = $tokenInfo->token ?? null;
                self::$token_expiration = $tokenInfo->token_expiration ?? null;
            }
        } catch (\Exception $e) {
            //
        }
    }
    
    protected static function clearAuthToken()
    {
        self::$token = null;
        self::$token_expiration = null;
        $file = fopen(self::TOKEN_FILE, "w+");
        ftruncate($file, 0);
        fclose($file);
    }
    
    protected static function saveAuthToken()
    {
        try {
            $tokenInfo = new \stdClass;
            $tokenInfo->refresh_time = time();
            $tokenInfo->token = self::$token;
            $tokenInfo->token_expiration = self::$token_expiration;
            file_put_contents(self::TOKEN_FILE, json_encode($tokenInfo));
        } catch (\Exception $e) {
            //
        }
    }
    
    protected static function getAuthToken()
    {
        self::loadAuthToken();
        
        if (self::$token==null) {
            $request = new ApiRequest(self::AUTH_URL, "POST", self::$params);
            $request->addHeader('Content-Type: application/x-www-form-urlencoded');
            self::getAuthTokenInfo($request);
        }
        self::renewAuthTokenIfExpired();
    }
    
    protected static function renewAuthTokenIfExpired()
    {
        if (is_null(self::$token)) {
            self::getAuthToken();
        } else {
            $currentTime = strtotime("+".self::REFRESH_PRELOAD." minutes");
            $expirationTime = date(self::$token_expiration);
            if ($currentTime>$expirationTime) {
                try {
                    $request = new ApiRequest(self::AUTH_URL, "GET", self::$params);
                    $request->addBearerToken(self::$token);
                    self::getAuthTokenInfo($request);
                } catch (\Exception $e) { // sin no puede renovar el token se pide uno nuevo
                    self::$token = null;
                    self::$token_expiration = null;
                    self::saveAuthToken();
                    self::getAuthToken();
                } 
            }
        }
    }
    
    protected static function getAuthTokenInfo($request)
    {
        $response = $request->exec();
        if ($response['code']==201) {
            $result = json_decode($response['data']);
            self::$token = $result->access_token;
            self::$token_expiration = strtotime($result->expires);
            self::saveAuthToken();
        } else {
            throw new \Exception("Connection error");
        }
    }
}