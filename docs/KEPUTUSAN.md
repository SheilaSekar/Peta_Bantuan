# Keputusan dan asumsi

- Data layanan bawaan seluruhnya contoh; tidak ada kontak atau alamat nyata yang dikarang.
- Kabupaten/kota yang tersedia hanya pilot DKI Jakarta dan Kota Contoh di Jawa Barat. Kode wilayah, khususnya kode provinsi dan `32.xx`, wajib diverifikasi terhadap sumber resmi Kemendagri sebelum rilis.
- Hotline ditampilkan sebagai kanal konfigurasi dan wajib diverifikasi ulang sebelum rilis; tanggal verifikasi awal belum tersedia.
- Konten hukum merupakan informasi umum dan wajib ditinjau pendamping sebelum rilis.
- Tombol Keluar cepat adalah pengalihan, bukan penghapus riwayat maupun perlindungan perangkat terpantau.
- Server perlu disetel untuk tidak mencatat IP dan query, atau menggunakan retensi sangat singkat.
- Repository layanan dan wilayah memakai cache Laravel; hash isi berkas menjadi bagian dari kunci cache agar perubahan JSON otomatis menghasilkan entri cache baru.
- Uji pengguna, verifikasi kontak, HTTPS, alamat email koreksi, dan lisensi belum ditentukan dan perlu diisi pengelola.
