<?php

namespace ManagementBundle\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class RestErrorResponse extends JsonResponse
{
    public function __construct(string $message, int $statusCode)
    {
        parent::__construct(['error' => [
            'message' => $message
        ]], $statusCode);
    }
}
