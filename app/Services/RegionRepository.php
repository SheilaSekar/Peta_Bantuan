<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RegionRepository
{
    public function all(): array
    {
        $path = resource_path('data/wilayah.json');
        $key = 'peta.regions.'.hash_file('sha256', $path);

        return Cache::rememberForever($key, fn () => json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR));
    }
}
