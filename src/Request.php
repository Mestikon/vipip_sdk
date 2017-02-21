<?php
/**
 * Request class file
 */

namespace Vipip;

/**
 * Class Request. Sending api query structure
 * @package Vipip
 */
class Request {
	
	/**
	 * @const string Api version
	 */
	const VERSION = '0.1';
	
	/**
	 * @const string URL
	 */
	const API_URL = 'https://api.vipip.ru';
	
	/**
	 * @var string Authtorization token
	 */
	protected $token;

    /**
     * Request constructor.
     * @param string $token
     */
	public function __construct($token = null){
		if( $token )
		    $this->setAccessToken($token);
	}

    /**
     * Setting access_token
     * @param $token
     */
	public function setAccessToken($token){
        $this->token = $token;
    }

    /**
     * Sending api query
     * @param $endpoint
     * @param array $params
     * @param string $method
     * @return \Vipip\Response
     * @throws \Vipip\RequestException
     */
	public function request( $endpoint, $params = array(), $method = 'GET' ){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $url = $this->createPath($endpoint);
        $postfields = $this->getSendData($params);

        if( $method == "GET" ){
            if(!empty($postfields)) {
                $url = "{$url}?{$postfields}";
            }
        }
        else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        }

        curl_setopt($ch, CURLOPT_URL, $url);

		$answer = curl_exec($ch);

		if(!$answer){
            throw new RequestException(curl_error($ch), curl_errno($ch));
		}else{
            $response = new Response($answer);

            if( $response->isError() )
                throw new RequestException($response->getErrMsg(), $response->getErrCode());

            return $response;
		}
	}

    /**
     * Creating query path
     * @param $endpoint
     * @return string
     */
	protected function createPath( $endpoint ){
		return sprintf("%s/v%s/%s", self::API_URL, self::VERSION, $endpoint);
	}

    /**
     * The transformation parameters to the http query form.
     * Supplement acces token
     * @param $params
     * @return string
     * @throws \Vipip\RequestException
     */
	protected function getSendData($params){
        if( !$this->token )
            throw new RequestException(Message::t('Not specified access_token'), RequestException::NO_ACCESS_TOKEN);

		$params['access_token'] = $this->token;
		return http_build_query($params);
	}
}