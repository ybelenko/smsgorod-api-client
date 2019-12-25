<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\TimeRequest;

/**
 * @coversDefaultClass Ybelenko\SmsGorod\TimeRequest
 */
class TimeRequestTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $login, 
        $password, 
        $isSilent,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $this->assertEquals('http://web.smsgorod.ru/xml/time.php', TimeRequest::TIME_URL);
        $req = new TimeRequest($login, $password, $isSilent);
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
        $req = new TimeRequest($login, $password, $isSilent);
        if ($expectedException) {
            $this->assertNull($req->login);
            $this->assertNull($req->password);
        }
    }

    public function provideConstructorInvalidArguments() {
        $exClass = \InvalidArgumentException::class;
        $invalidLoginMsg = 'Login should be not empty string';
        $invalidPassMsg = 'Password should be not empty string';
        return [
            'null login' => [null, 'пароль', false, $exClass, $invalidLoginMsg],
            'empty login' => ['', 'пароль', false, $exClass, $invalidLoginMsg],
            'null pass' => ['логин', null, false, $exClass, $invalidPassMsg],
            'empty pass' => ['логин', null, false, $exClass, $invalidPassMsg],
            'both null' => [null, null, false, $exClass, $invalidLoginMsg],
            'both empty' => ['', '', false, $exClass, $invalidLoginMsg],
            'both invalid, but silent mode' => [null, null, true, null, null],
        ];
    }
}
?>