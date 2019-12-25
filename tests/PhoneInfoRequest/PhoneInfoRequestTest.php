<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\PhoneInfoRequest;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\PhoneInfoRequest
 */
class PhoneInfoRequestTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $phones,
        $login, 
        $password, 
        $isSilent,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $this->assertEquals('http://web.smsgorod.ru/xml/def.php', PhoneInfoRequest::INFO_URL);
        $req = new PhoneInfoRequest($phones, $login, $password, $isSilent);
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
                    '79612242243',
                    '79612242244',
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
        $phones,
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
        $req = new PhoneInfoRequest($phones, $login, $password, $isSilent);
        if ($expectedException) {
            $this->assertNull($req->login);
            $this->assertNull($req->password);
        }
    }

    public function provideConstructorInvalidArguments() {
        $phones = [
            '79612242243',
            '79612242244',
        ];
        $exClass = \InvalidArgumentException::class;
        $invalidLoginMsg = 'Login should be not empty string';
        $invalidPassMsg = 'Password should be not empty string';
        return [
            'null login' => [$phones, null, 'пароль', false, $exClass, $invalidLoginMsg],
            'empty login' => [$phones, '', 'пароль', false, $exClass, $invalidLoginMsg],
            'null pass' => [$phones, 'логин', null, false, $exClass, $invalidPassMsg],
            'empty pass' => [$phones, 'логин', null, false, $exClass, $invalidPassMsg],
            'both null' => [$phones, null, null, false, $exClass, $invalidLoginMsg],
            'both empty' => [$phones, '', '', false, $exClass, $invalidLoginMsg],
            'both invalid, but silent mode' => [$phones, null, null, true, null, null],
        ];
    }
}
?>