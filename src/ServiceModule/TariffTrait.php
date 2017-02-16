<?php
/**
 * TariffTrait trait file
 */

namespace Vipip\ServiceModule;
use Vipip\VipIP;

/**
 * Class TariffTrait
 * @package Vipip\ServiceModule
 */
trait TariffTrait{

    /**
     * Getting tariff`s list
     * @return array|mixed|null
     */
    public function getTariffList(){
        $cacheKey = $this->prefix."/tarifflist";

        if(!$response = VipIP::getCache($cacheKey)){
            $response = VipIP::get($this->prefix."/tarifflist");

            VipIP::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }
}