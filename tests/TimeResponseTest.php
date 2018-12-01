<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use Ybelenko\SmsGorod\TimeResponse;

class TimeResponseTest extends TestCase {
    /**
     * @covers Ybelenko\SmsGorod\TimeResponse::__construct()
     */
    public function testGoodConstructor(){
        $expected  = "<response>";
        $expected .= "<time>15:34:05</time>";
        $expected .= "</response>";

        $res = new TimeResponse($expected);
        $this->assertSame($res->time, "15:34:05");
    }

    /**
     * @covers Ybelenko\SmsGorod\TimeResponse::__construct()
     */
    public function testBadConstructor(){
        $expected  = "<response>";
        $expected .= "<error>текст ошибки</error>";
        $expected .= "</response>";

        $res = new TimeResponse($expected);
        $this->assertSame($res->error, "текст ошибки");
    }
}
?>