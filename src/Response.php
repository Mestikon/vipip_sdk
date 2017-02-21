<?php
/**
 * Response class file
 */

namespace Vipip;

/**
 * Class Response
 * @package Vipip
 */
class Response {

    /**
     * @var int it is a digital representation of an error code
     */
    protected $ErrCode = 0;

    /**
     * @var string text of error message
     */
    protected $ErrMsg = "";

    /**
     * @var array|mixed|null attribute after request
     */
    protected $attributes = [];

    /**
     * Response constructor.
     * @param $answer
     */
    public function __construct($answer){
        $response = json_decode($answer, true);

        if( $response === null ){
            $this->ErrCode = "100";
            $this->ErrMsg = Message::t("Unable to decode JSON");
        }

        if( isset($response['error']) && $response['error'] ){
            $this->ErrCode = $response['error'];
            $this->ErrMsg = $response['errormsg'];
        }

        //nothing to do if error
        if( $this->isError() )
            return ;

        $this->attributes = $response;
    }

    /**
     * Getting error code
     * @return int
     */
    public function getErrCode(){
        return $this->ErrCode;
    }

    /**
     * Getting one error message
     * @return mixed|string
     */
    public function getErrMsg(){
        return is_array($this->ErrMsg) ? current($this->ErrMsg) : $this->ErrMsg;
    }

    /**
     * Get all error messages
     * @return string
     */
    public function getErrors(){
        return $this->ErrMsg;
    }

    /**
     * Whether the error was
     * @return bool
     */
    public function isError(){
        return (bool)$this->ErrCode;
    }

    /**
     * Getting attribute after request by name
     * @param $name
     * @param string $default
     * @return mixed|string
     */
    public function getAttribute($name, $default = ''){
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Getting attributes after request
     * @return array|mixed|null
     */
    public function getAttributes(){
        return $this->attributes;
    }
}