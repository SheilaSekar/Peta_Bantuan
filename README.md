# Peta Bantuan

Direktori informasi layanan bantuan untuk perempuan dan anak. Situs independen, bukan situs pemerintah. Tidak ada akun, formulir pengumpulan data, cookie pelacak, analitik, maupun geolokasi. Kontak contoh tidak boleh diperlakukan sebagai kontak nyata.

## Menjalankan

Memerlukan PHP 8.2+, Composer, serta ekstensi PHP Laravel, termasuk `fileinfo` dan `openssl`.

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Tidak ada database yang dipakai aplikasi. Aset CSS dan JavaScript langsung dilayani dari `public/`; tidak ada proses build front-end. Jalankan tes dengan `php artisan test` dan format dengan `./vendor/bin/pint` (Windows: `vendor/bin/pint`).

Regresi JavaScript untuk filter wilayah dapat dijalankan dengan `node --test tests/js/region-filter.test.cjs` (Node.js hanya diperlukan untuk tes ini, bukan untuk menjalankan situs).

Pada Windows, bila Composer melaporkan `ext-fileinfo` tidak tersedia, aktifkan ekstensi `fileinfo` pada `php.ini` CLI yang dipakai Composer, lalu buka terminal baru dan ulangi pemasangan.

## Data layanan

Ubah `resources/data/layanan.json` dan `wilayah.json`. Semua data nyata harus dikonfirmasi ke lembaga, dilengkapi sumber dan tanggal, serta ditinjau berkala. Validasi dengan `php artisan peta:validasi-data`. Template CSV tersedia di `resources/data/templates/layanan.csv`; impor CSV belum tersedia. Kode wilayah pilot belum diverifikasi; lihat `docs/KEPUTUSAN.md`.

Aplikasi tidak mencatat IP, query, atau user-agent di level aplikasi. Contoh nginx yang menulis hanya metode, path tanpa query, dan status ada di `docs/nginx-privacy.example.conf`; sesuaikan retensi log dengan kebijakan operasional. Aset CSS/JS memakai nomor versi query untuk cache panjang; naikkan versinya setiap kali konten aset berubah. Atur `Cache-Control: public, max-age=31536000, immutable` untuk aset tersebut; HTML tetap `private, no-cache` dari middleware aplikasi.

## Sebelum rilis

- Verifikasi semua nomor hotline dan data layanan.
- Minta pendamping meninjau konten hukum dan uji dengan penyintas/pendamping bila memungkinkan.
- Konfigurasikan HTTPS dan server agar tidak mencatat IP/query atau menerapkan retensi sangat singkat.
- Ganti `contact_email` dan `quick_exit_url` sesuai kebutuhan.
- Uji keyboard, pembaca layar, zoom 200%, lebar 360px, tanpa JavaScript, dan Shift tiga kali.
- Pastikan tidak ada cookie di seluruh respons, dan CSP, HSTS, serta `Permissions-Policy` sesuai lingkungan.

Keluar cepat hanya berpindah halaman; fitur ini tidak menghapus riwayat browser dan tidak melindungi perangkat yang dipantau.
