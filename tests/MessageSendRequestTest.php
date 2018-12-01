<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;
use Ybelenko\SmsGorod\Message;
use Ybelenko\SmsGorod\MessageSendRequest;

class MessageSendRequestTest extends TestCase {
    public function testRealExample() {
        $expected  = "<request><security><login value=\"login\" /><password value=\"password\" /></security>";
        $expected .= "<message type=\"sms\"><sender>VIRTA</sender><text>Текст сообщения 1</text>";
        $expected .= "<abonent phone=\"79224850804\" number_sms=\"1\" client_id_sms=\"301\" />";
        $expected .= "<abonent phone=\"79292695542\" number_sms=\"2\" client_id_sms=\"302\"  />";
        $expected .= "</message>";
        $expected .= "<message type=\"sms\"><sender>VIRTA</sender><text>Текст сообщения 2</text>";
        $expected .= "<abonent phone=\"79224850804\" number_sms=\"3\" client_id_sms=\"303\"  />";
        $expected .= "<abonent phone=\"79292695542\" number_sms=\"4\" client_id_sms=\"304\"  />";
        $expected .= "</message></request>";

        $type = Message::SMS;

        $abonents1 = [
            new Abonent("79224850804", "301", null, null, true),
            new Abonent("79292695542", "302", null, null, true)
        ];

        $abonents2 = [
            new Abonent("79224850804", "303", null, null, true),
            new Abonent("79292695542", "304", null, null, true)
        ];

        $messageA = new Message($type, "Текст сообщения 1", $abonents1, "VIRTA", false);
        $messageB = new Message($type, "Текст сообщения 2", $abonents2, "VIRTA", false);

        $req = new MessageSendRequest([$messageA, $messageB], "login", "password");

        $expectedDom =  new DOMDocument();
        $expectedDom->loadXML($expected);
        $actualDom = new DOMDocument();
        $actualDom->loadXML(strval($req));

        $this->assertEqualXMLStructure(
            $expectedDom->firstChild, $actualDom->firstChild, true
        );

        $this->assertXmlStringEqualsXmlString(
            strval($req),
            $expected,
            true
        );
    }

    public function testXmlSerialize(){
        $abonents = [
            new Abonent("7922458545", "1", "2017-03-0800:00", "2017-03-1800:00", true),
            new Abonent("7922466666", "2", "2017-03-1400:00", "2017-03-2400:00", true),
            new Abonent("7922433333", "3", "2017-03-2500:00", "2017-04-0500:00", true)
        ];
        $type = Message::SMS;
        $messageA = new Message($type, "Message Alpha", $abonents, "ALPHA SENDER", false);
        $messageB = new Message($type, "Message Beta", $abonents, "BETA SENDER", false);

        $req = new MessageSendRequest([$messageA, $messageB], "login", "password");

        $expected  = "<request><security><login value=\"login\" /><password value=\"password\" /></security>";
        $expected .= "<message type=\"{$type}\"><sender>ALPHA SENDER</sender><text>Message Alpha</text>";
        $expected .= "<abonent phone=\"{$abonents[0]->phone}\" client_id_sms=\"{$abonents[0]->clientIdSms}\" number_sms=\"1\" validity_period=\"{$abonents[0]->validityPeriod}\" time_send=\"{$abonents[0]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[1]->phone}\" client_id_sms=\"{$abonents[1]->clientIdSms}\" number_sms=\"2\" validity_period=\"{$abonents[1]->validityPeriod}\" time_send=\"{$abonents[1]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[2]->phone}\" client_id_sms=\"{$abonents[2]->clientIdSms}\" number_sms=\"3\" validity_period=\"{$abonents[2]->validityPeriod}\" time_send=\"{$abonents[2]->timeSend}\" />";
        $expected .= "</message>";
        $expected .= "<message type=\"{$type}\"><sender>BETA SENDER</sender><text>Message Beta</text>";
        $expected .= "<abonent phone=\"{$abonents[0]->phone}\" client_id_sms=\"{$abonents[0]->clientIdSms}\" number_sms=\"4\" validity_period=\"{$abonents[0]->validityPeriod}\" time_send=\"{$abonents[0]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[1]->phone}\" client_id_sms=\"{$abonents[1]->clientIdSms}\" number_sms=\"5\" validity_period=\"{$abonents[1]->validityPeriod}\" time_send=\"{$abonents[1]->timeSend}\" />";
        $expected .= "<abonent phone=\"{$abonents[2]->phone}\" client_id_sms=\"{$abonents[2]->clientIdSms}\" number_sms=\"6\" validity_period=\"{$abonents[2]->validityPeriod}\" time_send=\"{$abonents[2]->timeSend}\" />";
        $expected .= "</message>";
        $expected .= "</request>";
        $expectedDom =  new DOMDocument();
        $expectedDom->loadXML($expected);
        $actualDom = new DOMDocument();
        $actualDom->loadXML(strval($req));

        $this->assertEqualXMLStructure(
            $expectedDom->firstChild, $actualDom->firstChild, true, strval($req)
        );

        $this->assertXmlStringEqualsXmlString(
            strval($req),
            $expected,
            true
        );
    }
}
?>