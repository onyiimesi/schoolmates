<?php

namespace App\Services\Cache;

use App\Services\GeneralResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class FlexibleCacheService extends GeneralResultService
{
    public function firstHalf($user, array $data): JsonResponse
    {
        $cacheKey = $this->buildCacheKey('first_half',$data);

        return Cache::flexible($cacheKey, [200, 300], fn () => parent::firstHalf($user, $data));
    }

    public function secondHalf($user, array $data): JsonResponse
    {
        $cacheKey = $this->buildCacheKey('second_half', $data);

        return Cache::flexible($cacheKey, [200, 300], fn () => parent::secondHalf($user, $data));
    }

    private function buildCacheKey(string $type, array $data): string
    {
        return implode(':', [
            $type,
            $data['student_id'],
            $data['period'],
            $data['term'],
            $data['session'],
            $data['result_type'],
        ]);
    }
}
