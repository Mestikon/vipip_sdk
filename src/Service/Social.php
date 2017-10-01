<?php
/**
 * Social class file
 */

namespace Vipip\Service;

/**
 * Class Social
 *
 * @property \Vipip\Service\Settings\SocialTariff $tariff
 * @property string $url
 * @property integer $age_min
 * @property integer $age_max
 * @property string $sex
 * @property integer $friends_id
 * @property string $params
 * @property \Vipip\Service\Settings\Calendar $calendar
 *
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
     * URL of service
     * @var string
     */
    protected $_url;

    /**
     * Minimum age of service execution
     * @var integer
     */
    protected $_age_min;

    /**
     * Maximum age of service execution
     * @var integer
     */
    protected $_age_max;

    /**
     * Sex of service execution
     * @var
     */
    protected $_sex;

    /**
     * Friends number setting ID
     * @var integer
     */
    protected $_friends_id;

    /**
     * Additional service parameters
     * @var string
     */
    protected $_params;
}