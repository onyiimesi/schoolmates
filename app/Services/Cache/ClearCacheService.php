<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class ClearCacheService
{
    public function clearResultServiceCache(array $data): void
    {
        $firstMemo = $this->buildMemoKey('first_half', $data);
        $secondMemo = $this->buildMemoKey('second_half', $data);

        Cache::memo()->forget($firstMemo);
        Cache::memo()->forget($secondMemo);

        $firstHalfCacheKey = $this->buildCacheKey('first_half', $data);
        $secondHalfCacheKey = $this->buildCacheKey('second_half', $data);

        Cache::forget($firstHalfCacheKey);
        Cache::forget($secondHalfCacheKey);

        Cache::forget("cache:flexible:created:{$firstHalfCacheKey}");
        Cache::forget("cache:flexible:created:{$secondHalfCacheKey}");
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
            $data['class'],
        ]);
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
