<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class HttpResponse extends JsonResponse
{
    /**
     * @param int $statusCode
     * @param array|null $data
     * @return JsonResponse
     */
    public static function withArray(int $statusCode, ?array $data = []): JsonResponse
    {
        return (new JsonResponse())->setStatusCode($statusCode)->setData($data);
    }

    /**
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function withoutBody(int $statusCode): JsonResponse
    {
        return (new JsonResponse())->setStatusCode($statusCode);
    }
}