<?php
/**
 * InputRefererTrait trait file
 */

namespace Vipip\Service;
use Vipip\Service\Settings\InputReferer;
use Vipip\Vipip;

/**
 * Class InputRefererTrait
 * @package Vipip\Service
 */
trait InputRefererTrait {

    /**
     * Getting inputpoints and referers
     * @return InputReferer
     */
    public function getInputReferer(){
        return new InputReferer($this);
    }

    /**
     * Setting inputpoints and referers
     * @param InputReferer $inputReferer
     * @return bool
     */
    public function setInputReferer(InputReferer $inputReferer){
        $attr = $inputReferer->getRequestAttributes();

        $response  = Vipip::put($this->prefix."/inputreferer", array_merge($attr,[
                'linkid' => $this->linkid
            ])
        );

        $result = $response->getAttributes();

        $newAttr = [];
        $error = false;
        if( !$result['referer']['error'] ){
            $newAttr['referer_count'] = $result['referer']['count'];
        }
        else{
            $error = true;
            $this->setLastError($result['referer']['errormsg']);
        }
        if( !$result['inputpoint']['error'] ){
            $newAttr['inputpoint_count'] = $result['inputpoint']['count'];
        }
        else{
            $error = true;
            $this->setLastError($result['inputpoint']['errormsg']);
        }

        $this->setAttributes($newAttr);
        return !$error;
    }
}