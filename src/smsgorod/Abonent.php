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
    public $phone;

    /**
     * Число. Необязательный параметр, позволяет избежать повторной отправки. Если раннее с этого аккаунта уже было отправлено SMS с таким номером, то повторная отправка не производится, а возвращается номер ранее отправленного SMS.
     *
     * @var string|null
     */
    public $clientIdSms;

    /**
     * Дата и время отправки в формате YYYY-MM-DDHH:MM. Если не задано, то SMS отправляется сразу же.
     *
     * @var string|null
     */
    public $timeSend;

    /**
     * Дата и время, после которых не будут делаться попытки доставить SMS в формате YYYY-MM-DDHH. Если не задано, то SMS имеет максимальный срок жизни.
     *
     * @var string|null
     */
    public $validityPeriod;

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

        if ($silentMode === false && (!is_string($phone) || empty($phone))) {
            throw new \InvalidArgumentException("Номер телефона обязательный параметр");
        }
        $this->phone = $phone;
        $this->clientIdSms = $clientIdSms;
        $this->timeSend = $timeSend;
        $this->validityPeriod = $validityPeriod;
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
