# SMSGorod API Client

[![Latest Stable Version](https://poser.pugx.org/ybelenko/smsgorod-api-client/v/stable)](https://packagist.org/packages/ybelenko/smsgorod-api-client)
[![Build Status](https://travis-ci.org/ybelenko/smsgorod-api-client.svg?branch=master)](https://travis-ci.org/ybelenko/smsgorod-api-client)
[![Coverage Status](https://coveralls.io/repos/github/ybelenko/smsgorod-api-client/badge.svg?branch=master)](https://coveralls.io/github/ybelenko/smsgorod-api-client?branch=master)
[![License](https://poser.pugx.org/ybelenko/smsgorod-api-client/license)](https://packagist.org/packages/ybelenko/smsgorod-api-client)

## Требования
- Любой вебсервер, например Apache
- PHP ^5.6 || ^7.0
- PHP extensions:
    - ext-curl
    - lib-curl
    - lib-libxml
    - ext-SimpleXML
    - ext-json
- Composer
- Shell доступ, чтобы запустить скрипт установки в терминале

## Установка
Для установки потребуется [Composer](https://getcomposer.org/download/).
После успешной установки Composer нужно запустить распаковку пакетов через терминал/консоль:
```shell
$ composer require ybelenko/smsgorod-api-client
```

## Пример использования
```php
require __DIR__ . '/vendor/autoload.php';

use \Ybelenko\SmsGorod\SmsGorod;

$smsGorod = new SmsGorod($login, $password);
$res = $smsGorod->getServerTime();
echo "Current server time is " . $res->time;
```

## Запуск автоматических тестов
Чтобы запустить тесты нужно склонировать исходники.
```shell
$ git clone https://github.com/ybelenko/smsgorod-api-client.git smsgorod-api-client-clone
$ cd smsgorod-api-client-clone
$ composer install
$ composer test
```

## Автор
© Юрий Беленко <yura-bely@mail.ru> 2015-2019

## License
MIT License
