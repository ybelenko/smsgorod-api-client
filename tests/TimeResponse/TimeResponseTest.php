<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\TimeResponse;

/**
 * @coversDefaultClass Ybelenko\SmsGorod\TimeResponse
 */
class TimeResponseTest extends TestCase {
    /**
     * @covers ::__construct()
     * @covers ::__get()
     * @covers ::jsonSerialize()
     * @dataProvider provideResponses
     */
    public function testConstructor($responseLink, $expectedValues, $expectedJsonLink){
        $rawResponse = file_get_contents($responseLink);
        $res = new TimeResponse($rawResponse);
        foreach ($expectedValues as $prop => $expectedValue) {
            $this->assertSame($expectedValue, $res->$prop);
        }
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($res));
    }

    public function provideResponses()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'basic example' => [
                $fixtureDir . '/response.xml',
                [
                    'time' => '15:34:05',
                    'error' => '',
                ],
                $fixtureDir . '/response.json',
            ],
            'example with different timezone' => [
                $fixtureDir . '/response_timezone_different.xml',
                [
                    'time' => '2012-12-17 18:34:27',
                    'error' => '',
                ],
                $fixtureDir . '/response_timezone_different.json',
            ],
            'error response' => [
                $fixtureDir . '/response_error.xml',
                [
                    'time' => null,
                    'error' => 'текст ошибки',
                ],
                $fixtureDir . '/response_error.json',
            ],
        ];
    }
}
?>