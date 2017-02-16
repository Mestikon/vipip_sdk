<?php
/**
 * VipipObject class file
 */

namespace Vipip;

/**
 * Class VipipObject base object of SDK
 * @package Vipip
 */
abstract class VipipObject{

    /**
     * @var array
     * name => [
     *      'value' => <value>,
     *      'readOnly' => <bool> (default) false
     * ]
     */
    protected $attributes = [];

    /**
     * It is set to a true value, if necessary, change the properties of a read-only
     * @var bool
     */
    private $safeMode = false;

    /**
     * Expanding the object properties list
     * @param $attributes
     */
    protected function extendAttributes($attributes){
        foreach($attributes as $name => $val){
            if( is_array($val) ) {
                $val['value'] = isset($val['value']) ? $val['value'] : '';
                $val['readOnly'] = isset($val['readOnly']) ? $val['readOnly'] : false;
                $this->attributes[$name] = $val;
            }
            else
                $this->attributes[$name] = [
                    'value' => $val,
                    'readOnly' => false
                ];
        }
    }

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
        if( array_key_exists($name, $this->attributes) )
            return $this->attributes[$name]['value'];

        return null;
    }

    /**
     * Setter
     * @param $name
     * @param $val
     */
    public function __set($name, $val){
        //Safe mode does not check the assignment methods
        if( method_exists($this, 'set'.$name) && !$this->safeMode ){
            call_user_func([$this, 'set'.$name], $val);
        }
        else{
            if( array_key_exists($name, $this->attributes) ) {
                if (!$this->attributes[$name]['readOnly'] || $this->safeMode)
                    $this->attributes[$name]['value'] = $val;
                else{
                    throw new \Exception(Message::t("Read-only property '{property}'", ['{property}'=>$name]));
                }
            }
        }
    }
}