<?php
/**
 * Adv class file
 */

namespace Vipip\AdvModule;

use Vipip\Object;
use Vipip\Response;
use Vipip\VipIP;

/**
 * Class Adv
 *
 * @property integer $advid
 * @property string $title
 * @property string $status
 * @property integer $groupUniq
 * @property integer $total_links
 * @property integer $active_links
 *
 * @package Vipip\AdvModule
 */
class Adv extends Object
{
    const STATUS_ENABLED = 'enabled';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Group identifier
     * @var integer
     */
    protected $_advid;

    /**
     * Name of group
     * @var string
     */
    protected $_title;

    /**
     * Status of group
     * @var string
     */
    protected $_status;

    protected $_groupUniq;

    protected $_total_links;

    protected $_active_links;

    /**
     * Adv constructor.
     * @param array $attributes
     */
    public function __construct($attributes = []){
        $this->setAttributes($attributes);
    }

    /**
     * Processing response from the server containing
     * the result of the structure of several services
     * @param $response
     * @return bool
     */
    protected function multipleResponse(Response $response){
        $newAttributes = $response->getAttribute("links");

        $newAttributes = $newAttributes[$this->linkid];

        if( isset($newAttributes['error']) && $newAttributes['error'] ){
            $this->setLastError($newAttributes['errormsg']);

            return false;
        }
        else{
            $this->setAttributes($newAttributes);
        }

        return true;
    }

    public function save($attributes){
        $attributes['advid'] = $this->advid;
        $response  = VipIP::put("adv", $attributes);
        $this->setAttributes($response->getAttributes());
        return true;
    }

    public function changeStatus($status){
        $attributes['advids'] = $this->advid;
        $attributes['status'] = $status;
        $response  = VipIP::put("adv/status", $attributes);
        $this->($response->getAttributes());
        return true;
    }
}