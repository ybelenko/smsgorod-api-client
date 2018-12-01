<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\TimeRequest;

class TimeRequestTest extends TestCase {

    public function testXmlSerialize(){
        $expected  = "<request>";
        $expected .= "<security>";
        $expected .= "<login value=\"логин\" />";
        $expected .= "<password value=\"пароль\" />";
        $expected .= "</security>";
        $expected .= "</request>";

        $req = new TimeRequest("логин", "пароль", false);

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