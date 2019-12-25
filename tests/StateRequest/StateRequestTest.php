<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\StateRequest;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\StateRequest
 */
class StateRequestTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $smsIds,
        $login, 
        $password, 
        $isSilent,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $this->assertEquals('http://web.smsgorod.ru/xml/state.php', StateRequest::STATE_URL);
        $req = new StateRequest($smsIds, $login, $password, $isSilent);
        $reqString = (string) $req;
        $this->assertSame($login, $req->login);
        $this->assertSame($password, $req->password);
        $this->assertXmlStringEqualsXmlFile($expectedXmlLink, $reqString);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($req));
    }

    public function provideConstructorArguments()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'everything correct' => [
                [
                    '3aef8358-e950-45f3-a2b1-a506a3e4017b',
                    '23b3b25f-d30f-452f-b0ac-2dae28391306',
                    '99f5114a-28aa-4555-8332-4a116955e21e',
                    '71cc0959-d2ce-4149-8c47-c1eaf6adec65',
                ],
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
        $smsIds,
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
        $req = new StateRequest($smsIds, $login, $password, $isSilent);
        if ($expectedException) {
            $this->assertNull($req->login);
            $this->assertNull($req->password);
        }
    }

    public function provideConstructorInvalidArguments() {
        $smsIds = [
            '3aef8358-e950-45f3-a2b1-a506a3e4017b',
            '23b3b25f-d30f-452f-b0ac-2dae28391306',
            '99f5114a-28aa-4555-8332-4a116955e21e',
            '71cc0959-d2ce-4149-8c47-c1eaf6adec65',
        ];
        $exClass = \InvalidArgumentException::class;
        $invalidLoginMsg = 'Login should be not empty string';
        $invalidPassMsg = 'Password should be not empty string';
        return [
            'null login' => [$smsIds, null, 'пароль', false, $exClass, $invalidLoginMsg],
            'empty login' => [$smsIds, '', 'пароль', false, $exClass, $invalidLoginMsg],
            'null pass' => [$smsIds, 'логин', null, false, $exClass, $invalidPassMsg],
            'empty pass' => [$smsIds, 'логин', null, false, $exClass, $invalidPassMsg],
            'both null' => [$smsIds, null, null, false, $exClass, $invalidLoginMsg],
            'both empty' => [$smsIds, '', '', false, $exClass, $invalidLoginMsg],
            'both invalid, but silent mode' => [$smsIds, null, null, true, null, null],
        ];
    }
}
?>