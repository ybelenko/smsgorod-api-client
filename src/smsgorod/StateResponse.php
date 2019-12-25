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
 * Класс ответа API на метод «Запрос статуса SMS сообщения».
 *
 * @property-read int|null    $rawResponse Исходный ответ сервера, должен быть валидным XML документом. Read-only.
 * @property-read int|null    $statusCode  Статус код API запроса. Read-only.
 * @property-read int|null    $errorno     Номер ошибки API запроса. Read-only.
 * @property-read string|null $error       Описание ошибки API запроса. Read-only.
 * @property-read string|null $errorList   Массив, содержащий все ошибки API запроса. Read-only.
 * @property-read array|null  $state       Массив со статусами всех запрошенных сообщений.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */
final class StateResponse extends ApiResponse implements XMLSerializable, \JsonSerializable
{
    /**
     * Статус - статус сообщения не получен.
     * В этом случае передается пустой time (time="").
     */
    const STATE_SEND = "send";

    /**
     * Статус - сообщение не было доставлено.
     * Конечный статус (не меняется со временем).
     */
    const STATE_NOT_DELIVER = "not_deliver";

    /**
     * Статус - абонент находился не в сети в те моменты, когда делалась попытка доставки.
     * Конечный статус (не меняется со временем).
     */
    const STATE_EXPIRED = "expired";

    /**
     * Статус - сообщение доставлено.
     * Конечный статус (не меняется со временем).
     */
    const STATE_DELIVER = "deliver";

    /**
     * Статус - сообщение было отправлено, но статус так и не был получен.
     * Конечный статус (не меняется со временем). В этом случае для разъяснения причин отсутствия статуса необходимо связаться со службой тех. поддержки.
     */
    const STATE_PARTLY_DELIVER = "partly_deliver";

    /**
     * @internal Массив со статусами всех запрошенных сообщений.
     *
     * @var array|null
     */
    private $state = [];

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

        if ($xml !== false && $xml->state) {
            foreach ($xml->state as $state) {
                $this->state[] = [
                    "id_sms" => (string) $state->attributes()->id_sms,
                    "time" => (string) $state->attributes()->time,
                    "state" => (string) $state,
                    "price" => (float) $state->attributes()->price
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
            case 'state':
                return $this->state;
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
            "state" => $this->state,
            "errno" => $this->errno,
            "error" => $this->error,
            "error_list" => $this->errorList,
            "status_code" => $this->statusCode
        ];
    }
}
