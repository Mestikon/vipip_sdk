<?php
/**
 * Link class file
 */

namespace Vipip\Service;
use Vipip\Service\Settings\Calendar;
use Vipip\Service\Settings\InputReferer;
use Vipip\Service\Settings\LinkTariff;

/**
 * Class Link
 *
 * @property string $url
 * @property LinkTariff $tariff
 * @property integer $maxshowuser
 * @property integer $advside
 * @property integer $addtime
 * @property integer $intjumpid
 * @property integer $inputpoint_count
 * @property integer $referer_count
 * @property InputReferer $inputreferer
 * @property Calendar $calendar
 *
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
     * URL of service
     * @var string
     */
    protected $_url;

    /**
     * The number of times a link is shown to one visitor
     * @var integer
     */
    protected $_maxshowuser;

    /**
     * show captcha
     * @var integer
     */
    protected $_advside;

    /**
     * Additional link display time in seconds
     * @var integer
     */
    protected $_addtime;

    /**
     * depth of site viewing by a visitor
     * @var integer
     */
    protected $_intjumpid;

    /**
     * number of entry points
     * @var integer
     */
    protected $_inputpoint_count;

    /**
     * number of referers
     * @var integer
     */
    protected $_referer_count;

    /**
     * Getting tariff settings
     * @return mixed
     */
    public function getTariff(){
        return new LinkTariff($this);
    }
}