<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\BalanceResponse;

class BalanceReponseTest extends TestCase{
    /**
     * @covers Ybelenko\SmsGorod\BalanceResponse::__construct()
     */
    public function testGoodConstructorWithMultipleInfo() {
        $expectedSmsArray = [
            [
                "area" => "Россия",
                "count" => 22
            ],
            [
                "area" => "МТС",
                "count" => 19
            ],
            [
                "area" => "Мегафон",
                "count" => 19
            ],
            [
                "area" => "Теле2",
                "count" => 19
            ],
            [
                "area" => "Мотив",
                "count" => 42
            ],
            [
                "area" => "Билайн",
                "count" => 21
            ],
            [
                "area" => "SMARTS",
                "count" => 21
            ],
            [
                "area" => "Остальные",
                "count" => 19
            ]
        ];

        $expectedMoneyArray = [
            [
                "currency" => "RUB",
                "value" => 29.47
            ]
        ];

        $expected  = "<response>";
        $expected .= "<sms area=\"Россия\">22</sms>";
        $expected .= "<sms area=\"МТС\">19</sms>";
        $expected .= "<sms area=\"Мегафон\">19</sms>";
        $expected .= "<sms area=\"Теле2\">19</sms>";
        $expected .= "<sms area=\"Мотив\">42</sms>";
        $expected .= "<sms area=\"Билайн\">21</sms>";
        $expected .= "<sms area=\"SMARTS\">21</sms>";
        $expected .= "<sms area=\"Остальные\">19</sms>";
        $expected .= "<money currency=\"RUB\">29.47</money>";
        $expected .= "</response>";

        $res = new BalanceResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedSmsArray), json_encode($res->sms));
        $this->assertJsonStringEqualsJsonString(json_encode($expectedMoneyArray), json_encode($res->money));
    }

    /**
     * @covers Ybelenko\SmsGorod\BalanceResponse::__construct()
     */
    public function testGoodConstructorWithSingleInfo(){
        $expectedSmsArray = [
            [
                "area" => "Россия",
                "count" => 22
            ]
        ];

        $expectedMoneyArray = [
            [
                "currency" => "RUB",
                "value" => 29.47
            ]
        ];

        $expected  = "<response>";
        $expected .= "<sms area=\"Россия\">22</sms>";
        $expected .= "<money currency=\"RUB\">29.47</money>";
        $expected .= "</response>";

        $res = new BalanceResponse($expected);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedSmsArray), json_encode($res->sms));
        $this->assertJsonStringEqualsJsonString(json_encode($expectedMoneyArray), json_encode($res->money));
        $this->assertSame($res->sms[0]["count"], 22);
        $this->assertSame($res->money[0]["value"], 29.47);
    }

    /**
     * @covers Ybelenko\SmsGorod\BalanceResponse::__construct()
     */
    public function testBadConstructor(){
        $expected  = "<response>";
        $expected .= "<error>текст ошибки</error>";
        $expected .= "</response>";

        $res = new BalanceResponse($expected);
        $this->assertSame($res->error, "текст ошибки");
    }

    /**
     * @covers Ybelenko\SmsGorod\BalanceResponse::__get()
     */
    public function testGetValues() {
        $expected  = "<response>";
        $expected .= "<sms area=\"Россия\">22</sms>";
        $expected .= "<money currency=\"RUB\">29.47</money>";
        $expected .= "</response>";

        $res = new BalanceResponse($expected);

        $this->assertSame($res->sms, [[ "area" => "Россия", "count" => 22]]);
        $this->assertSame($res->money, [[ "currency" => "RUB", "value" => 29.47]]);
        $this->assertSame($res->error, "");
    }

    /**
     * @covers Ybelenko\SmsGorod\BalanceResponse::jsonSerialize()
     */
    public function testJsonSerialize() {
        $expected  = "<response>";
        $expected .= "<sms area=\"Россия\">22</sms>";
        $expected .= "<money currency=\"RUB\">29.47</money>";
        $expected .= "</response>";

        $res = new BalanceResponse($expected);

        $json = $res->jsonSerialize();
        $this->assertInternalType('array', $json);

        $this->assertArrayHasKey('errno', $json);
        $this->assertArrayHasKey('error', $json);
        $this->assertArrayHasKey('error_list', $json);
        $this->assertArrayHasKey('status_code', $json);
        $this->assertArrayHasKey('sms', $json);
        $this->assertArrayHasKey('money', $json);

        $this->assertJsonStringEqualsJsonString(
            json_encode($json),
            json_encode([
                "sms" => $res->sms,
                "money" => $res->money,
                "errno" => $res->errno,
                "error" => $res->error,
                "error_list" => $res->errorList,
                "status_code" => $res->statusCode
            ])
        );
    }

}
?>