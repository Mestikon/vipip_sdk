<?php
/**
 * User class file
 */

namespace Vipip;

/**
 * User is class for user information request
 * @package Vipip
 */
class User {

    /**
     * Getting user`s balance
     * @return mixed|string
     */
	public function getBalance(){
        $response = VipIP::get("user/balance");

        return $response->getAttribute('balance');
	}

    /**
     * Getting user`s discount
     * @return mixed|string
     */
    public function getDiscount(){
        $response = VipIP::get("user/discount");

        return $response->getAttribute('discount');
    }
}