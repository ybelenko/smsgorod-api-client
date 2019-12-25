<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\SmsGorod;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\SmsGorod
 */
class SmsGorodTest extends TestCase {

    /**
     * @covers ::__construct()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $login,
        $password,
        $isSilent
    ) {
        $api = new SmsGorod($login, $password, $isSilent);
        $this->assertSame($login, $api->login);
        $this->assertSame($password, $api->password);
    }

    public function provideConstructorArguments()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'everything correct' => [
                'логин',
                'пароль',
                false,
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
        $api = new SmsGorod($login, $password, $isSilent);
        if ($expectedException) {
            $this->assertNull($api->login);
            $this->assertNull($api->password);
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