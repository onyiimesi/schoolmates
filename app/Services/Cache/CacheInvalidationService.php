<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class CacheInvalidationService
{
    public function clearResultServiceCache(array $data): void
    {
        Cache::forget($this->buildMemoKey('first_half', $data));
        Cache::forget($this->buildMemoKey('second_half', $data));

        $firstHalfCacheKey = $this->buildCacheKey('first_half', $data);
        $secondHalfCacheKey = $this->buildCacheKey('second_half', $data);

        Cache::forget($firstHalfCacheKey);
        Cache::forget($secondHalfCacheKey);

        Cache::forget("illuminate:cache:flexible:created:{$firstHalfCacheKey}");
        Cache::forget("illuminate:cache:flexible:created:{$secondHalfCacheKey}");
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
            $data['status'],
        ]);
    }

    private function buildMemoKey(string $type, array $data): string
    {
        return implode('memo_', [
            $type,
            $data['student_id'],
            $data['period'],
            $data['term'],
            $data['session'],
            $data['result_type'],
            $data['status'],
        ]);
    }
}
