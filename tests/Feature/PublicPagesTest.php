<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_public_pages_have_no_cookies_and_security_headers(): void
    {
        foreach (['/', '/cari', '/hak-dan-alur', '/keamanan-digital', '/tentang', '/cari/jawa-barat/kota-contoh', '/layanan/uptd-ppa-kota-contoh', '/robots.txt', '/sitemap.xml'] as $uri) {
            $response = $this->get($uri);
            $response->assertSuccessful()->assertHeaderMissing('Set-Cookie')->assertHeader('Referrer-Policy', 'no-referrer')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Permissions-Policy', 'geolocation=(), camera=(), microphone=(), payment=()');
            $this->assertStringNotContainsString('unsafe-inline', $response->headers->get('Content-Security-Policy'));
            $this->assertStringContainsString('default-src \'none\'', $response->headers->get('Content-Security-Policy'));
            if (! in_array($uri, ['/robots.txt', '/sitemap.xml'], true)) {
                $this->assertStringContainsString('Keluar cepat', $response->getContent());
            }
        }
    }

    public function test_unknown_service_is_a_neutral_404_with_exit_link(): void
    {
        $this->get('/layanan/tidak-ada')->assertNotFound()->assertHeaderMissing('Set-Cookie')->assertSee('Keluar cepat')->assertSee('Halaman tidak ditemukan');
    }

    public function test_temporary_error_pages_keep_safety_navigation(): void
    {
        config(['app.debug' => false]);
        Route::middleware('web')->get('/__test/error/{status}', fn ($status) => abort((int) $status));

        foreach ([500, 503] as $status) {
            $this->get('/__test/error/'.$status)
                ->assertStatus($status)
                ->assertHeaderMissing('Set-Cookie')
                ->assertSee('Keluar cepat')
                ->assertSee('SAPA 129');
        }
    }

    public function test_production_responses_set_transport_and_html_cache_headers(): void
    {
        config(['app.env' => 'production']);
        $this->get('/')
            ->assertOk()
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->assertHeader('Cache-Control', 'no-cache, private');
    }

    public function test_search_redirects_valid_region_and_reports_mismatched_city(): void
    {
        $this->get('/cari?provinsi=jawa-barat&kabkota=kota-contoh')->assertRedirect('/cari/jawa-barat/kota-contoh');
        $this->get('/cari?provinsi=dki-jakarta&kabkota=kota-contoh')->assertOk()->assertSee('tidak sesuai dengan provinsi');
        $this->get('/cari/jawa-barat')->assertOk()->assertSee('SAPA 129');
    }

    public function test_city_select_lists_non_javascript_fallback_options_in_province_groups(): void
    {
        $this->get('/cari')
            ->assertOk()
            ->assertSee('<optgroup label="DKI Jakarta" data-provinsi="dki-jakarta">', false)
            ->assertSee('<optgroup label="Jawa Barat" data-provinsi="jawa-barat">', false)
            ->assertSee('Kota Jakarta Selatan')
            ->assertSee('Kota Contoh');
    }

    public function test_sample_services_have_no_active_phone_or_whatsapp_links(): void
    {
        $this->get('/layanan/uptd-ppa-kota-contoh')->assertOk()->assertSee('CONTOH DATA')->assertSee('[NOMOR TELEPON]')->assertSee('[NOMOR WHATSAPP]')->assertDontSee('href="tel:');
    }

    public function test_national_hotline_contacts_have_actionable_links(): void
    {
        $this->get('/cari/jawa-barat/kota-contoh')
            ->assertOk()
            ->assertSee('href="https://wa.me/628111129129"', false)
            ->assertSee('href="https://laporsapa129.kemenpppa.go.id"', false)
            ->assertSee('href="https://wa.me/6281110027727"', false)
            ->assertSee('href="tel:1500771"', false);
    }

    public function test_public_html_does_not_load_assets_from_other_origins(): void
    {
        foreach (['/', '/cari', '/hak-dan-alur', '/keamanan-digital', '/tentang'] as $uri) {
            $html = $this->get($uri)->assertOk()->getContent();
            preg_match_all('/<(?:script|link)\b[^>]*(?:src|href)="([^"#]+)"/i', $html, $matches);
            foreach ($matches[1] as $url) {
                $this->assertStringStartsWith('/', $url, 'A loaded asset must be served from this site: '.$url);
            }
        }
    }

    public function test_robots_and_sitemap_are_available(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Allow: /');
        $this->get('/sitemap.xml')->assertOk()->assertSee('<urlset', false);
    }
}
