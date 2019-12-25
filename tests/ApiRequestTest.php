<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use PHPUnit\Framework\Error\Notice;
use Ybelenko\SmsGorod\ApiRequest;

/**
 * @coversDefaultClass \Ybelenko\SmsGorod\ApiRequest
 */
class ApiRequestTest extends TestCase{

    /**
     * @covers ::__get()
     */
    public function testGetValues() {
        $request = $this->getMockForAbstractClass(ApiRequest::class);
        $this->assertNull($request->errno);
        $this->assertNull($request->error);
        $this->assertNull($request->statusCode);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Переменной unknown не существует');
        $unknown = $request->unknown;
        $this->assertNull($unknown);
    }
}
?>