<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\MessageSendResponse;

class MessageSendResponseTest extends TestCase {
    /**
     * @covers Ybelenko\SmsGorod\MessageSendResponse::__construct()
     */
    public function testGoodConstructorWithMultipleInfo() {
        $expectedArray = [
            [
                "state" => "send",
                "number_sms" => "1",
                "id_sms" => "2856677675",
                "id_turn" => "533025253",
                "parts" => 1,
                "price" => 1.1,
                "price_sum" => 1.1
            ],
            [
                "state" => "send",
                "number_sms" => "2",
                "id_sms" => "2856677676",
                "id_turn" => "533025254",
                "parts" => 1,
                "price" => 1.1,
                "price_sum" => 1.1
            ],
            [
                "state" => "send",
                "number_sms" => "3",
                "id_sms" => "2856677677",
                "id_turn" => "533025255",
                "parts" => 1,
                "price" => 1.1,
                "price_sum" => 1.1
            ],
            [
                "state" => "send",
                "number_sms" => "4",
                "id_sms" => "2856677678",
                "id_turn" => "533025256",
                "parts" => 1,
                "price" => 1.1,
                "price_sum" => 1.1
            ]
        ];

        $expected  = "<response>";
        $expected .= "<information number_sms=\"1\" id_sms=\"2856677675\" id_turn=\"533025253\" parts=\"1\" price=\"1.1\" price_sum=\"1.1\">send</information>";
        $expected .= "<information number_sms=\"2\" id_sms=\"2856677676\" id_turn=\"533025254\" parts=\"1\" price=\"1.1\" price_sum=\"1.1\">send</information>";
        $expected .= "<information number_sms=\"3\" id_sms=\"2856677677\" id_turn=\"533025255\" parts=\"1\" price=\"1.1\" price_sum=\"1.1\">send</information>";
        $expected .= "<information number_sms=\"4\" id_sms=\"2856677678\" id_turn=\"533025256\" parts=\"1\" price=\"1.1\" price_sum=\"1.1\">send</information>";
        $expected .= "</response>";

        $res = new MessageSendResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->sms));
    }

    /**
     * @covers Ybelenko\SmsGorod\MessageSendResponse::__construct()
     */
    public function testGoodConstructorWithSingleInfo(){
        $expectedArray = [
            [
                "state" => "send",
                "number_sms" => "2",
                "id_sms" => "2779500190",
                "id_turn" => "",
                "parts" => 1,
                "price" => 0,
                "price_sum" => 0
            ]
        ];
        $expected  = "<response>";
        $expected .= "<information number_sms=\"2\" id_sms=\"2779500190\" id_turn=\"\" parts=\"1\" price=\"\" price_sum=\"0\">send</information>";
        $expected .= "</response>";

        $res = new MessageSendResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->sms));
    }

    /**
     * @covers Ybelenko\SmsGorod\MessageSendResponse::__construct()
     */
    public function testBadConstructor(){
        $expected  = "<response>";
        $expected .= "<error>текст ошибки</error>";
        $expected .= "</response>";

        $res = new MessageSendResponse($expected);
        $this->assertSame($res->error, "текст ошибки");
    }
}
?>