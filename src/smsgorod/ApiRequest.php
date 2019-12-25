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

/**
 * Абстрактный класс запроса к серверу API.
 *
 * @category PHP
 * @package  Ybelenko\SmsGorod
 * @author   Yuriy Belenko <yura-bely@mail.ru>
 * @license  MIT License https://github.com/ybelenko/smsgorod-api-client/blob/master/LICENSE
 * @link     https://github.com/ybelenko/smsgorod-api-client
 * @version  v1.0.0
 */
abstract class ApiRequest
{
    /**
     * Номер ошибки Curl запроса. Read-only.
     *
     * @var int|null
     */
    protected $errno;

    /**
     * Описание ошибки Curl запроса. Read-only.
     *
     * @var string|null
     */
    protected $error;

    /**
     * Http статус код ответа Curl запроса. Read-only.
     *
     * @var int|null
     */
    protected $statusCode;

    /**
     * Выполняет POST запрос по указанному URL и возвращает ответ сервера.
     * Для корректной работы требуется расширение ext - curl.
     * @codeCoverageIgnore
     *
     * @param string $url Загружаемый URL.
     * @param mixed  $data Все данные, передаваемые в HTTP POST-запросе.
     *
     * @return mixed при успешном завершении будет возвращен результат, а при неудаче - FALSE
     */
    protected function post($url, $data)
    {
        // При этом передаваемый XMLдокумент не должен содержать переводов строки.
        // Переводы строк в самих данных должны быть заменены на “/n”.
        $data = preg_replace('~\R~u', '', $data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: text/xml; charset=utf-8']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CRLF, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $this->errno = curl_errno($curl);
        $this->error = curl_error($curl);
        $this->statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $result;
    }

    /**
     * @internal Возвращает значения read - only переменных класса.
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        switch ($name) {
            case 'errno':
                return $this->errno;
            case 'error':
                return $this->error;
            case 'statusCode':
                return $this->statusCode;
            default:
                throw new \InvalidArgumentException(
                    sprintf('Переменной %s не существует', $name)
                );
        }
    }
}
