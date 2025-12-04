<?php

namespace App\Services\Cache;

use App\Services\Cache\FlexibleCacheService;

class CacheWarmService
{
    public function __construct(
        protected FlexibleCacheService $flexibleCacheService,
    )
    {}

    public function warmResultServiceCache($user, array $data): void
    {
        $this->flexibleCacheService->firstHalf($user, $data);
        $this->flexibleCacheService->secondHalf($user, $data);
    }
}

