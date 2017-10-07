<?php
/**
 * VipipObject class file
 */

namespace Vipip;

/**
 * Class VipipObject base object of SDK
 * @package Vipip
 */
abstract class Object{

    /**
     * It is set to a true value, if necessary, change the properties of a read-only
     * @var bool
     */
    private $safeMode = false;

    /**
     * Setting attributes value
     * @param $attributes
     */
    protected function setAttributes($attributes){
        $this->safeMode = true;
        foreach($attributes as $name => $val){
            $this->$name = $val;
        }
        $this->safeMode = false;
    }

    /**
     * Getter
     * @param $name
     * @return null
     */
    public function __get($name){
        if( method_exists($this, 'get'.$name) ){
            return call_user_func([$this, 'get'.$name]);
        }
        elseif( property_exists($this, "_".$name) )
            return $this->{"_".$name};

        return null;
    }

    /**
     * Setter
     * @param $name
     * @param $val
     * @throws \Exception
     */
    public function __set($name, $val){
        //Safe mode does not check the assignment methods
        if( method_exists($this, 'set'.$name) && !$this->safeMode ){
            call_user_func([$this, 'set'.$name], $val);
        }
        else{
            if( property_exists($this, "_".$name) ){
                if ($this->safeMode) {
                    $this->{"_" . $name} = $val;
                }
                else{
                    throw new \Exception(Message::t("Read-only property '{property}'", ['{property}'=>$name]));
                }
            }
            else{
                //in the safe mode we ignore the nonexistent property
                if (!$this->safeMode) {
                    throw new \Exception(Message::t("Unknown property '{property}'", ['{property}' => $name]));
                }
            }
        }
    }
}