<?php

use CalDAVClient\Facade\Responses\EventCreatedResponse;
use CalDAVClient\Facade\Responses\EventUpdatedResponse;
use CalDAVClient\Facade\Responses\EventDeletedResponse;
use PHPUnit\Framework\TestCase;

final class ResponseMethodsTest extends TestCase
{
    public function testEventCreatedResponseHasCorrectMethods()
    {
        $response = new EventCreatedResponse('uid', 'etag', 'url', '', 201);

        $this->assertTrue(method_exists($response, 'isSuccessFull'));
        $this->assertTrue(method_exists($response, 'getCode'));
        $this->assertFalse(method_exists($response, 'getStatusCode'));
    }

    public function testEventUpdatedResponseHasCorrectMethods()
    {
        $response = new EventUpdatedResponse('uid', 'etag', 'url', '', 204);

        $this->assertTrue(method_exists($response, 'isSuccessFull'));
        $this->assertTrue(method_exists($response, 'getCode'));
    }

    public function testEventDeletedResponseHasCorrectMethods()
    {
        $response = new EventDeletedResponse('', 204);

        $this->assertTrue(method_exists($response, 'isSuccessFull'));
        $this->assertTrue(method_exists($response, 'getCode'));
    }
}
