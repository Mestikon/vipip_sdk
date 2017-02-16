<?php
/**
 * Calendar class file
 */

namespace Vipip\Service\Settings;
use Vipip\Message;
use Vipip\Service\Service;
use Vipip\Vipip;
use Vipip\VipipObject;

/**
 * Class Calendar
 * @package Vipip\Service\Settings
 */
class Calendar extends VipipObject {
    
    const TYPE_WEEK = 2;
    const TYPE_MONTH = 3;

    const WEEKDAY_SUNDAY = 0;
    const WEEKDAY_MONDAY = 1;
    const WEEKDAY_TUESDAY = 2;
    const WEEKDAY_WEDNESDAY= 3;
    const WEEKDAY_THURSDAY = 4;
    const WEEKDAY_FRIDAY = 5;
    const WEEKDAY_SATURDAY = 6;

    /**
     * Calendar constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        $this->extendAttributes([
            'timetarget' => [
                'readOnly' => true
            ],
            'timezone_id' => [
                'value' => 1,
                'readOnly' => true
            ],
            'type' => [
                'value' => self::TYPE_WEEK,
                'readOnly' => true
            ]
        ]);

        //regenirate timetarget
        $this->clear();

        if( $service ){
            $this->serviceInit($service);
        }
    }

    /**
     * Initialization of the data service class
     * @param $service
     */
    private function serviceInit($service){
        $response = Vipip::get($service->getPrefix()."/timetarget", [
            'linkid' => $service->linkid
        ]);

        $this->setAttributes($response->getAttributes());
    }

    /**
     * Setting type of calendar
     * @param $type
     */
    public function setType($type){
        $this->setAttributes(['type'=>$type]);

        $this->clear();
    }

    /**
     * clear and regenirate
     */
    public function clear(){
        $timetarget = [];

        if( $this->type == self::TYPE_WEEK ){
            $timetarget = array_fill(0, 7, ['']);
        }
        if( $this->type == self::TYPE_MONTH ){
            $timetarget = [''];
        }

        $this->setAttributes(['timetarget'=>$timetarget]);
    }

    /**
     * Setting the number of shows on the day of the week and hour (only type week)
     * @param $dweek
     * @param $hour
     * @param $number
     */
    public function setWeekDay($dweek, $hour=0, $number=0){
        if( $this->type == self::TYPE_WEEK ){
            $timetarget = $this->timetarget;

            //if the first parameter is passed as an array
            if( is_array($dweek) ){
                if(count($dweek)!=7){
                    throw new \Exception(Message::t("Wrong number of days of the week"));
                }

                $timetarget = $dweek;
            }
            //if the first parameter is the day of the week,
            //and the second array of settings by hours
            elseif(is_array($hour)){
                $dweek = ($dweek >= 0 && $dweek < 7) ? $dweek : self::WEEKDAY_MONDAY;
                $timetarget[$dweek] = $hour;
            }
            else{
                $dweek = ($dweek >= 0 && $dweek < 7) ? $dweek : self::WEEKDAY_MONDAY;

                $timetarget[$dweek][$hour] = $number;
            }
            $this->setAttributes(['timetarget'=>$timetarget]);
        }
    }

    /**
     * Setting the number of shows in a month (only type month)
     * @param $day
     * @param $month
     * @param $year
     * @param $count
     */
    public function setMonthDay($day, $month, $year, $count){
        if( $this->type == self::TYPE_MONTH ){
            $timetarget = $this->timetarget;

            $date = date("Ymd",gmmktime(0,0,0,$month,$day,$year));

            $timetarget[$date] = $count;

            $this->setAttributes(['timetarget'=>$timetarget]);
        }
    }

    /**
     * List the attributes required to fulfill the request
     * @return array
     */
    public function getRequestAttributes(){
        return [
            'timetarget' => $this->timetarget,
            'timezone_id' => $this->timezone_id,
            'type' => $this->type
        ];
    }

    /**
     * Getting a list of time zones
     * @return array|mixed|null
     */
    static public function getTimeZoneList(){
        $cacheKey = "timezonelist";

        if(!$response = Vipip::getCache($cacheKey)){
            $response = Vipip::get("settings/timezone");

            Vipip::setCache($response, $cacheKey, 86400);
        }

        return $response->getAttributes();
    }

    /**
     * Setting id of time zone
     * @param $id
     * @throws \Exception
     */
    public function setTimeZone($id){
        $list = self::getTimeZoneList();

        $zone = array_filter($list, function($timezone) use ($id) {
            return $timezone['id'] == $id;
        });

        if( empty($zone) )
            throw new \Exception(Message::t("Unknown time zone id '{id}'", ['{id}'=>$id]));
        else
            $this->setAttributes(['timezone_id'=>$id]);
    }
}