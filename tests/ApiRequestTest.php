<?php

/**
 * @author Yuriy Belenko
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use PHPUnit\Framework\Error\Notice;
use Ybelenko\SmsGorod\ApiRequest;

class ApiRequestTest extends TestCase{

    /**
     * @covers Ybelenko\SmsGorod\ApiRequest::__get()
     */
    public function testGetValues(){
        $this->setExpectedException('PHPUnit_Framework_Error');
        //PHPUnit_Framework_Error_Notice::$enabled = false;
        $request = $this->getMockForAbstractClass(ApiRequest::class);

        $this->assertNull($request->errno);
        $this->assertNull($request->error);
        $this->assertNull($request->statusCode);
        $this->assertNull($request->unknown);
    }

}
?>