<?php

/**
 * Библиотека для отправки SMS сообщений через провайдера @link http://smsgorod.ru
 * PHP version ^5.6 || ^7.0
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */

namespace Ybelenko\SmsGorod;

use Ybelenko\SmsGorod\Interfaces\XmlSerializable;

/**
 * Класс ответа API на метод «Запрос на получение информации по номеру телефона».
 *
 * @property-read int|null    $rawResponse Исходный ответ сервера, должен быть валидным XML документом. Read-only.
 * @property-read int|null    $statusCode  Статус код API запроса. Read-only.
 * @property-read int|null    $errorno     Номер ошибки API запроса. Read-only.
 * @property-read string|null $error       Описание ошибки API запроса. Read-only.
 * @property-read string|null $errorList   Массив, содержащий все ошибки API запроса. Read-only.
 * @property-read array|null  $phones      Массив с информацией по запрошенным номерам телефонов.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */
final class PhoneInfoResponse extends ApiResponse implements XMLSerializable, \JsonSerializable
{

    /**
     * @internal Массив с информацией по запрошенным номерам телефонов.
     *
     * @var array|null
     */
    private $phones = [];

    /**
     * Создает новый экземпляр класса.
     *
     * @param string $rawCrudResponse Исходный ответ сервера, должен быть валидным XML документом.
     *
     * @throw \UnexpectedValueException
     */
    public function __construct($rawCrudResponse)
    {
        parent::__construct($rawCrudResponse);
        $xml = simplexml_load_string($rawCrudResponse);

        if ($xml !== false && $xml->phone) {
            foreach ($xml->phone as $phone) {
                $this->phones[] = [
                    "phone" => (string) $phone,
                    "operator" => (string) $phone->attributes()->operator,
                    "region" => (string) $phone->attributes()->region,
                    "time_zone" => ( (string) $phone->attributes()->time_zone !== "unknown") ? (int) $phone->attributes()->time_zone : "unknown"
                ];
            }
        }
    }

    /**
     * @internal Возвращает значения read-only переменных класса.
     *
     * @param string $name Имя переменной, значение которой требуется вернуть.
     * @return mixed|null
     */
    public function __get($name)
    {
        switch ($name) {
            case 'phones':
                return $this->phones;
            default:
                return parent::__get($name);
        }
    }

    /**
     * Сериализует объект в значение, которое в свою очередь может быть сериализовано функцией json_encode().
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            "phones" => $this->phones,
            "errno" => $this->errno,
            "error" => $this->error,
            "error_list" => $this->errorList,
            "status_code" => $this->statusCode
        ];
    }
}
