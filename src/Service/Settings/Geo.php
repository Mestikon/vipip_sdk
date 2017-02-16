<?php
/**
 * Geo clas file
 */

namespace Vipip\Service\Settings;
use Vipip\Service\Service;
use Vipip\Vipip;
use Vipip\VipipObject;

/**
 * Class Geo work with geography
 * @package Vipip\Service\Settings
 */
class Geo extends VipipObject {

    /**
     * Geo constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        $this->extendAttributes([
            'cities' => [
                'value' => [],
                'readOnly' => true
            ],
            'regions' => [
                'value' => [],
                'readOnly' => true
            ],
            'countries' => [
                'value' => [],
                'readOnly' => true
            ]
        ]);

        if( $service ){
            $this->serviceInit($service);
        }
    }

    /**
     * Initialization of the data service class
     * @param $service
     */
    private function serviceInit($service){
        $response = Vipip::get($service->getPrefix()."/placetarget", [
            'linkid' => $service->linkid
        ]);

        $this->setAttributes($response->getAttributes());
    }

    /**
     * Getting list of cities, regions and countries
     * @return array|mixed|null
     */
    static public function getList(){
        $cacheKey = "geolist";

        if(!$response = Vipip::getCache($cacheKey)){
            $response = Vipip::get("settings/placetargetlist");

            Vipip::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }

    /**
     * Getting information by city id
     * @param $id
     * @return mixed
     */
    static public function getCity($id){
        $list = self::getList();

        $filter = array_filter($list['cities'], function($city) use ($id){
            return $city['id'] == $id;
        });

        return current($filter);
    }

    /**
     * Getting information by region id
     * @param $id
     * @return mixed
     */
    static public function getRegion($id){
        $list = self::getList();

        $filter = array_filter($list['regions'], function($region) use ($id){
            return $region['id'] == $id;
        });

        return current($filter);
    }

    /**
     * Getting information by country id
     * @param $code
     * @return mixed
     */
    static public function getCountry($code){
        $list = self::getList();

        $filter = array_filter($list['countries'], function($country) use ($code){
            return $country['code'] == $code;
        });

        return current($filter);
    }

    /**
     * Setting cities id
     * @param array $cities
     */
    public function setCities(array $cities = []){
        $this->setAttributes(['cities' => $cities]);
    }

    /**
     * Setting regions id
     * @param array $regions
     */
    public function setRegions(array $regions = []){
        $this->setAttributes(['regions' => $regions]);
    }

    /**
     * Setting countries id
     * @param array $countries
     */
    public function setCountries(array $countries = []){
        $this->setAttributes(['countries' => $countries]);
    }

    /**
     * List the attributes required to fulfill the request
     * @return array
     */
    public function getRequestAttributes(){
        return [
            "cities" => $this->cities,
            "regions" => $this->regions,
            "countries" => $this->countries
        ];
    }
}