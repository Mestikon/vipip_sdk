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

    protected $_url;
    protected $_age_min;
    protected $_age_max;
    protected $_sex;
    protected $_friends_id;
    protected $_params;
}