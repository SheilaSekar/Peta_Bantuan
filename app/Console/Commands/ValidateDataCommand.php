<?php

namespace App\Console\Commands;

use App\Support\Freshness;
use App\Support\ServiceType;
use Illuminate\Console\Command;

class ValidateDataCommand extends Command
{
    protected $signature = 'peta:validasi-data {--file= : Berkas layanan JSON alternatif untuk audit}';

    protected $description = 'Memvalidasi berkas data wilayah dan layanan.';

    public function handle(): int
    {
        $regions = json_decode(file_get_contents(resource_path('data/wilayah.json')), true);
        $serviceFile = $this->option('file') ?: resource_path('data/layanan.json');
        $services = is_file($serviceFile) ? json_decode(file_get_contents($serviceFile), true) : null;
        if (! is_array($regions) || ! is_array($services)) {
            $this->error('JSON wilayah atau layanan tidak valid.');

            return self::FAILURE;
        } $codes = [];
        foreach ($regions as $region) {
            $codes[] = $region['kode'];
            foreach ($region['kabupaten_kota'] ?? [] as $city) {
                $codes[] = $city['kode'];
            }
        } $slugs = [];
        $errors = [];
        foreach ($services as $index => $service) {
            $prefix = 'Entri '.($index + 1).': ';
            if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $service['slug'] ?? '')) {
                $errors[] = $prefix.'slug tidak valid';
            }if (in_array($service['slug'] ?? null, $slugs, true)) {
                $errors[] = $prefix.'slug duplikat';
            }$slugs[] = $service['slug'] ?? '';
            if (! in_array($service['wilayah_kode'] ?? '', $codes, true)) {
                $errors[] = $prefix.'kode wilayah tidak dikenal';
            }if (! ServiceType::tryFrom($service['jenis'] ?? '')) {
                $errors[] = $prefix.'jenis tidak dikenal';
            }$date = $service['terakhir_diverifikasi'] ?? null;
            $dateValid = $date === null || (is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4)));
            if (! $dateValid) {
                $errors[] = $prefix.'tanggal verifikasi harus ISO YYYY-MM-DD';
            }if (! empty($service['sumber_url']) && ! filter_var($service['sumber_url'], FILTER_VALIDATE_URL)) {
                $errors[] = $prefix.'URL sumber tidak valid';
            }foreach (['telepon', 'wa'] as $field) {
                $number = $service['kontak'][$field] ?? null;
                if ($number !== null && ! preg_match('/^(?:\+?62|0)[0-9 .()-]{7,18}$/', $number)) {
                    $errors[] = $prefix.'nomor '.$field.' bukan format nomor Indonesia';
                }
            }if (empty($service['is_sample']) && empty($date)) {
                $errors[] = $prefix.'entri nyata wajib memiliki tanggal verifikasi';
            }if (empty($service['is_sample']) && empty($service['sumber'])) {
                $errors[] = $prefix.'entri nyata wajib memiliki sumber';
            }if ($date && $dateValid && Freshness::status($date, config('peta.stale_after_days')) === 'perlu_verifikasi_ulang') {
                $this->warn($prefix.'melewati masa verifikasi, perlu diperbarui.');
            }
        }if ($errors) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }$this->info(count($services).' entri layanan valid.');

        return self::SUCCESS;
    }
}
