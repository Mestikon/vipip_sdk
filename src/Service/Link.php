<?php
/**
 * Link class file
 */

namespace Vipip\Service;

/**
 * Class Link
 * @package Vipip\Service
 */
class Link extends Service {
    use InputRefererTrait;
    use CalendarTrait;

    /**
     * @var string service prefix name
     */
    protected $prefix = 'links';

    /**
     * @var string service tariff classname
     */
    protected $tariffClass = "Vipip\\Service\\Settings\\LinkTariff";

    /**
     * Link constructor.
     * @param array $attributes
     */
    public function __construct($attributes){
        $this->extendAttributes([
            'url' => [
                'readOnly' => true
            ],
            'maxshowuser' => [
                'readOnly' => true
            ],
            'advside' => [
                'readOnly' => true
            ],
            'addtime' => [
                'readOnly' => true
            ],
            'intjumpid' => [
                'readOnly' => true
            ],
            'inputpoint_count' => [
                'readOnly' => true
            ],
            'referer_count' => [
                'readOnly' => true
            ]
        ]);

        parent::__construct($attributes);
    }
}