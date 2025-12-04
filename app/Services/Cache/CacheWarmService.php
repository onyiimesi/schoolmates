<?php

namespace App\Services\Cache;

use App\Services\Cache\FlexibleCacheService;

class CacheWarmService
{
    public function __construct(
        protected FlexibleCacheService $flexibleCacheService,
    )
    {}
    public function warmResultServiceCache(array $data, $user): void
    {
        $this->flexibleCacheService->firstHalf($user, $data);
        $this->flexibleCacheService->secondHalf($user, $data);
    }
}

