# SMSGorod API Client

[![Latest Stable Version](https://poser.pugx.org/ybelenko/smsgorod-api-client/v/stable)](https://packagist.org/packages/ybelenko/smsgorod-api-client)
[![Build Status](https://travis-ci.org/ybelenko/smsgorod-api-client.svg?branch=master)](https://travis-ci.org/ybelenko/smsgorod-api-client)
[![Coverage Status](https://coveralls.io/repos/github/ybelenko/smsgorod-api-client/badge.svg?branch=master)](https://coveralls.io/github/ybelenko/smsgorod-api-client?branch=master)
[![License](https://poser.pugx.org/ybelenko/smsgorod-api-client/license)](https://packagist.org/packages/ybelenko/smsgorod-api-client)

Примечание от службы поддержки СМС Город:
> :exclamation: Перед подключением данной клиентской библиотеки необходимо написать на <support@smsgorod.ru>. На основании ваших пожеланий, служба поддержки поможет выбрать соответствующий канал отправки сообщений.

## Требования
- Любой вебсервер, например Apache
- PHP ^7.0 || ^8.0
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

use Ybelenko\SmsGorod\SmsGorod;
use Ybelenko\SmsGorod\Message;
use Ybelenko\SmsGorod\Abonent;

// здесь требуется подставить логин и папроль от сервиса SMSGorod
$smsGorod = new SmsGorod('логин', 'пароль');
$sender = 'VIRTA';
$messageType = Message::SMS;
$message = 'Hello World!';

// отправляем смс сообщение одному абоненту при помощи запроса к апи
$response = $smsGorod->sendMessage([
    new Message(
        $messageType,
        $message,
        [
            // телефон получателя смс
            new Abonent('79033256699'),

            // одно сообщение могут получать несколько абонентов
            // new Abonent('79033256699'),
        ],
        $sender
    ),
    // можно отправить несколько сообщений за один запрос
    // new Message(),
]);

// ответ апи в формате JSON
echo json_encode($response->sms, \JSON_PRETTY_PRINT);
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
