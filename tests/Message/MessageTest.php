<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\Abonent;
use Ybelenko\SmsGorod\Message;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\Message
 */
class MessageTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideConstructorArguments
     */
    public function testConstructor(
        $type,
        $text,
        $abonents,
        $sender,
        $silentMode,
        $expectedXmlLink,
        $expectedJsonLink
    ) {
        $message = new Message($type, $text, $abonents, $sender, $silentMode);
        $msgString = (string) $message;
        $this->assertXmlStringEqualsXmlFile($expectedXmlLink, $msgString);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($message));
    }

    public function provideConstructorArguments()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'multiple abonents' => [
                'sms',
                'Текст сообщения 1',
                [
                    new Abonent('79033256699', '101', '2001-12-31 12:34', '2001-12-31 15:34'),
                    new Abonent('79033256699', '102', '2001-12-31 12:35'),
                    new Abonent('79033256699', '110'),
                ],
                'Отправитель 1',
                false,
                $fixtureDir . '/message.xml',
                $fixtureDir . '/message.json'
            ],
            'single abonent' => [
                'sms',
                'Текст сообщения 1',
                [
                    new Abonent('79033256699', '101', '2001-12-31 12:34', '2001-12-31 15:34'),
                ],
                'Отправитель 1',
                false,
                $fixtureDir . '/message_single_abonent.xml',
                $fixtureDir . '/message_single_abonent.json'
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
        $message = new Message('wappush', 'foobar', [], 'sender', false);
        if ($expectedException) {
            $this->expectException($expectedException);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }
        $message->$name = $value;
        if ($expectedException === null) {
            $this->assertSame($value, $message->$name);
        }
    }

    public function provideDataForGetterAndSetter()
    {
        return [
            ['type', 'flash_sms'],
            ['text', 'Hello World'],
            ['abonents', [new Abonent('79033256699', '101')]],
            ['sender', 'John Doe'],
            ['type', 'foobaz', \InvalidArgumentException::class, 'Тип сообщения может быть flash_sms, sms, wappush, vcard'],
            ['text', '', \InvalidArgumentException::class, 'Текст сообщения не должен быть пустым'],
            ['abonents', 'foobaz', \InvalidArgumentException::class, 'Abonents must be an array'],
            ['sender', '', \InvalidArgumentException::class, 'Отправитель сообщения не должен быть пустой строкой'],
            ['silentMode', true, \InvalidArgumentException::class, 'Переменной silentMode не существует'],
        ];
    }

    /**
     * @covers ::__get()
     * @covers ::__set()
     */
    public function testGetterAndSetterInSilentMode()
    {
        $message = new Message('sms', 'foobar', [], 'sender', true);
        $silentMode = $message->silentMode;
        $this->assertNull($silentMode);
    }

    /**
     * @covers ::__get()
     * @covers ::__set()
     */
    public function testGetterAndSetterWithoutSilentMode()
    {
        $message = new Message('sms', 'foobar', [], 'sender', false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Переменной silentMode не существует');
        $silentMode = $message->silentMode;
    }
}
?>