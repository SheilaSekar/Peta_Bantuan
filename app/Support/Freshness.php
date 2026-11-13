<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class Freshness
{
    public static function status(?string $date, int $days = 90): string
    {
        if (! $date) {
            return 'belum_diverifikasi';
        }

        return CarbonImmutable::parse($date)->startOfDay()->diffInDays(today(), false) > $days ? 'perlu_verifikasi_ulang' : 'terverifikasi';
    }
}
