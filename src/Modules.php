<?php
/**
 * Modules class file
 */

namespace Vipip;

/**
 * Class Modules
 * @package Vipip
 */
class Modules {

    /**
     * @var array name-value pairs <module name> - <class>
     */
	static private $allowModules = [
		"user" => "Vipip\\User",
		"link" => "Vipip\\ServiceModule\\Link",
        "task" => "Vipip\\ServiceModule\\Task",
        "social" => "Vipip\\ServiceModule\\Social",
        "shuffle" => "Vipip\\ServiceModule\\Shuffle",
        "ctask" => "Vipip\\ServiceModule\\CTask"
	];

    /**
     * Getting module by name
     * @param $name
     * @return mixed
     */
	static function getModule($name){
		if( array_key_exists( $name, self::$allowModules ) ){
			return new self::$allowModules[$name]();
		}
	}
}