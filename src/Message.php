<?php
/**
 * Message class file
 */

namespace Vipip;

/**
 * Class Message
 * @package Vipip
 */
class Message{

    /**
     * @var string the language that the sdk is written in. Defaults to 'ru'
     */
    static protected $lang = 'ru';

    const messages_dir = __DIR__."/messages/";

    /**
     * @var array localization data
     */
    static protected $messages = [];

    /**
     * Setting language
     * @param $lang
     */
    static function setLang($lang){
        self::$lang = $lang;
    }

    /**
     * Translates a message to the specified language
     * @param $message
     * @param array $params
     * @param null $lang
     * @return string
     */
    static function t($message, $params = [], $lang = null){
        $lang = isset($lang) ? $lang : self::$lang;

        if( empty(self::$messages[$lang]) )
            self::loadMessages($lang);

        $message = isset(self::$messages[$lang][$message]) ? self::$messages[$lang][$message] : $message;

        if( $params == [] )
            return $message;

        return strtr($message, $params);
    }

    /**
     * Loading localization data
     * @param $lang
     * @return bool
     */
    static protected function loadMessages($lang){
        $path_to_messages = self::messages_dir.$lang.".php";

        if( !file_exists($path_to_messages) )
            return false;

        self::$messages[$lang] = require_once($path_to_messages);
        return true;
    }
}