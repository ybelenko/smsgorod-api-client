<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\StateRequest;

class StateRequestTest extends TestCase {

    public function testXmlSerialize() {

        $expected  = "<request>";
        $expected .= "<security>";
        $expected .= "<login value=\"логин\" />";
        $expected .= "<password value=\"пароль\" />";
        $expected .= "</security>";
        $expected .= "<get_state>";
        $expected .= "<id_sms>3aef8358-e950-45f3-a2b1-a506a3e4017b</id_sms>";
        $expected .= "<id_sms>23b3b25f-d30f-452f-b0ac-2dae28391306</id_sms>";
        $expected .= "<id_sms>99f5114a-28aa-4555-8332-4a116955e21e</id_sms>";
        $expected .= "<id_sms>71cc0959-d2ce-4149-8c47-c1eaf6adec65</id_sms>";
        $expected .= "</get_state>";
        $expected .= "</request>";

        $req = new StateRequest([
            "3aef8358-e950-45f3-a2b1-a506a3e4017b",
            "23b3b25f-d30f-452f-b0ac-2dae28391306",
            "99f5114a-28aa-4555-8332-4a116955e21e",
            "71cc0959-d2ce-4149-8c47-c1eaf6adec65"
        ], "логин", "пароль", false);

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