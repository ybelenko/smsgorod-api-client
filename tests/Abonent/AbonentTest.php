<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\Abonent
 */
class AbonentTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $phone,
        $clientIdSms,
        $timeSend,
        $validityPeriod,
        $silentMode,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $abonent = new Abonent($phone, $clientIdSms, $timeSend, $validityPeriod, $silentMode);
        $this->assertXmlStringEqualsXmlFile($expectedXmlLink, (string) $abonent);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($abonent));
    }

    public function provideConstructorArguments()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            [
                '79033256699',
                '101',
                '2001-12-31 12:34',
                '2001-12-31 15:34',
                false,
                $fixtureDir . '/abonent1.xml',
                $fixtureDir . '/abonent1.json',
            ],
            [
                '79033256699',
                '102',
                '2001-12-31 12:35',
                null,
                false,
                $fixtureDir . '/abonent2.xml',
                $fixtureDir . '/abonent2.json',
            ],
        ];
    }

    /**
     * @covers ::__set()
     * @covers ::__get()
     * @dataProvider provideDataForGetterAndSetter
     */
    public function testSetterAndGetter(
        $name,
        $value,
        $expectedException = null,
        $expectedExceptionMessage = null
    ) {
        $abonent = new Abonent('79033256699', '102', '2001-12-31 12:35', null, false);
        if ($expectedException) {
            $this->expectException($expectedException);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }
        $abonent->$name = $value;
        if ($expectedException === null) {
            $this->assertSame($value, $abonent->$name);
        }
    }

    public function provideDataForGetterAndSetter()
    {
        return [
            ['phone', '79033256699'],
            ['phone', null, \InvalidArgumentException::class, 'Номер телефона обязательный параметр'],
            ['phone', '', \InvalidArgumentException::class, 'Номер телефона обязательный параметр'],
            ['client_id_sms', '102'],
            ['time_send', '2001-12-31 12:35'],
            ['validity_period', '2001-12-31 12:35'],
            ['silentMode', true, \InvalidArgumentException::class, 'Переменной silentMode не существует'],
        ];
    }

    /**
     * @covers ::__get()
     * @covers ::__set()
     */
    public function testGetterAndSetterInSilentMode()
    {
        $abonent = new Abonent('79033256699', '102', '2001-12-31 12:35', null, true);
        $silentMode = $abonent->silentMode;
        $this->assertNull($silentMode);
    }

    /**
     * @covers ::__get()
     * @covers ::__set()
     */
    public function testGetterAndSetterWithoutSilentMode()
    {
        $abonent = new Abonent('79033256699', '102', '2001-12-31 12:35', null, false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Переменной silentMode не существует');
        $silentMode = $abonent->silentMode;
    }
}

?>