<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\ApiResponse;

class ApiResponseTest extends TestCase{

    /**
     * @dataProvider providerResponse
     * @covers Ybelenko\SmsGorod\ApiResponse::__construct()
     */
    public function testConstructor($rawResponse){
        $res = new ApiResponse($rawResponse);
        $this->assertSame($res->statusCode, 200);
        $this->assertSame($res->errno, 0);
        $this->assertEmpty($res->error);
        $this->assertJsonStringEqualsJsonString(
            json_encode($res->errorList),
            json_encode([])
        );
        $this->assertSame($rawResponse, $res->rawResponse);
    }

    /**
     * @dataProvider providerError
     * @covers Ybelenko\SmsGorod\ApiResponse::__construct()
     */
    public function testConstructorWithErrorResponse($rawResponse) {
        $res = new ApiResponse($rawResponse);
        //$res = simplexml_load_string("<response><error>Some Error text</error></response>");
        $this->assertSame($res->statusCode, 500);
        $this->assertSame($res->errno, 500);
        $this->assertSame($res->error, "Some Error text");
        $this->assertJsonStringEqualsJsonString(
            json_encode($res->errorList),
            json_encode([
                [
                    "errno" => 500,
                    "error" =>  "Some Error text"
                ]
            ])
        );
        $this->assertSame($rawResponse, $res->rawResponse);
    }

    /**
     * @covers Ybelenko\SmsGorod\ApiResponse::__construct()
     */
    public function testConstructorWithInvalidInput() {
        $this->expectException(UnexpectedValueException::class);
        $req = new ApiResponse("error");
    }

    /**
     * @dataProvider providerResponse
     * @covers Ybelenko\SmsGorod\ApiResponse::__get()
     */
    public function testGetValues($rawResponse) {
        $this->setExpectedException('PHPUnit_Framework_Error');
        //PHPUnit_Framework_Error_Notice::$enabled = false;
        $res = new ApiResponse($rawResponse);

        $this->assertSame($res->errno, 0);
        $this->assertSame($res->error, '');
        $this->assertSame($res->statusCode, 200);
        $this->assertSame($res->rawResponse, $rawResponse);
        $this->assertSame($res->errorList, []);
        $this->assertNull($res->unknown);
    }

    /**
     * @covers Ybelenko\SmsGorod\ApiResponse::__toString()
     * @dataProvider providerResponse
     */
    public function testToString($rawResponse) {
        $res = new ApiResponse($rawResponse);

        $this->assertInternalType('string', strval($res));
        $this->assertXmlStringEqualsXmlString($res->xmlSerialize()->asXML(), strval($res), true);
    }

    /**
     * @dataProvider providerError
     * @covers Ybelenko\SmsGorod\ApiResponse::xmlSerialize()
     */
    public function testXmlSerialize($rawResponse) {
        $res = new ApiResponse($rawResponse);

        $xml = $res->xmlSerialize();
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);

        $this->assertXmlStringEqualsXmlString(
            $xml->asXML(),
            $rawResponse,
            true
        );
    }

    /**
     * @dataProvider providerError
     * @covers Ybelenko\SmsGorod\ApiResponse::jsonSerialize()
     */
    public function testJsonSerialize($rawResponse) {
        $res = new ApiResponse($rawResponse);

        $json = $res->jsonSerialize();
        $this->assertInternalType('array', $json);

        $this->assertArrayHasKey('errno', $json);
        $this->assertArrayHasKey('error', $json);
        $this->assertArrayHasKey('error_list', $json);
        $this->assertArrayHasKey('status_code', $json);

        $this->assertJsonStringEqualsJsonString(
            json_encode($json),
            json_encode([
                "errno" => $res->errno,
                "error" => $res->error,
                "error_list" => $res->errorList,
                "status_code" => $res->statusCode
            ])
        );
    }

    public function providerError() {
        return [
            ["<response><error>Some Error text</error></response>"]
        ];
    }

    public function providerResponse() {
        return [
            ["<response><sms area=\"somearea\">1</sms><sms area=\"somearea\">1</sms><money currency=\"rub\">55</money></response>"]
        ];
    }

}
?>