<?php
/**
 * Service class file
 */

namespace Vipip\Service;
use Vipip\Response;
use Vipip\Service\Settings\Tariff;
use Vipip\Vipip;
use Vipip\Service\Settings\Geo;
use Vipip\Object;

/**
 * Class Service. Base class of service
 *
 * @property integer $linkid
 * @property string $title
 * @property string $status
 * @property integer $timetargetid
 * @property integer $placetargetid
 * @property integer $typeid
 * @property double $priceadv
 * @property integer $showInDay
 * @property integer $showedYesterday
 * @property double $balance
 * @property Tariff $tariff
 * @property Geo $geo
 *
 * @package Vipip\Service
 */
abstract class Service extends Object {

    /**
     * @var string service prefix name. Determined in a derived class
     */
    protected $prefix;

    /**
     * @var string service tariff classname. Determined in a derived class
     */
    protected $tariffClass;

    //replenish balance of services number of shows
    const BALANCE_TYPE_SHOWS = 1;
    //replenish balance of services number of money
    const BALANCE_TYPE_MONEY = 2;
    //replenish balance of services number of days
    const BALANCE_TYPE_DAYS = 3;

    const STATUS_ENABLED = 'enabled';
    const STATUS_DISABLED = 'disabled';

    /**
     * Service identifier
     * @var integer
     */
    protected $_linkid;

    /**
     * Service status
     * @var string
     */
    protected $_status;

    /**
     * Name of service
     * @var string
     */
    protected $_title;

    /**
     * Identifier calendar setting
     * @var integer
     */
    protected $_timetargetid;

    /**
     * Identifier geo setting
     * @var integer
     */
    protected $_placetargetid;

    /**
     * Identifier of tariff
     * @var integer
     */
    protected $_typeid;

    /**
     * Price of service
     * @var double
     */
    protected $_priceadv;

    /**
     * Number shows for the past day
     * @var integer
     */
    protected $_showInDay;

    /**
     * Number show per day
     * @var integer
     */
    protected $_showedYesterday;

    /**
     * Number show current day
     * @var integer
     */
    protected $_showedToday;

    /**
     * Balance of service
     * @var integer
     */
    protected $_balance;

    /**
     * last error message
     * @var string
     */
    protected $lastError;

    /**
     * Service constructor.
     * @param array $attributes
     */
	public function __construct($attributes = []){
		$this->setAttributes($attributes);
	}

    /**
     * Seeting last error message
     * @param $error
     */
	protected function setLastError($error){
        $this->lastError = $error;
    }

    /**
     * Getting last error message
     * @return mixed
     */
    public function getLastError(){
        return $this->lastError;
    }

    /**
     * Get prefix of service
     * @return mixed
     */
    public function getPrefix(){
        return $this->prefix;
    }

    /**
     * Sending a request to save new data
     * @param array $attributes
     * @return bool
     */
	public function save($attributes = []){
        $attributes['linkid'] = $this->linkid;
        $response  = VipIP::put($this->prefix, $attributes);
        $this->setAttributes($response->getAttributes());
        return true;
    }

    /**
     * Processing response from the server containing
     * the result of the structure of several services
     * @param $response
     * @return bool
     */
    protected function multipleResponse(Response $response){
        $newAttributes = $response->getAttribute("links");

        $newAttributes = $newAttributes[$this->linkid];

        if( isset($newAttributes['error']) && $newAttributes['error'] ){
            $this->setLastError($newAttributes['errormsg']);

            return false;
        }
        else{
            $this->setAttributes($newAttributes);
        }

        return true;
    }

    /**
     * Changing service balance
     * @param $number
     * @param int $type
     * @return bool
     */
    public function changeBalance($number, $type = self::BALANCE_TYPE_SHOWS){
        $response  = VipIP::put($this->prefix."/balance", [
            'linkids' => $this->linkid,
            'balance' => $number,
            'BalanceType'=>$type
        ]);

        return $this->multipleResponse($response);
    }

    /**
     * Changing service status
     * @param $status
     * @return bool
     */
    public function changeStatus($status){
        $response  = VipIP::put($this->prefix."/status", [
            'linkids' => $this->linkid,
            'status' => $status
        ]);

        return $this->multipleResponse($response);
    }

    /**
     * Sending a request to delete service
     */
    public function delete(){
        Vipip::delete($this->prefix, ['linkids'=>$this->linkid]);
    }

    /**
     * Setting tariff settings
     * @param Tariff $tariff
     */
    public function setTariff(Tariff $tariff){
        $attr = $tariff->getRequestAttributes();

        $response  = Vipip::put($this->prefix."/tariff", array_merge($attr,[
                'linkid' => $this->linkid
            ])
        );

        $this->setAttributes($response->getAttributes());
    }

    /**
     * Getting tariff settings
     * @return mixed
     */
    public function getTariff(){
        return new $this->tariffClass($this);
    }

    /**
     * Getting geography settings
     * @return Geo
     */
    public function getGeo(){
        return new Geo($this);
    }

    /**
     * Setting geography settings
     * @param Geo $geo
     * @return bool
     */
    public function setGeo(Geo $geo){
        $geoAttr = $geo->getRequestAttributes();

        $response  = Vipip::put($this->prefix."/placetarget", array_merge($geoAttr,[
                'linkid' => $this->linkid
            ])
        );

        $result= $response->getAttributes();

        if( !$result['error'] ) {
            $this->setAttributes($result);
            return true;
        }
        else
            return false;
    }
}