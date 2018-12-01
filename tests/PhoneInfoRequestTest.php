<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\PhoneInfoRequest;

class PhoneInfoRequestTest extends TestCase{

    public function testXmlSerialize(){

        $expected  = "<request>";
        $expected .= "<security>";
        $expected .= "<login value=\"логин\" />";
        $expected .= "<password value=\"пароль\" />";
        $expected .= "</security>";
        $expected .= "<phones>";
        $expected .= "<phone>79612242243</phone>";
        $expected .= "<phone>79612242244</phone>";
        $expected .= "</phones>";
        $expected .= "</request>";

        $req = new PhoneInfoRequest(["79612242243", "79612242244"], "логин", "пароль", false);
        $expectedDom = new DOMDocument();
        $expectedDom->loadXML($expected);
        $actualDom = new DOMDocument();
        $actualDom->loadXML(strval($req));

        $this->assertEqualXMLStructure(
            $expectedDom->firstChild, $actualDom->firstChild, true, strval($req)
        );

        $this->assertXmlStringEqualsXmlString(
            strval($req),
            $expected,
            true
        );
    }
}
?>