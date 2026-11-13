<?php

namespace Tests\Feature;

use Tests\TestCase;

class ValidateDataCommandTest extends TestCase
{
    public function test_bundled_service_data_passes_validation(): void
    {
        $this->artisan('peta:validasi-data')->assertExitCode(0);
    }

    public function test_invalid_fixture_fails_validation(): void
    {
        $this->artisan('peta:validasi-data', ['--file' => base_path('tests/Fixtures/invalid-services.json')])->assertExitCode(1);
    }
}
