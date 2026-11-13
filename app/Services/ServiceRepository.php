<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ServiceRepository
{
    public function all(): array
    {
        $path = resource_path('data/layanan.json');
        $key = 'peta.services.'.hash_file('sha256', $path);

        return Cache::rememberForever($key, fn () => json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR));
    }

    public function find(string $slug): ?array
    {
        foreach ($this->all() as $service) {
            if ($service['slug'] === $slug) {
                return $service;
            }
        }

        return null;
    }
}
