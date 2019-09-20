<?php

namespace Tests\ManagementBundle\Http;

use ManagementBundle\Http\RestErrorResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class RestErrorResponseTest extends TestCase
{
    public function testErrorResponse()
    {
        $errorResponse = new RestErrorResponse('Test error', 400);

        $this->assertInstanceOf(JsonResponse::class, $errorResponse);
        $this->assertEquals(400, $errorResponse->getStatusCode());
        $this->assertEquals(json_encode(['errors' => ['message' => 'Test error']]), $errorResponse->getContent());
    }
}
