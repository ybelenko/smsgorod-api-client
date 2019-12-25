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
 * @property-read array|null  $sms         Массив с информацией об отправленных смс.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */
final class MessageSendResponse extends ApiResponse implements XMLSerializable, \JsonSerializable
{
    /**
     * @internal Массив с информацией об отправленных смс.
     *
     * @var array|null
     */
    private $sms = [];

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

        if ($xml !== false && $xml->information) {
            foreach ($xml->information as $information) {
                $this->sms[] = [
                    "state" => (string) $information,
                    "id_sms" => (string) $information->attributes()->id_sms,
                    "id_turn" => (string) $information->attributes()->id_turn,
                    "number_sms" => (string) $information->attributes()->number_sms,
                    "parts" => (int) $information->attributes()->parts,
                    "price" => (float) $information->attributes()->price,
                    "price_sum" => (float) $information->attributes()->price_sum
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
        switch (strtolower($name)) {
            case 'sms':
                return $this->sms;
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
            "sms" => $this->sms,
            "errno" => $this->errno,
            "error" => $this->error,
            "error_list" => $this->errorList,
            "status_code" => $this->statusCode
        ];
    }
}
