<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\MessageSendResponse;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\MessageSendResponse
 */
class MessageSendResponseTest extends TestCase {

    /**
     * @covers ::__construct()
     * @covers ::__get()
     * @covers ::jsonSerialize()
     * @dataProvider provideResponses
     */
    public function testConstructor($responseLink, $expectedJsonLink) {
        $rawResponse = file_get_contents($responseLink);
        $res = new MessageSendResponse($rawResponse);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($res));
        $this->assertIsArray($res->sms);
        $this->assertIsInt($res->statusCode);
    }

    public function provideResponses()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'basic example' => [
                $fixtureDir . '/response.xml',
                $fixtureDir . '/response.json',
            ],
            'response with single state' => [
                $fixtureDir . '/response_single.xml',
                $fixtureDir . '/response_single.json',
            ],
            'error response' => [
                $fixtureDir . '/response_error.xml',
                $fixtureDir . '/response_error.json',
            ],
        ];
    }
}
?>