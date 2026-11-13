<?php

namespace Tests\Unit;

use App\Support\Freshness;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FreshnessTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_it_reports_all_freshness_states(): void
    {
        Carbon::setTestNow('2026-11-13');
        self::assertSame('belum_diverifikasi', Freshness::status(null));
        self::assertSame('terverifikasi', Freshness::status('2026-10-01'));
        self::assertSame('perlu_verifikasi_ulang', Freshness::status('2026-08-01'));
    }
}
