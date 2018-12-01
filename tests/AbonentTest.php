<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;

class AbonentTest extends TestCase {

    /**
     * @dataProvider dataProvider
     * @covers Ybelenko\SmsGorod\Abonent::__construct()
     */
    public function testConstructor($phone, $clientIdSms, $timeSend, $validityPeriod) {
        $abonent = new Abonent($phone, $clientIdSms, $timeSend, $validityPeriod);

        $this->assertEquals($abonent->phone, $phone);
        $this->assertEquals($abonent->clientIdSms, $clientIdSms);
        $this->assertEquals($abonent->timeSend, $timeSend);
        $this->assertEquals($abonent->validityPeriod, $validityPeriod);
    }

    /**
     * @covers Ybelenko\SmsGorod\Abonent::__construct()
     */
    public function testBadConstructor() {
        $this->expectException(\InvalidArgumentException::class);
        $abonent = new Abonent(null, null, null, null);
    }

    /**
     * @dataProvider dataProvider
     * @covers Ybelenko\SmsGorod\Abonent::__toString()
     */
    public function testToString($phone, $clientIdSms, $timeSend, $validityPeriod) {
        $abonent = new Abonent($phone, $clientIdSms, $timeSend, $validityPeriod);

        $this->assertInternalType('string', strval($abonent));
        $this->assertXmlStringEqualsXmlString($abonent->xmlSerialize()->asXML(), strval($abonent), true);

        $xml = simplexml_load_string(strval($abonent));
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);

        $this->assertEquals((string) $xml->attributes()->phone, $phone);
        $this->assertEmpty((string) $xml->attributes()->number_sms);
        $this->assertEquals((string) $xml->attributes()->client_id_sms, $clientIdSms);
        $this->assertEquals((string) $xml->attributes()->time_send, $timeSend);
        $this->assertEquals((string) $xml->attributes()->validity_period, $validityPeriod);

        $this->assertXmlStringEqualsXmlString(
            $xml->asXML(),
            "<abonent client_id_sms=\"{$clientIdSms}\" phone=\"{$phone}\" time_send=\"{$timeSend}\" validity_period=\"{$validityPeriod}\"/>",
            true
        );
    }


    /**
     * @dataProvider dataProvider
     * @covers Ybelenko\SmsGorod\Abonent::xmlSerialize()
     */
    public function testXmlSerialize($phone, $clientIdSms, $timeSend, $validityPeriod) {
        $abonent = new Abonent($phone, $clientIdSms, $timeSend, $validityPeriod);

        $xml = $abonent->xmlSerialize();
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);

        $this->assertEquals((string) $xml->attributes()->phone, $phone);
        $this->assertEmpty((string) $xml->attributes()->number_sms);
        $this->assertEquals((string) $xml->attributes()->client_id_sms, $clientIdSms);
        $this->assertEquals((string) $xml->attributes()->time_send, $timeSend);
        $this->assertEquals((string) $xml->attributes()->validity_period, $validityPeriod);

        $this->assertXmlStringEqualsXmlString(
            $xml->asXML(),
            "<abonent client_id_sms=\"{$clientIdSms}\" phone=\"{$phone}\" time_send=\"{$timeSend}\" validity_period=\"{$validityPeriod}\"/>",
            true
        );
    }

    /**
     * @dataProvider dataProvider
     * @covers Ybelenko\SmsGorod\Abonent::jsonSerialize()
     */
    public function testJsonSerialize($phone, $clientIdSms, $timeSend, $validityPeriod) {
        $abonent = new Abonent($phone, $clientIdSms, $timeSend, $validityPeriod);

        $json = $abonent->jsonSerialize();
        $this->assertInternalType('array', $json);

        $this->assertArrayHasKey('phone', $json);
        $this->assertArrayHasKey('client_id_sms', $json);
        $this->assertArrayHasKey('time_send', $json);
        $this->assertArrayHasKey('validity_period', $json);

        $this->assertEquals($json["phone"], $phone);
        $this->assertEquals($json["client_id_sms"], $clientIdSms);
        $this->assertEquals($json["time_send"], $timeSend);
        $this->assertEquals($json["validity_period"], $validityPeriod);

        $this->assertJsonStringEqualsJsonString(
            json_encode($json),
            json_encode([
                "phone" => $phone,
                "client_id_sms" => $clientIdSms,
                "time_send" => $timeSend,
                "validity_period" => $validityPeriod
            ])
        );
    }

    public function dataProvider() {
        return array(
            ["79224850800", 1, "YYYY-MM-DDHH:MM", "YYYY-MM-DDHH"],
            ["15", 2, "YYYY-MM-DDHH:MM", "YYYY-MM-DDHH"],
            ["22", 12357878, "YYYY-MM-DDHH:MM", "YYYY-MM-DDHH"]
        );
    }
}

?>