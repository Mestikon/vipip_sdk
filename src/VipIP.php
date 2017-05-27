<?php
/**
 * Vipip class file
 */

namespace Vipip;
use phpFastCache\CacheManager;

/**
 * Vipip is the base class for SDK
 * @package Vipip
 */
class VipIP {

    const VESRION = '0.1.3';

	/**
	 * @var string Authtorization token
	 */
	static protected $token;

    /**
     * @var array Configuration
     */
    static protected $config = [
        'lang' => 'en',
        'cache' => [
            'driver' => 'files',
            'config' => []
        ]
    ];

    /**
     * @var \Vipip\Request instance of request class
     */
    static private $request;
	
	/**
	 * @var array modules list
	 */
    static private $modules = [];

    /**
     * @var ExtendedCacheItemPoolInterface instance of cache class
     */
    static private $cache;

    /**
     * Vipip initialize.
     * @param string $token - request token
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
	static public function init($token, array $config = []){
        self::$request = new Request();

        self::setToken($token);

        if( !empty($config) ) {
            self::$config = array_replace_recursive(self::$config, $config);
        }

        Message::setLang(self::$config['lang']);
        self::$cache = CacheManager::getInstance(self::$config['cache']['driver'], self::$config['cache']['config']);
    }

    /**
     * Setting request token
     * @param $token
     */
	static public function setToken($token){
        self::$token = $token;
        self::$request->setAccessToken(self::$token);
    }

    /**
     * Executes request
     * @param $method
     * @param $endpoint
     * @param array $params
     * @return Response
     */
    static public function sendRequest($method, $endpoint, $params = []){
        return self::$request->request($endpoint, $params, $method);
    }

    /**
     * Executes request GET
     * @param $endpoint
     * @param array $params
     * @return Response
     */
    static public function get($endpoint, $params = []){
        return self::sendRequest("GET", $endpoint, $params);
    }

    /**
     * Executes request POST
     * @param $endpoint
     * @param array $params
     * @return Response
     */
    static public function post($endpoint, $params = []){
        return self::sendRequest("POST", $endpoint, $params);
    }

    /**
     * Executes request PUT
     * @param $endpoint
     * @param array $params
     * @return Response
     */
    static public function put($endpoint, $params = []){
        return self::sendRequest("PUT", $endpoint, $params);
    }

    /**
     * Executes request DELETE
     * @param $endpoint
     * @param array $params
     * @return Response
     */
    static public function delete($endpoint, $params = []){
        return self::sendRequest("DELETE", $endpoint, $params);
    }

    /**
     * Getting module
     * @param $name
     * @return mixed
     */
	static public function module($name){
		if(!array_key_exists($name, self::$modules)){
			self::$modules[$name] = Modules::getModule($name);
		}
		
		return self::$modules[$name];
	}

    /**
     * Setting the value to the cache
     * @param $value
     * @param $key
     * @param int $ttl
     */
	static public function setCache($value, $key, $ttl = 60*5){
        $CachedString = self::$cache->getItem(self::createCacheKey($key));

        $CachedString->set($value)->expiresAfter($ttl);
        self::$cache->save($CachedString);
    }

    /**
     * Getting the value from the cache
     * @param $key
     * @return mixed
     */
    static public function getCache($key){
        $CachedString = self::$cache->getItem(self::createCacheKey($key));

        return $CachedString->get();
    }

    /**
     * Creating cache key
     * @param $key
     * @return string
     */
    static private function createCacheKey($key){
        return md5("vipip_sdk".self::VESRION.$key);
    }
}