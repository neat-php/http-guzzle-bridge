<?php

namespace Neat\Http\Guzzle\Test;

use Neat\Http\Guzzle\Transmitter;
use PHPUnit\Framework\TestCase;

class TransmitterTest extends TestCase
{
    public function testHtmlResponse()
    {
        $factory = new Transmitter();

        $response = $factory->html('Hello World!');
        $this->assertSame('Hello World!', $response->body());
        $this->assertSame('text/html', $response->contentType()->getValue());
    }

    public function testJsonResponse()
    {
        $factory = new Transmitter();

        $response = $factory->json(['test' => 1]);
        $this->assertSame('{"test":1}', $response->body());
        $this->assertSame('application/json', $response->contentType()->getValue());
    }
}
