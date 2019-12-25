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

/**
 * Главный класс библиотеки.
 *
 * #### Пример
 * ```php
 * require __DIR__ . '/vendor/autoload.php';
 *
 * use \Ybelenko\SmsGorod\SmsGorod;
 *
 * $smsGorod = new SmsGorod($login, $password);
 * $res = $smsGorod->getServerTime();
 * echo "Current server time is " . $res->time;
 * ```
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.1.0
 */
final class SmsGorod
{

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
     * @param string $login      Логин в системе SmsGorod.
     * @param string $password   Пароль в системе SmsGorod.
     * @param bool   $silentMode Если задано true, то класс не будет выбрасывать исключения. По умолчанию равно false.
     *
     * @throw \InvalidArgumentException
     */
    public function __construct($login, $password, $silentMode = false)
    {
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
     * Выполняет запрос информации по номеру телефона.
     * @codeCoverageIgnore
     *
     * @param array $phones Массив с номерами телефонов.
     *
     * @return PhoneInfoResponse
     */
    public function getPhoneInfo(array $phones)
    {
        $req = new PhoneInfoRequest($phones, $this->login, $this->password);
        return $req->execute();
    }

    /**
     * Выполняет запрос статуса отправленного сообщения.
     * @codeCoverageIgnore
     *
     * @param array $smsIds Массив с идентификаторами отправленных сообщений.
     *
     * @return StateResponse
     */
    public function getSmsState(array $smsIds)
    {
        $req = new StateRequest($smsIds, $this->login, $this->password);
        return $req->execute();
    }

    /**
     * Выполняет запрос время сервера.
     * @codeCoverageIgnore
     *
     * @return TimeResponse
     */
    public function getServerTime()
    {
        $req = new TimeRequest($this->login, $this->password);
        return $req->execute();
    }

    /**
     * Выполняет запрос баланса.
     * @codeCoverageIgnore
     *
     * @return BalanceResponse
     */
    public function getBalance()
    {
        $req = new BalanceRequest($this->login, $this->password);
        return $req->execute();
    }

    /**
     * Выполняет запрос на отправку сообщений.
     * @codeCoverageIgnore
     *
     * @param array $messages Массив с подготовленными сообщениями для отправки.
     *
     * @return MessageSendResponse
     */
    public function sendMessage(array $messages)
    {
        $req = new MessageSendRequest($messages, $this->login, $this->password);
        return $req->execute();
    }
}
