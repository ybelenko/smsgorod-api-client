<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\ApiResponse;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\ApiResponse
 */
class ApiResponseTest extends TestCase{

    /**
     * @covers ::__construct()
     * @covers ::__get()
     * @covers ::__toString()
     * @covers ::xmlSerialize()
     * @covers ::jsonSerialize()
     * @dataProvider provideResponses
     */
    public function testConstructor($responseLink, $expectedJsonLink) {
        $rawResponse = file_get_contents($responseLink);
        $res = new ApiResponse($rawResponse);
        $this->assertJsonStringEqualsJsonFile($expectedJsonLink, json_encode($res));
        $this->assertXmlStringEqualsXmlFile($responseLink, (string) $res);
        $this->assertInternalType('integer', $res->errno);
        $this->assertInternalType('string', $res->error);
        $this->assertInternalType('integer', $res->statusCode);
        $this->assertInternalType('array', $res->errorList);
        $this->assertInternalType('string', $res->rawResponse);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Переменной silentMode не существует');
        $silentMode = $res->silentMode;
    }

    public function provideResponses()
    {
        $fixtureDir = __DIR__ . '/fixtures';
        return [
            'basic example' => [
                $fixtureDir . '/response.xml',
                $fixtureDir . '/response.json',
            ],
            'error response' => [
                $fixtureDir . '/response_error.xml',
                $fixtureDir . '/response_error.json',
            ],
        ];
    }

    /**
     * @covers ::__construct()
     */
    public function testConstructorWithBadResponse()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ответ должен быть валидным XML документом');
        $res = new ApiResponse(null);
    }
}
?>