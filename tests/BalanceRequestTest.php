<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\BalanceRequest;
use Ybelenko\SmsGorod\BalanceResponse;

class BalanceRequestTest extends TestCase{

    /**
     * @dataProvider providerInvalidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::__construct()
     */
    public function testConstructorWithInvalidCredentials($login, $password){
        $req = new BalanceRequest($login, $password);

        $this->assertSame($req->login, $login);
        $this->assertSame($req->password, $password);
    }

    /**
     * @dataProvider providerValidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::__construct()
     */
    public function testConstructorWithValidCredentials($login, $password){
        $req = new BalanceRequest($login, $password);

        $this->assertSame($req->login, $login);
        $this->assertSame($req->password, $password);
    }

    /**
     * @dataProvider providerExceptionCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::__construct()
     */
    public function testWaitForConstructorException($login, $password) {
        $this->expectException(InvalidArgumentException::class);

        $req = new BalanceRequest($login, $password);
    }

    /**
     * @dataProvider providerValidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::execute()
     */
    public function testExecuteWithGoodCredentials($login, $password) {
        $req = new BalanceRequest($login, $password);
        $result = new BalanceResponse('<response><sms area="Россия">16</sms><sms area="МТС">13</sms><sms area="Мегафон">13</sms><sms area="Теле2">13</sms><sms area="Мотив">29</sms><sms area="Билайн">14</sms><sms area="Yota">11</sms><sms area="SMARTS">11</sms><sms area="Остальные">13</sms><money currency="RUB">20.67</money></response>');

        $this->assertNotEmpty($result, "Response should containt XML document");
        $this->assertEquals($result->statusCode, 200);
        $this->assertEquals($result->errno, 0);
        $this->assertEmpty($result->error);
        $this->assertInstanceOf(SimpleXMLElement::class, simplexml_load_string($result));

        $expected = new DOMDocument;
        $expectedXML  = "<response>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<sms area=\"somearea\">1</sms>";
        $expectedXML .= "<money currency=\"rub\">55</money>";
        $expectedXML .= "</response>";
        $expected->loadXML($expectedXML);


        $actual = new DOMDocument;
        $actual->loadXML(strval($result));

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true, strval($result)
        );

        $this->assertXmlStringEqualsXmlString(
            strval($req),
            "<request><security><login value=\"{$login}\"/><password value=\"{$password}\"/></security></request>",
            true
        );
    }

    /**
     * @dataProvider providerInvalidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::execute()
     */
    public function testExecuteWithBadCredentials($login, $password) {
        //$req = new BalanceRequest($login, $password);

        $result = new BalanceResponse('<response><error>Неверный логин или пароль</error></response>');

        $this->assertNotEmpty($result, "Response should containt XML document");
        $this->assertEquals($result->statusCode, 500);
        $this->assertEquals($result->errno, 500);
        $this->assertSame($result->error, "Неверный логин или пароль");
        $this->assertInstanceOf(SimpleXMLElement::class, simplexml_load_string($result));

        $expected = new DOMDocument;
        $expected->loadXML("<response><error>Some Error text</error></response>");

        $actual = new DOMDocument;
        $actual->loadXML(strval($result));

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true, strval($result)
        );

        $this->assertXmlStringEqualsXmlString(
            strval($result),
            "<response><error>{$result->error}</error></response>",
            true
        );
    }

    /**
     * @dataProvider providerInvalidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::__toString()
     */
    public function testToString($login, $password) {
        $req = new BalanceRequest($login, $password);

        $this->assertInternalType('string', strval($req));
        $this->assertXmlStringEqualsXmlString($req->xmlSerialize()->asXML(), strval($req), true);

        $xml = simplexml_load_string(strval($req));
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);

        $this->assertEquals((string) $xml->security->login->attributes()->value, $login);
        $this->assertEquals((string) $xml->security->password->attributes()->value, $password);

        $this->assertXmlStringEqualsXmlString(
            $xml->asXML(),
            "<request><security><login value=\"{$login}\"/><password value=\"{$password}\"/></security></request>",
            true
        );
    }

    /**
     * @dataProvider providerInvalidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::xmlSerialize()
     */
    public function testXmlSerialize($login, $password) {
        $req = new BalanceRequest($login, $password);

        $xml = $req->xmlSerialize();
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);

        $this->assertEquals((string) $xml->security->login->attributes()->value, $login);
        $this->assertEquals((string) $xml->security->password->attributes()->value, $password);

        $this->assertXmlStringEqualsXmlString(
            $xml->asXML(),
            "<request><security><login value=\"{$login}\"/><password value=\"{$password}\"/></security></request>",
            true
        );
    }

    /**
     * @dataProvider providerInvalidCredentials
     * @covers Ybelenko\SmsGorod\BalanceRequest::jsonSerialize()
     */
    public function testJsonSerialize($login, $password) {
        $req = new BalanceRequest($login, $password);

        $json = $req->jsonSerialize();
        $this->assertInternalType('array', $json);

        $this->assertArrayHasKey('security', $json);
        $this->assertArrayHasKey('login', $json["security"]);
        $this->assertArrayHasKey('password', $json["security"]);

        $this->assertEquals($json["security"]["login"], $login);
        $this->assertEquals($json["security"]["password"], $password);

        $this->assertJsonStringEqualsJsonString(
            json_encode($json),
            json_encode([
                "security" => [
                    "login" => $login,
                    "password" =>  $password
                ]
            ])
        );
    }

    public function providerValidCredentials(){
        return [
            ["ybelenko", "19550625"]
        ];
    }

    public function providerInvalidCredentials(){
        return [
            ["user", "password"],
            ["sandbox", "sanbox"],
            ["admin", "admin"],
            ["admin", "12345"]
        ];
    }

    public function providerExceptionCredentials(){
        return [
            [12454787, null],
            ["", ""],
            ["login", null],
            [0, 0]
        ];
    }
}
?>