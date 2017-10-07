<?php
/**
 * LinkTariff class file
 */

namespace Vipip\Service\Settings;
use Vipip\Message;
use Vipip\Service\Link;
use Vipip\Service\Service;
use Vipip\Vipip;

/**
 * Class LinkTariff
 *
 * @property integer $auto
 * @property integer $addtime
 * @property integer $advside
 * @property integer $intjumpid
 * @property integer $maxshowuser
 *
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
     * Autosurfing
     * @var integer
     */
    protected $_auto;

    /**
     * Additional link display time in seconds
     * @var integer
     */
    protected $_addtime = 0;

    /**
     * show captcha
     * @var integer
     */
    public $advside = self::ADVSIDE_ONE_PLACE;

    /**
     * depth of site viewing by a visitor
     * @var integer
     */
    protected $_intjumpid = 0;

    /**
     * The number of times a link is shown to one visitor
     * @var integer
     */
    protected $_maxshowuser = 1;

    /**
     * Initialization of the data service class
     * @param Service $service
     */
    protected function serviceInit(Service $service){
        /* @var Link $service */
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