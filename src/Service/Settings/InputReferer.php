<?php
/**
 * InputReferer class file
 */

namespace Vipip\Service\Settings;
use Vipip\Service\Service;
use Vipip\Vipip;
use Vipip\Object;

/**
 * Class InputReferer work with inputpoints and referers
 *
 * @property array $referer;
 * @property array $inputpoint;
 *
 * @package Vipip\Service\Settings
 */
class InputReferer extends Object {

    /**
     * List of referers
     * @var array
     */
    protected $_referer = [];

    /**
     * List of entry points
     * @var array
     */
    protected $_inputpoint = [];

    /**
     * InputReferer constructor.
     * @param Service|null $service
     */
    public function __construct(Service $service = null){
        if( $service ){
            $this->serviceInit($service);
        }
    }

    /**
     * Initialization of the data service class
     * @param Service $service
     */
    private function serviceInit(Service $service){
        $response = Vipip::get($service->getPrefix()."/inputreferer", [
            'linkid' => $service->linkid
        ]);

        $this->setAttributes($response->getAttributes());
    }

    /**
     * Adding referer
     * @param $url
     * @param int $weight
     */
    public function addReferer($url, $weight = 1){
        $referer = $this->referer;
        $referer[] = [
            'url' => $url,
            'weight' => $weight
        ];

        $this->setAttributes(['referer'=>$referer]);
    }

    /**
     * Adding inputpoint
     * @param $url
     * @param int $weight
     */
    public function addInputpoint($url, $weight = 1){
        $inputpoint = $this->inputpoint;
        $inputpoint[] = [
            'url' => $url,
            'weight' => $weight
        ];

        $this->setAttributes(['inputpoint'=>$inputpoint]);
    }

    /**
     * Make inputpoint empty
     */
    public function clearInputpoint(){
        $this->setAttributes(['inputpoint'=>[]]);
    }

    /**
     * Make referer empty
     */
    public function clearReferer(){
        $this->setAttributes(['referer'=>[]]);
    }

    /**
     * List the attributes required to fulfill the request
     * @return array
     */
    public function getRequestAttributes(){
        $inputpoint = $this->inputpoint;
        $referer = $this->referer;

        /**
         * Callback function.
         * @param $inputpoint
         * @return string
         */
        function requestToString($inputpoint){
            $res = array_map(function($item){
                return $item['weight']."|".$item['url'];
            }, $inputpoint);

            return implode("\n", $res);
        }

        $referer = requestToString($referer);
        $inputpoint = requestToString($inputpoint);

        return [
            "referer" => $referer,
            "inputpoint" => $inputpoint
        ];
    }
}