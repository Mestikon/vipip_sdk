<?php
/**
 * ServiceModule class file
 */

namespace Vipip\ServiceModule;
use Vipip\Vipip;

/**
 * Class ServiceModule
 * @package Vipip\ServiceModule
 */
abstract class ServiceModule {

    /**
     * @var string classname of service. Determined in a derived class
     */
	protected $class_object;

    /**
     * @var string service prefix. Determined in a derived class
     */
    protected $prefix;

    /**
     * Getting the list of service
     * @param $ids array or comma separated string of services id
     * @return array list of services
     */
	public function getList($ids = ''){
        if( is_array($ids) ){
            $ids = implode(',', $ids);
        }

		$response = Vipip::get($this->prefix, ['linkids'=>$ids]);
        $links = $response->getAttributes();

		$collection = [];
		foreach($links as $attributes){
			$collection[] = new $this->class_object($attributes);
		}
		
		return $collection;
	}

    /**
     * Getting the service
     * @param $id
     * @return mixed|null
     */
	public function getOne($id){
        $links = $this->getList($id);
        return isset($links[0]) ? $links[0] : null;
    }
}