<?php
/**
 * Tariff class file
 */

namespace Vipip\Service\Settings;
use Vipip\Message;
use Vipip\Service\Service;
use Vipip\VipIP;
use Vipip\VipipObject;

/**
 * Class Tariff. Base class tariffs services
 *
 * @property integer $id
 * @property integer $code
 * @property string $title
 * @property string $titleshort
 * @property double $priceadv
 * @property integer $timer
 *
 * @package Vipip\Service\Settings
 */
abstract class Tariff extends VipipObject {

    /**
     * Name of module. Determined in a derived class
     * @var string
     */
    protected $vipip_moule;

    /**
     * Tariff identifier
     * @var integer
     */
    protected $_id;

    /**
     * tariff code
     * @var integer
     */
    protected $_code;

    /**
     * Name of tariff
     * @var string
     */
    protected $_title;

    /**
     * Short name of tariff
     * @var string
     */
    protected $_titleshort;

    /**
     * Price of tariff
     * @var double
     */
    protected $_priceadv;

    /**
     * Tariff timer
     * @var integer
     */
    protected $_timer;

    /**
     * Tariff constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        if( $service )
            $this->serviceInit($service);
    }

    /**
     * Initialization of the data service class
     * @param Service $service
     */
    protected function serviceInit(Service $service){
        $this->setTypeId($service->typeid);
    }

    /**
     * Setting tariff typeId
     * @param $id
     * @throws \Exception
     */
    public function setTypeId($id){
        $tariffs = VipIP::module($this->vipip_moule)->getTariffList();

        $tariffs = array_filter($tariffs, function($tariff) use ($id) {
            return $tariff['id'] == $id;
        });

        if( empty($tariffs) )
            throw new \Exception(Message::t("Unknown tariff '{tariffid}'", ['{tariffid}'=>$id]));

        $tariff = current($tariffs);

        $this->setAttributes($tariff);
    }

    /**
     * List the attributes required to fulfill the request
     * @return mixed
     */
    abstract public function getRequestAttributes();
}