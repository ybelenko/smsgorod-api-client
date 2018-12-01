<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;
use Ybelenko\SmsGorod\Message;

class MessageTest extends TestCase{

    public function testXmlSerialize(){
        $abonents = [
            new Abonent("7922458545", "1", "2017-03-0800:00", "2017-03-1800:00", true),
            new Abonent("7922466666", "2", "2017-03-1400:00", "2017-03-2400:00", true),
            new Abonent("7922433333", "3", "2017-03-2500:00", "2017-04-0500:00", true)
        ];
        $type = Message::SMS;
        $message = new Message($type, "Sms text message", $abonents, "VIRTA SENDER", false);

        $expected  = "<message type=\"{$type}\"><sender>VIRTA SENDER</sender><text>Sms text message</text>";
        $expected .= "<abonent phone=\"{$abonents[0]->phone}\" client_id_sms=\"{$abonents[0]->clientIdSms}\" validity_period=\"{$abonents[0]->validityPeriod}\" time_send=\"{$abonents[0]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[1]->phone}\" client_id_sms=\"{$abonents[1]->clientIdSms}\" validity_period=\"{$abonents[1]->validityPeriod}\" time_send=\"{$abonents[1]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[2]->phone}\" client_id_sms=\"{$abonents[2]->clientIdSms}\" validity_period=\"{$abonents[2]->validityPeriod}\" time_send=\"{$abonents[2]->timeSend}\" />";
        $expected .= "</message>";
        $expectedDom =  new DOMDocument();
        $expectedDom->loadXML($expected);
        $actualDom = new DOMDocument();
        $actualDom->loadXML(strval($message));

        $this->assertEqualXMLStructure(
            $expectedDom->firstChild, $actualDom->firstChild, true, strval($message)
        );

        $this->assertXmlStringEqualsXmlString(
            strval($message),
            $expected,
            true
        );
    }

}
?>