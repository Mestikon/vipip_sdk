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
    $link = VipIP::module('link')->create("My first link", "http://example.com");

    //Tariff
    $tariff = $link->getTariff();
    //id value from VipIP::module('link')->getTariffList();
    $tariff->typeid = 2;
    //addtime value from VipIP::module('link')->getAddTimeList();
    $tariff->addtime = 60;
    //intjumpid value from VipIP::module('link')->getIntJumpList();
    $tariff->intjumpid = 3;
    $tariff->advside = \Vipip\Service\Settings\LinkTariff::ADVSIDE_DIFFERENT_PLACE;
    $link->setTariff($tariff);

    //Inputpoints and referers
    $inp_ref = $link->getInputReferer();
    $inp_ref->addInputpoint("http://example.com/page/43", 2);
    $inp_ref->addInputpoint("http://example.com/gallery", 3);
    $inp_ref->addReferer("http://www.google.ru/?#newwindow=1&q=exmple.com", 5);
    $inp_ref->addReferer("http://www.yandex.ru/search/?text=exmple.com&lr=225&ncrnd=8626");
    if( !$link->setInputReferer($inp_ref) ){
        echo "Last error: ".$link->getLastError() . PHP_EOL;
    }

    //Geography
    //geo data \Vipip\Service\Settings\Geo::getList();
    $geo = $link->getGeo();
    $geo->setCities([41, 15]);
    $geo->setRegions([3]);
    $geo->setCountries(['UA', 'KZ']);
    $link->setGeo($geo);

    //Calendar
    $cal = $link->getCalendar();
    $cal->setType(\Vipip\Service\Settings\Calendar::TYPE_WEEK);
    $cal->setWeekDay(\Vipip\Service\Settings\Calendar::WEEKDAY_MONDAY, 0, 15);
    $cal->setWeekDay(\Vipip\Service\Settings\Calendar::WEEKDAY_MONDAY, 1, 17);
    //...
    $cal->setWeekDay(\Vipip\Service\Settings\Calendar::WEEKDAY_SUNDAY, 23, 91);

    //timezone id \Vipip\Service\Settings\Calendar::getTimeZoneList();
    $cal->timezone_id = 4;
    $link->setCalendar($cal);

    if( !$link->changeBalance(250, \Vipip\Service\Service::BALANCE_TYPE_SHOWS) ){
        echo $link->getLastError() . PHP_EOL;
    }

    if( !$link->changeStatus(\Vipip\Service\Service::STATUS_DISABLED) ){
        echo $link->getLastError() . PHP_EOL;
    }

    $info = <<<INFO
    linkid: \t$link->linkid
    title: \t$link->title
    url: \t$link->url;
    status: \t$link->status
    balance: \t$link->balance
    priceadv: \t$link->priceadv
    ref_count: \t$link->referer_count
    inp_count: \t$link->inputpoint_count
    tariff 
        title: \t$tariff->title
        addtime: \t$link->addtime
        setIntJump: \t$link->intjumpid
        maxshowuser: \t$link->maxshowuser
        advside: \t$link->advside

INFO;
        echo $info.PHP_EOL;
}
catch(Exception $e){
    echo "Catch: \n";
    echo "Code: {$e->getCode()} \n";
    echo "Message: {$e->getMessage()} \n";
}