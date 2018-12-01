<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\PhoneInfoResponse;

class PhoneInfoResponseTest extends TestCase {
    /**
     * @covers Ybelenko\SmsGorod\PhoneInfoResponse::__construct()
     */
    public function testGoodConstructorWithMultipleInfo() {
        $expectedArray = [
            [
                "phone" => "79612242243",
                "operator" => "Вымпел-Коммуникации",
                "region" => "Новосибирская область",
                "time_zone" => 3
            ],
            [
                "phone" => "79612242244",
                "operator" => "Вымпел-Коммуникации",
                "region" => "Новосибирская область",
                "time_zone" => 3
            ],
            [
                "phone" => "79224850804",
                "operator" => "МегаФон",
                "region" => "Тюменская область",
                "time_zone" => 1
            ],
            [
                "phone" => "79292695542",
                "operator" => "МегаФон",
                "region" => "Тюменская область",
                "time_zone" => 1
            ],
            [
                "phone" => "12357878778",
                "operator" => "unknown",
                "region" => "unknown",
                "time_zone" => "unknown"
            ]
        ];
        $expected  = "<response>";
        $expected .= "<phone operator=\"Вымпел-Коммуникации\" region=\"Новосибирская область\" time_zone=\"3\">79612242243</phone>";
        $expected .= "<phone operator=\"Вымпел-Коммуникации\" region=\"Новосибирская область\" time_zone=\"3\">79612242244</phone>";
        $expected .= "<phone operator=\"МегаФон\" region=\"Тюменская область\" time_zone=\"1\">79224850804</phone>";
        $expected .= "<phone operator=\"МегаФон\" region=\"Тюменская область\" time_zone=\"1\">79292695542</phone>";
        $expected .= "<phone operator=\"unknown\" region=\"unknown\" time_zone=\"unknown\">12357878778</phone>";
        $expected .= "</response>";

        $res = new PhoneInfoResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->phones));
    }

    /**
     * @covers Ybelenko\SmsGorod\PhoneInfoResponse::__construct()
     */
    public function testGoodConstructorWithSingleInfo(){
        $expectedArray = [
            [
                "phone" => "79612242244",
                "operator" => "Вымпел-Коммуникации",
                "region" => "Новосибирская область",
                "time_zone" => 3
            ]
        ];
        $expected  = "<response>";
        $expected .= "<phone operator=\"Вымпел-Коммуникации\" region=\"Новосибирская область\" time_zone=\"3\">79612242244</phone>";
        $expected .= "</response>";

        $res = new PhoneInfoResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($res->phones));
    }

    /**
     * @covers Ybelenko\SmsGorod\PhoneInfoResponse::__construct()
     */
    public function testBadConstructor(){
        $expected  = "<response>";
        $expected .= "<error>текст ошибки</error>";
        $expected .= "</response>";

        $res = new PhoneInfoResponse($expected);
        $this->assertSame($res->error, "текст ошибки");
    }
}
?>