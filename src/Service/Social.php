<?php
/**
 * Social class file
 */

namespace Vipip\Service;

/**
 * Class Social
 * @package Vipip\Service
 */
class Social extends Service {
    use CalendarTrait;

    /**
     * @var string service prefix name
     */
    protected $prefix = 'social';

    /**
     * @var string service tariff classname
     */
    protected $tariffClass = "Vipip\\Service\\Settings\\SocialTariff";

    /**
     * Social constructor.
     * @param array $attributes
     */
    public function __construct($attributes){
        $this->extendAttributes([
            'url' => [
                'readOnly' => true
            ],
            'age_min' => [
                'readOnly' => true
            ],
            'age_max' => [
                'readOnly' => true
            ],
            'sex' => [
                'readOnly' => true
            ],
            'friends_id' => [
                'readOnly' => true
            ],
            'params' => [
                'readOnly' => true
            ]
        ]);

        parent::__construct($attributes);
    }
}