<?php

namespace App\Http\Controllers;

use App\Services\RegionRepository;
use App\Services\ServiceRepository;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    private function data(string $file): array
    {
        return $file === 'layanan' ? app(ServiceRepository::class)->all() : app(RegionRepository::class)->all();
    }

    private function page(string $page, array $data = [])
    {
        return response()->view('pages.'.$page, $data);
    }

    public function home()
    {
        return $this->page('home');
    }

    public function search(Request $request)
    {
        $regions = $this->data('wilayah');
        $message = null;
        if ($request->query('provinsi')) {
            $province = collect($regions)->firstWhere('slug', $request->query('provinsi'));
            if ($province) {
                $city = collect($province['kabupaten_kota'] ?? [])->firstWhere('slug', $request->query('kabkota'));
                if (! $request->query('kabkota') || $city) {
                    return redirect('/cari/'.$province['slug'].($city ? '/'.$city['slug'] : ''));
                }
            } $message = 'Pilihan kabupaten/kota tidak sesuai dengan provinsi. Silakan pilih ulang wilayahnya.';
        }

        return $this->page('search', ['regions' => $regions, 'message' => $message]);
    }

    public function results(string $provinsi, ?string $kabkota = null)
    {
        $regions = $this->data('wilayah');
        $services = $this->data('layanan');
        $p = collect($regions)->firstWhere('slug', $provinsi);
        abort_unless($p, 404);
        $city = $kabkota ? collect($p['kabupaten_kota'] ?? [])->firstWhere('slug', $kabkota) : null;
        abort_if($kabkota && ! $city, 404);
        $rows = collect($services)->filter(fn ($s) => $s['wilayah_kode'] == ($city['kode'] ?? $p['kode']))->all();

        return $this->page('results', ['services' => $rows, 'provinsi' => $p, 'kota' => $city]);
    }

    public function detail(string $slug)
    {
        $service = app(ServiceRepository::class)->find($slug);
        abort_unless($service, 404);
        $back = '/cari';
        foreach ($this->data('wilayah') as $region) {
            foreach ($region['kabupaten_kota'] ?? [] as $city) {
                if ($city['kode'] === $service['wilayah_kode']) {
                    $back = '/cari/'.$region['slug'].'/'.$city['slug'];
                }
            }if ($region['kode'] === $service['wilayah_kode']) {
                $back = '/cari/'.$region['slug'];
            }
        }

        return $this->page('detail', ['service' => $service, 'back' => $back]);
    }

    public function rights()
    {
        return $this->page('rights');
    }

    public function safety()
    {
        return $this->page('safety');
    }

    public function about()
    {
        return $this->page('about');
    }

    public function robots()
    {
        return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml'), 200, ['Content-Type' => 'text/plain']);
    }

    public function sitemap()
    {
        return response()->view('pages.sitemap', ['services' => $this->data('layanan')], 200, ['Content-Type' => 'application/xml']);
    }
}
