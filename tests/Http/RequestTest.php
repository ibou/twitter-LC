<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Twitter\Http\Request;

class RequestTest extends TestCase
{
    public function test_we_can_instantiate_request()
    {
        $request = new Request(
            [
                'author' => 'ibou',
                'content' => 'a tweet test',
            ]
        );
        $this->assertEquals('ibou', $request->get('author'));
        $this->assertEquals('a tweet test', $request->get('content'));
        $this->assertNull($request->get('authornonvalid'));
    }
}
