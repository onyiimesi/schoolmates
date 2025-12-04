<?php

namespace App\Services\Cache;

use App\Services\Cache\FlexibleCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MemoizedCacheService
{
    public function __construct(
        protected FlexibleCacheService $flexibleCacheService,
    ) {}

    public function firstHalf($user, array $data): JsonResponse
    {
        $memoKey = $this->buildMemoKey('first_half', $data);

        return Cache::memo()->remember($memoKey, 3600, fn () => $this->flexibleCacheService->firstHalf($user, $data));
    }

    public function secondHalf($user, array $data): JsonResponse
    {
        $memoKey = $this->buildMemoKey('second_half',  $data);

        return Cache::memo()->remember($memoKey, 3600, fn () => $this->flexibleCacheService->secondHalf($user, $data));
    }

    private function buildMemoKey(string $type, array $data): string
    {
        return implode('_memo_', [
            $type,
            $data['student_id'],
            $data['period'],
            $data['term'],
            $data['session'],
            $data['result_type'],
            $data['class'],
        ]);
    }
}
