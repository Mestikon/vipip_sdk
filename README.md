# Vipip SDK
Vipip SDK for PHP

![VipIP Темная сторона рекламы](https://vipip.ru/i/logos/logo.png)
# Table of contents
---
1. [Installation](#installation)
2. [Usage](#usage)
3. [Modules](#modules)
    * [User](#user)
	* [Services](#services)
4. [Service methods](#service)

# Installation
---
It is recommended that you install the Vipip SDK library [through composer](http://getcomposer.org/). To do so, add the following lines to your ``composer.json`` file.

```json
{
    "require": {
       "ferrumfist/vipip_sdk": "dev-master"
    }
}
```

# Usage
---
At the beginning of the library must be initialized by calling the init method

```php
VipIP::init(<access_token>[, $config]);
```
The following options can be passed as a configuration:
```php
[
'lang'=>'ru',
'cache' => [
    'driver' => 'redis',
    'config' => [
        'host' => '127.0.0.1',
        'port' => '6379'
    ]
]
```
More information about the cache options, [click here](https://github.com/PHPSocialNetwork/phpfastcache)

# Modules
---
SDK is divided into modules. You get the module to perform API requests

```php
$user = VipIP::module('user');
```
## User
**getBalance** - getting user`s balance
```php
$user->getBalance();
```
**getDiscount** - getting user`s discount
```php
$user->getDiscount();
```

## Services
Services it is a group of modules which includes Link, Task, Social

**create** - creating services
```php
$link = VipIP::module('link')->create("My first link", "http://example.com");
```

**getList**, **getOne**- getting list of/one service(s)
```php
$links = VipIP::module('link')->getList([43,648,474]);
$link = VipIP::module('link')->getOne(45);
```

# Service methods
After creating/getting the service you have the object of service. To use a service, use the following methods:

**save** - saving attributes of service
```php
$link->save([
	'title' => 'new titile',
	'url' => 'http://new_url.org'
]);
```

**changeBalance** - changing service balance

**changeStatus** - changing service status
example
```php
if( !$link->changeBalance(3, \Vipip\Service\Service::BALANCE_TYPE_DAYS) ){
   echo $link->getLastError();
}
```

The next group of methods gets / sets the objects for the service settings

**getTariff** - getting tariff

**setTariff** - setting tariff

**getInputReferer** - getting inputpoints and referers

**setInputReferer** - Setting inputpoints and referers

**getGeo** - getting geography settings

**setGeo** - setting geography settings

**getCalendar** - getting calendar settings

**setCalendar** - setting calendar settings