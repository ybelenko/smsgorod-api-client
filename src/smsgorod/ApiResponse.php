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
 * Базовый класс ответа API.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.0.0
 */
class ApiResponse implements XmlSerializable, \JsonSerializable
{
    /**
     * Исходный ответ сервера, должен быть валидным XML документом. Read-only.
     *
     * @var string|null
     */
    protected $rawResponse;

    /**
     * Статус код API запроса. Read-only.
     *
     * @var int|null
     */
    protected $statusCode = 200;

    /**
     * Номер ошибки API запроса. Read-only.
     *
     * @var int|null
     */
    protected $errno = 0;

    /**
     * Описание ошибки API запроса. Read-only.
     *
     * @var int|null
     */
    protected $error = '';

    /**
     * Массив, содержащий все ошибки API запроса. Read-only.
     *
     * @var array
     */
    protected $errorList = [];

    /**
     * Создает новый экземпляр класса.
     *
     * @param string $rawCrudResponse Исходный ответ сервера, должен быть валидным XML документом.
     *
     * @throw \UnexpectedValueException
     */
    public function __construct($rawCrudResponse)
    {
        libxml_use_internal_errors(true);
        $this->rawResponse = $rawCrudResponse;
        $xml = simplexml_load_string($rawCrudResponse);
        if ($xml !== false) {
            if ($xml->error) {
                $this->statusCode = 500;
                $this->errno = 500;
                $this->error = (string) $xml->error;
                $this->errorList[] = [
                    "errno" => 500,
                    "error" => (string) $xml->error
                ];
            } else {
                $this->statusCode = 200;
            }
        } else {
            throw new \UnexpectedValueException("Ответ должен быть валидным XML документом");
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
            case 'errno':
                return $this->errno;
            case 'error':
                return $this->error;
            case 'errorList':
                return $this->errorList;
            case 'statusCode':
                return $this->statusCode;
            case 'rawResponse':
                return $this->rawResponse;
            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $name .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE
                );
                // @codeCoverageIgnoreStart
                return null;
                // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Стандартный метод для конвертирования объекта в строку. Формат данных XML.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->rawResponse;
    }

    /**
     * Конвертирует объект в XML документ.
     *
     * @return \SimpleXMLElement
     */
    public function xmlSerialize()
    {
        return simplexml_load_string($this->rawResponse);
    }

    /**
     * Сериализует объект в значение, которое в свою очередь может быть сериализовано функцией json_encode().
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            "errno" => $this->errno,
            "error" => $this->error,
            "error_list" => $this->errorList,
            "status_code" => $this->statusCode
        ];
    }
}
