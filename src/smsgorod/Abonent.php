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
 * Вспомогательный класс для создания абонента, которому будет отправлено сообщение.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.0.0
 */
final class Abonent implements XmlSerializable, \JsonSerializable
{

    /**
     * Номер абонента, которому адресована SMS.
     *
     * @var string|null
     */
    private $phone;

    /**
     * Число. Необязательный параметр, позволяет избежать повторной отправки. Если раннее с этого аккаунта уже было отправлено SMS с таким номером, то повторная отправка не производится, а возвращается номер ранее отправленного SMS.
     *
     * @var string|null
     */
    private $clientIdSms;

    /**
     * Дата и время отправки в формате YYYY-MM-DDHH:MM. Если не задано, то SMS отправляется сразу же.
     *
     * @var string|null
     */
    private $timeSend;

    /**
     * Дата и время, после которых не будут делаться попытки доставить SMS в формате YYYY-MM-DDHH. Если не задано, то SMS имеет максимальный срок жизни.
     *
     * @var string|null
     */
    private $validityPeriod;

    /**
     * Если задано true, то класс не будет выбрасывать исключений.
     *
     * @var bool
     */
    private $silentMode = false;

    /**
     * Создает новый экземпляр класса.
     *
     * @param string      $phone          Номер абонента, которому адресована SMS.
     * @param string|null $clientIdSms    Необязательный параметр, позволяет избежать повторной отправки. Если раннее с этого аккаунта уже было отправлено SMS с таким номером, то повторная отправка не производится, а возвращается номер ранее отправленного SMS.
     * @param string|null $timeSend       Дата и время отправки в  формате <em>YYYY-MM-DDHH:MM</em>. Если не задано, то SMS отправляется сразу же.
     * @param string|null $validityPeriod Дата и время, после которых не будут делаться попытки доставить SMS в формате <em>YYYY-MM-DDHH</em>. Если не задано, то SMS имеет максимальный срок жизни.
     * @param bool        $silentMode     Если задано true, то класс не будет выбрасывать исключения. По умолчанию равно false.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        $phone,
        $clientIdSms = null,
        $timeSend = null,
        $validityPeriod = null,
        $silentMode = false
    ) {
        $this->silentMode = $silentMode;
        $this->__set('phone', $phone);
        $this->__set('client_id_sms', $clientIdSms);
        $this->__set('time_send', $timeSend);
        $this->__set('validity_period', $validityPeriod);
    }

    /**
     * @internal Задает значение переменных класса.
     *
     * @param string $name  Имя переменной, значение которой требуется задать.
     * @param mixed  $value Новое значение переменной.
     *
     * @throws \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'phone':
                if (is_string($value) && !empty($value)) {
                    $this->phone = $value;
                } elseif ($this->silentMode !== true) {
                    throw new \InvalidArgumentException('Номер телефона обязательный параметр');
                }
                break;
            case 'client_id_sms':
                $this->clientIdSms = $value;
                break;
            case 'time_send':
                $this->timeSend = $value;
                break;
            case 'validity_period':
                $this->validityPeriod = $value;
                break;
            default:
                if ($this->silentMode !== true) {
                    throw new \InvalidArgumentException(
                        sprintf('Переменной %s не существует', $name)
                    );
                }
        }
    }

    /**
     * @internal Возвращает значения read-only переменных класса.
     *
     * @param string $name Имя переменной, значение которой требуется вернуть.
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        switch ($name) {
            case 'phone':
                return $this->phone;
            case 'client_id_sms':
                return $this->clientIdSms;
            case 'time_send':
                return $this->timeSend;
            case 'validity_period':
                return $this->validityPeriod;
            default:
                if ($this->silentMode !== true) {
                    throw new \InvalidArgumentException(
                        sprintf('Переменной %s не существует', $name)
                    );
                }
        }
    }

    /**
     * Стандартный метод для конвертирования объекта в строку. Формат данных XML.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->xmlSerialize()->asXML();
    }

    /**
     * Конвертирует объект в XML документ.
     *
     * @return \SimpleXMLElement
     */
    public function xmlSerialize()
    {
        $root = new \SimpleXMLElement("<abonent/>");
        $root->addAttribute("phone", $this->phone);
        if ($this->clientIdSms) {
            $root->addAttribute("client_id_sms", $this->clientIdSms);
        }
        if ($this->timeSend) {
            $root->addAttribute("time_send", $this->timeSend);
        }
        if ($this->validityPeriod) {
            $root->addAttribute("validity_period", $this->validityPeriod);
        }
        return $root;
    }

    /**
     * Сериализует объект в значение, которое в свою очередь может быть сериализовано функцией json_encode().
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            "phone" => $this->phone,
            "client_id_sms" => $this->clientIdSms,
            "time_send" => $this->timeSend,
            "validity_period" => $this->validityPeriod
        ];
    }
}
