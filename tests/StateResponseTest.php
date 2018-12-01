<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\StateResponse;

class StateResponseTest extends TestCase {
    /**
     * @covers Ybelenko\SmsGorod\StateResponse::__construct()
     */
    public function testGoodConstructorWithMultipleState() {
        $expectedArray = [
            [
                "id_sms" => "2780243767",
                "time" => "2015-10-27 14:46:51",
                "state" => "deliver",
                "price" => 1.45
            ],
            [
                "id_sms" => "2799892974",
                "time" => "2016-03-26 05:11:06",
                "state" => "deliver",
                "price" => 1.52
            ],
            [
                "id_sms" => "2806791047",
                "time" => "2016-05-21 05:29:14",
                "state" => "deliver",
                "price" => 1.1
            ],
            [
                "id_sms" => "2826561937",
                "time" => "2016-10-15 08:59:51",
                "state" => "deliver",
                "price" => 1.49
            ],
            [
                "id_sms" => "2826561955",
                "time" => "2016-10-15 09:00:22",
                "state" => "deliver",
                "price" => 1.49
            ],
            [
                "id_sms" => "1254578877",
                "time" => "",
                "state" => "Сообщение с таким ID не принималось",
                "price" => 1.49
            ]
        ];
        $expected  = "<response>";
        $expected .= "<state id_sms=\"2780243767\" time=\"2015-10-27 14:46:51\" price=\"1.45\">deliver</state>";
        $expected .= "<state id_sms=\"2799892974\" time=\"2016-03-26 05:11:06\" price=\"1.52\">deliver</state>";
        $expected .= "<state id_sms=\"2806791047\" time=\"2016-05-21 05:29:14\" price=\"1.1\">deliver</state>";
        $expected .= "<state id_sms=\"2826561937\" time=\"2016-10-15 08:59:51\" price=\"1.49\">deliver</state>";
        $expected .= "<state id_sms=\"2826561955\" time=\"2016-10-15 09:00:22\" price=\"1.49\">deliver</state>";
        $expected .= "<state id_sms=\"1254578877\" time=\"\" price=\"1.49\">Сообщение с таким ID не принималось</state>";
        $expected .= "</response>";

        $res = new StateResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->state));
    }

    /**
     * @covers Ybelenko\SmsGorod\StateResponse::__construct()
     */
    public function testGoodConstrucorWithSingleState() {
        $expectedArray = [
            [
                "id_sms" => "2780243767",
                "time" => "2015-10-27 14:46:51",
                "state" => "deliver",
                "price" => 1.45
            ]
        ];
        $expected  = "<response>";
        $expected .= "<state id_sms=\"2780243767\" time=\"2015-10-27 14:46:51\" price=\"1.45\">deliver</state>";
        $expected .= "</response>";

        $res = new StateResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->state));
    }

    /**
     * @covers Ybelenko\SmsGorod\StateResponse::__construct()
     */
    public function testBadConstructor(){
        $expected  = "<response>";
        $expected .= "<error>текст ошибки</error>";
        $expected .= "</response>";

        $res = new StateResponse($expected);
        $this->assertSame($res->error, "текст ошибки");
    }
}
?>