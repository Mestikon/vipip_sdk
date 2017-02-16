<?php
/**
 * CalendarTrait trait file
 */

namespace Vipip\Service;
use Vipip\Service\Settings\Calendar;
use Vipip\Vipip;

/**
 * Class CalendarTrait
 * @package Vipip\Service
 */
trait CalendarTrait {

    /**
     * Getting calendar settings
     * @return Calendar
     */
    public function getCalendar(){
        return new Calendar($this);
    }

    /**
     * Setting calendar settings
     * @param Calendar $calendar
     * @return bool
     */
    public function setCalendar(Calendar $calendar){
        $attr = $calendar->getRequestAttributes();

        $response  = Vipip::put($this->prefix."/timetarget", array_merge($attr,[
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