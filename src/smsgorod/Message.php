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
 * Класс отправляемого сообщения.
 *
 * @property-read  string $type     Тип отправляемого сообщения: flashsms – flash SMS, sms – обычная SMS, wappush – WAP-Push, vcard – визитная карточка (vCard).
 * @property-read  array  $abonents Массив со всеми абонентами, которым нужно отправить данное сообщение.
 * @property-write array  $abonents Массив со всеми абонентами, которым нужно отправить данное сообщение.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.0.0
 */
final class Message implements XmlSerializable, \JsonSerializable
{
    /**
     * Константа типа отправляемого сообщения «flash SMS».
     */
    const FLASH_SMS = 'flash_sms';

    /**
     * Константа типа отправляемого сообщения «обычная SMS».
     */
    const SMS = 'sms';

    /**
     * Константа типа отправляемого сообщения «WAP - Push».
     */
    const WAPPUSH = 'wappush';

    /**
     * Константа типа отправляемого сообщения «визитная карточка (vCard)».
     */
    const VCARD = 'vcard';

    /**
     * @internal Тип отправляемого сообщения: flashsms – flash SMS, sms – обычная SMS, wappush – WAP-Push, vcard – визитная карточка (vCard).
     *
     * @var string
     */
    private $type = 'sms';

    /**
     * @internal Массив со всеми абонентами, которым нужно отправить данное сообщение.
     *
     * @var array
     */
    private $abonents = [];

    /**
     * Текст обычного SMS или описание WAP ссылки.
     *
     * @var string|null
     */
    public $text;

    /**
     * Отправитель SMS. Именно это значение будет выводиться на телефоне абонента в поле от кого SMS.
     *
     * @var string|null
     */
    public $sender;

    /**
     * Создает новый экземпляр класса.
     *
     * @param string $type       Тип отправляемого сообщения: flashsms – flash SMS, sms – обычная SMS, wappush – WAP-Push, vcard – визитная карточка (vCard).
     * @param string $text       Текст обычного SMS или описание WAP ссылки.
     * @param array  $abonents   Массив со всеми абонентами, которым нужно отправить данное сообщение.
     * @param string $sender     Отправитель SMS. Именно это значение будет выводиться на телефоне абонента в поле от кого SMS.
     * @param bool   $silentMode Если задано true, то класс не будет выбрасывать исключения. По умолчанию равно false.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($type, $text, array $abonents, $sender, $silentMode = false)
    {
        $this->type = $type;
        $this->text = $text;
        $this->abonents = array_filter($abonents, function ($var) {
            return (bool)($var instanceof Abonent);
        });
        $this->sender = $sender;
    }

    /**
     * @internal Задает значение переменных класса.
     *
     * @param string $name  Имя переменной, значение которой требуется задать.
     * @param mixed  $value Новое значение переменной.
     *
     * @throw \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'type':
                if (is_string($value) && in_array($value, [FLASH_SMS, SMS, WAPPUSH, VCARD], true)) {
                    $this->type = $value;
                } else {
                    throw new \InvalidArgumentException("Тип сообщения может быть flashsms, sms, wappush, vcard");
                }
                break;
            case 'abonents':
                if (is_array($value)) {
                    $this->abonents = array_filter($value, function ($var) {
                        return (bool)($var instanceof Abonent);
                    });
                } else {
                    throw new \InvalidArgumentException("Abonents must be an array");
                }
                break;
            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $name .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE
                );
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
            case 'type':
                return $this->type;
            case 'abonents':
                return $this->abonents;
            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $name .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE
                );
                return null;
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
        $root = new \SimpleXMLElement("<message/>");
        $root->addAttribute("type", $this->type);
        $root->addChild("sender", $this->sender);
        $root->addChild("text", $this->text);
        $rootDom = dom_import_simplexml($root);
        foreach ($this->abonents as $abonent) {
            $abonentDom = dom_import_simplexml($abonent->xmlSerialize());
            $rootDom->appendChild($rootDom->ownerDocument->importNode($abonentDom, true));
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
        $abonents = [];
        for ($a = 1; $a < count($this->abonents) -1; $a++) {
            $abonent = $this->abonents[$a]->jsonSerialize();
            $abonent["number_sms"] = $a;
            array_push($abonents, $abonent);
        }
        return [
            "type" => $this->type,
            "sender" => $this->sender,
            "text" => $this->text,
            "abonent" => $abonents
        ];
    }
}
