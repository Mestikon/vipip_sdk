<?php
/**
 * Social class file
 */

namespace Vipip\ServiceModule;
use Vipip\Vipip;

/**
 * Class Social
 * @package Vipip\ServiceModule
 */
class Social extends ServiceModule{
    use TariffTrait;

    /**
     * @var string classname of service
     */
	protected $class_object = "Vipip\\Service\\Social";

    /**
     * @var string service prefix
     */
    protected $prefix = 'social';

    /**
     * Create new social
     * @param $title
     * @param $typeid
     * @param array $attr
     * @return \Vipip\Service\Social
     */
    public function create($title, $typeid, $attr = []){
        //to check the correctness of typeid
        $tariff = new \Vipip\Service\Settings\SocialTariff();
        $tariff->setTypeId($typeid);

        $request = Vipip::post($this->prefix, array_merge([
            'title' => $title,
            'typeid' => $typeid
        ], $attr));

        return new \Vipip\Service\Social($request->getAttributes());
    }

    /**
     * Getting list of friends settings
     * @return array|mixed|null
     */
    public function getFriendsList(){
        $cacheKey = $this->prefix."/friends";

        if(!$response = Vipip::getCache($cacheKey)){
            $response = Vipip::get($this->prefix."/friends");

            Vipip::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }
}