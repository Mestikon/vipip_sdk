<?php
require __DIR__."/../../../autoload.php";

use Vipip\VipIP;

VipIP::init("44.EPbbzK4ZnouwvSUpedD9iAvGzFbk7pnc", [
    'lang'=>'ru',
    'cache' => [
        'driver' => 'redis'
    ]
]);

try{
    $link = VipIP::module('social')->create('First social', 2, ['url'=>'https://vk.com/wt_fans']);

    $tariff = $link->getTariff();
    $tariff->age_min = 18;
    $tariff->age_max = 25;
    $tariff->sex = \Vipip\Service\Settings\SocialTariff::SEX_FEMALE;
    $tariff->friends_id = 300;
    $link->setTariff($tariff);

    //=== баланс ===
    if( !$link->changeBalance(150, \Vipip\Service\Social::BALANCE_TYPE_SHOWS) ){
        echo "Last error: ".$link->getLastError().PHP_EOL;
    }

    //=== статус ===
    if( !$link->changeStatus(\Vipip\Service\Social::STATUS_DISABLED) ){
        echo "Last error: ".$link->getLastError().PHP_EOL;
    }

    $info = <<<INFO
    linkid: \t$link->linkid
    title: \t$link->title
    url: \t$link->url
    status: \t$link->status
    balance: \t$link->balance
    priceadv: \t$link->priceadv
    tariff 
        title: \t$tariff->title
        age_min: \t$link->age_min
        age_max: \t$link->age_max
        friends_id: \t$link->friends_id
        sex: \t\t$link->sex

INFO;
    echo $info.PHP_EOL;
}
catch(Exception $e){
    echo "Catch: \n";
    echo "Code: {$e->getCode()} \n";
    echo "Message: {$e->getMessage()} \n";
}