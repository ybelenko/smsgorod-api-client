<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;
use Ybelenko\SmsGorod\Message;
use Ybelenko\SmsGorod\MessageSendRequest;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\MessageSendRequest
 */
class MessageSendRequestTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $messages,
        $login, 
        $password, 
        $isSilent,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $this->assertEquals('http://web.smsgorod.ru/xml/', MessageSendRequest::SEND_URL);
        $req = new MessageSendRequest($messages, $login, $password, $isSilent);
        $reqString = (string) $req;
        $this->assertSame($login, $req->login);
        $this->assertSame($password, $req->password);
        $this->assertXmlStringEqualsXmlFile($expectedXmlLink, $reqString);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($req));
    }

    public function provideConstructorArguments()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        $messages = [
            new Message('sms', 'Текст сообщения 1', [
                new Abonent('79033256699', '301'),
                new Abonent('79033256699', '302'),
            ], 'VIRTA'),
            new Message('sms', 'Текст сообщения 2', [
                new Abonent('79033256699', '303'),
                new Abonent('79033256699', '304'),
            ], 'VIRTA'),
        ];
        return [
            'everything correct' => [
                $messages,
                'логин', 
                'пароль', 
                false, 
                $fixtureDir . '/request.xml',
                $fixtureDir . '/request.json'
            ],
        ];
    }

    /**
     * @covers ::__construct()
     * @dataProvider provideConstructorInvalidArguments
     */
    public function testBadConstructor(
        $messages,
        $login,
        $password,
        $isSilent,
        $expectedException,
        $expectedExceptionMessage
    ) {
        if ($expectedException) {
            $this->expectException($expectedException);
        }
        if ($expectedExceptionMessage) {
            $this->expectExceptionMessage($expectedExceptionMessage);
        }
        $req = new MessageSendRequest($messages, $login, $password, $isSilent);
        if ($expectedException) {
            $this->assertNull($req->login);
            $this->assertNull($req->password);
        }
    }

    public function provideConstructorInvalidArguments() {
        $messages = [
        ];
        $exClass = \InvalidArgumentException::class;
        $invalidLoginMsg = 'Login should be not empty string';
        $invalidPassMsg = 'Password should be not empty string';
        return [
            'null login' => [$messages, null, 'пароль', false, $exClass, $invalidLoginMsg],
            'empty login' => [$messages, '', 'пароль', false, $exClass, $invalidLoginMsg],
            'null pass' => [$messages, 'логин', null, false, $exClass, $invalidPassMsg],
            'empty pass' => [$messages, 'логин', null, false, $exClass, $invalidPassMsg],
            'both null' => [$messages, null, null, false, $exClass, $invalidLoginMsg],
            'both empty' => [$messages, '', '', false, $exClass, $invalidLoginMsg],
            'both invalid, but silent mode' => [$messages, null, null, true, null, null],
        ];
    }
}
?>