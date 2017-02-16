<?php
/**
 * LinkTariff class file
 */

namespace Vipip\Service\Settings;
use Vipip\Message;
use Vipip\Service\Service;
use Vipip\Vipip;

/**
 * Class LinkTariff
 * @package Vipip\Service\Settings
 */
class LinkTariff extends Tariff{

    /**
     * @var string name of module
     */
    protected $vipip_moule = 'link';

    const ADVSIDE_ONE_PLACE = 0;
    const ADVSIDE_DIFFERENT_PLACE = 1;

    /**
     * LinkTariff constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        $this->extendAttributes([
            'auto' => [
                'readOnly' => true
            ],
            // extra time
            "addtime" => [
                'value' => 0,
                'readOnly' => true
            ],
            // How to display a control picture 0 - in one place, 1 - in different locations
            "advside" => self::ADVSIDE_ONE_PLACE,
            // depth of view
            "intjumpid" => [
                'value' => 0,
                'readOnly' => true
            ],
            // the number of views per visitor
            "maxshowuser" => '1'
        ]);

        parent::__construct($service);
    }

    /**
     * Initialization of the data service class
     * @param Service $service
     */
    protected function serviceInit(Service $service){
        parent::serviceInit($service);

        $this->setAttributes([
            'addtime' => $service->addtime,
            'advside' => $service->advside,
            'intjumpid' => $service->intjumpid,
            'maxshowuser' => $service->maxshowuser
        ]);
    }

    /**
     * List the attributes required to fulfill the request
     * @return array
     */
    public function getRequestAttributes(){
        return [
            "typeid" => $this->id,
            "addtime" => $this->addtime,
            "advside" => $this->advside,
            "intjumpid" => $this->intjumpid,
            "maxshowuser" => $this->maxshowuser
        ];
    }

    /**
     * Setting additional time
     * @param $addtime
     * @throws \Exception
     */
    public function setAddTime($addtime){
        $addTimeList = Vipip::module($this->vipip_moule)->getAddTimeList();

        $addTimeList = array_filter($addTimeList, function($tariff) use ($addtime) {
            return $tariff['addtime'] == $addtime;
        });

        if( empty($addTimeList) )
            throw new \Exception(Message::t("The value of the parameter '{parameter}'={value} is incorrect", ['{parameter}'=>'addtime', '{value}'=>$addtime]));

        $this->setAttributes(['addtime'=>$addtime]);
    }

    /**
     * Setting the number of internal transitions
     * @param $intjumpid
     * @throws \Exception
     */
    public function setIntjumpid($intjumpid){
        $intjumpList = Vipip::module($this->vipip_moule)->getIntJumpList();

        $addTimeList = array_filter($intjumpList, function($tariff) use ($intjumpid) {
            return $tariff['intjumpid'] == $intjumpid;
        });

        if( empty($addTimeList) )
            throw new \Exception(Message::t("The value of the parameter '{parameter}'={value} is incorrect", ['{parameter}'=>'intjumpid', '{value}'=>$intjumpid]));

        $this->setAttributes(['intjumpid'=>$intjumpid]);
    }
}