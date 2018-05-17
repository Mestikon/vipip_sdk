<?php
/**
 * Adv module
 */

namespace Vipip\AdvModule;

use Vipip\VipIP;

class Module
{
    /**
     * @param string $ids
     * @param string $status
     * @return Adv[]
     */
    public function getList($ids = '', $status = ''){
        if( is_array($ids) ){
            $ids = implode(',', $ids);
        }

        $response = VipIP::get("adv", ['advids'=>$ids, 'status'=>$status]);
        $advs = $response->getAttributes();

        $collection = [];
        foreach($advs as $attributes){
            $collection[] = new Adv($attributes);
        }

        return $collection;
    }

    public function getOne($id){
        $advs = $this->getList($id);
        return isset($advs[0]) ? $advs[0] : null;
    }

    public function create($title, $groupUniq = 0){
        $response = VipIP::post("adv", ['title'=>$title, 'groupUniq'=>$groupUniq]);
        return new Adv($response->getAttributes());
    }
}