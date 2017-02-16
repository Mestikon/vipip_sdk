<?php
/**
 * Link class file
 */

namespace Vipip\ServiceModule;
use Vipip\VipIP;

/**
 * Class Link
 * @package Vipip\ServiceModule
 */
class Link extends ServiceModule {

    use TariffTrait;

    /**
     * @var string classname of service
     */
	protected $class_object = "Vipip\\Service\\Link";

    /**
     * @var string service prefix
     */
    protected $prefix = 'links';

    /**
     * Create new link
     * @param $title - title of link
     * @param $url - url of link
     * @return \Vipip\Service\Link
     */
    public function create($title, $url){
        $request = VipIP::post($this->prefix, [
            'title' => $title,
            'url' => $url
        ]);

        return new \Vipip\Service\Link($request->getAttributes());
    }

    /**
     * Getting the list of additional time
     * @return array|mixed|null
     */
    public function getAddTimeList(){
        $cacheKey = $this->prefix."/addtime";

        if(!$response = VipIP::getCache($cacheKey)){
            $response = VipIP::get($this->prefix."/addtime");

            VipIP::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }

    /**
     * Getting the list of internal transitions
     * @return array|mixed|null
     */
    public function getIntJumpList(){
        $cacheKey = $this->prefix."/intjump";

        if(!$response = VipIP::getCache($cacheKey)){
            $response = VipIP::get($this->prefix."/intjump");

            VipIP::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }
}