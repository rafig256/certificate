<?php

namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Cache;

class CertificateViewService
{
    public static function handle(Certificate $certificate): void
    {
        $sessionId = session()->getId();

        $cacheKey = "certificate:viewed:{$certificate->id}:{$sessionId}";

        if (Cache::has($cacheKey)) {
            return;
        }

        $certificate->increment('views');

        Cache::put(
            $cacheKey,
            true,
            now()->addHours(18)
        );
    }
}
