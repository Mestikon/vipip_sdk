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
 * @package Vipip\Service\Settings
 */
abstract class Tariff extends VipipObject {

    /**
     * @var string name of module. Determined in a derived class
     */
    protected $vipip_moule;

    /**
     * Tariff constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        $this->extendAttributes([
            'id' => [
                'readOnly' => true
            ],
            'code' => [
                'readOnly' => true
            ],
            'title' => [
                'readOnly' => true
            ],
            'titleshort' => [
                'readOnly' => true
            ],
            'priceadv' => [
                'readOnly' => true
            ],
            'timer' => [
                'readOnly' => true
            ]
        ]);

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