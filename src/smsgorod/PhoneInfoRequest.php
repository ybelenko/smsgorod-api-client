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
 * Класс метода API «Запрос на получение информации по номеру телефона».
 *
 * @property-read int|null    $errorno    Номер ошибки Curl запроса. Read-only.
 * @property-read string|null $error      Описание ошибки Curl запроса. Read-only.
 * @property-read int|null    $statusCode Http статус код ответа Curl запроса. Read-only.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */
final class PhoneInfoRequest extends ApiRequest implements XmlSerializable, \JsonSerializable
{
    /**
     * URL сервиса для выполнения текущего запроса.
     */
    const INFO_URL = 'http://web.smsgorod.ru/xml/def.php';

    /**
     * Массив с номерами телефона.
     *
     * @var array|null
     */
    public $phones;

    /**
     * Логин в системе SmsGorod.
     *
     * @var string|null
     */
    public $login;

    /**
     * Пароль в системе SmsGorod.
     *
     * @var string|null
     */
    public $password;

    /**
     * Создает новый экземпляр класса.
     *
     * @param array  $phones     Массив телефонов, которые будут проверены.
     * @param string $login      Логин в системе SmsGorod.
     * @param string $password   Пароль в системе SmsGorod.
     * @param bool   $silentMode Если задано true, то класс не будет выбрасывать исключения. По умолчанию равно false.
     *
     * @throw \InvalidArgumentException
     */
    public function __construct(array $phones, $login, $password, $silentMode = false)
    {
        $this->phones = $phones;
        if ($silentMode === false && ( !is_string($login) || empty($login) )) {
            throw new \InvalidArgumentException("Login should be not empty string");
        }
        if ($silentMode === false && ( !is_string($password) || empty($login) )) {
            throw new \InvalidArgumentException("Password should be not empty string");
        }
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Выполняет запрос к АПИ.
     * @codeCoverageIgnore
     *
     * @return PhoneInfoResponse
     */
    public function execute()
    {
        $raw = parent::post(self::INFO_URL, strval($this));
        return new PhoneInfoResponse($raw);
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
        $root = new \SimpleXMLElement("<request/>");
        $security = $root->addChild("security");
        $security->addChild("login")->addAttribute("value", $this->login);
        $security->addChild("password")->addAttribute("value", $this->password);
        $phones = $root->addChild("phones");
        foreach ($this->phones as $phone) {
            $phones->addChild("phone", strval($phone));
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
        $phones = array_map(function ($item) {
            return ['phone' => $item];
        }, $this->phones);
        return [
            "security" => [
                "login" => $this->login,
                "password" => $this->password,
            ],
            "phones" => $phones,
        ];
    }
}
