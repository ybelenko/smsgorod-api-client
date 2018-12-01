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
 * @version  v1.0.0
 */

namespace Ybelenko\SmsGorod;

use Ybelenko\SmsGorod\Interfaces\XmlSerializable;

/**
 * Класс ответа API на запрос проверки баланса.
 *
 * @property-read int|null    $rawResponse Исходный ответ сервера, должен быть валидным XML документом. Read-only.
 * @property-read int|null    $statusCode  Статус код API запроса. Read-only.
 * @property-read int|null    $errorno     Номер ошибки API запроса. Read-only.
 * @property-read string|null $error       Описание ошибки API запроса. Read-only.
 * @property-read string|null $errorList   Массив, содержащий все ошибки API запроса. Read-only.
 * @property-read array       $sms         Количество доступных SMS сообщений для каждого направления.
 * @property-read array       $money       Остаток средств.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.0.0
 */
final class BalanceResponse extends ApiResponse implements XMLSerializable, \JsonSerializable
{

    /**
     * @internal Количество доступных SMS сообщений для каждого направления.
     *
     * При этом количество SMS не может быть суммировано по разным направлениям.
     * При отправке смс в одном направлении уменьшается количество доступных SMS сообщений во всех других направления в соответствии с их стоимостью.
     *
     * @var array
     */
    private $sms = [];

    /**
     * @internal Остаток средств.
     *
     * @var array
     */
    private $money = [];

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

        if ($xml !== false && $xml->sms) {
            foreach ($xml->sms as $sms) {
                $this->sms[] = [
                    "area" => (string) $sms->attributes()->area,
                    "count" => (int) $sms
                ];
            }
            foreach ($xml->money as $money) {
                $this->money[] = [
                    "currency" => (string) $money->attributes()->currency,
                    "value" => (float) $money
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
            case 'sms':
                return $this->sms;
            case 'money':
                return $this->money;
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
            "money" => $this->money,
            "errno" => $this->errno,
            "error" => $this->error,
            "error_list" => $this->errorList,
            "status_code" => $this->statusCode
        ];
    }
}
