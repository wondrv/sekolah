# Harapan Theme — Laravel School Template (Gold–Navy)

**Framework**: Laravel 11.x  
**UI**: TailwindCSS via CDN (tanpa build)  
**Namespace Controller**: `App\Http\Controllers\Harapan`

## Struktur
- `resources/views/` (semua Blade siap pakai)
- `app/Http/Controllers/Harapan/SchoolController.php`
- `routes/web.php`
- `public/images/` (dummy images)

## Instalasi Cepat
1. Salin isi folder ini ke root project Laravel-mu (akan menimpa folder `app/`, `resources/`, `routes/`, `public/` jika ada).
2. Jalankan server dev:
   ```bash
   php artisan serve
   ```
3. Buka:
   - `/` (Home)
   - `/tentang`
   - `/program`
   - `/berita`
   - `/galeri`
   - `/ppdb`
   - `/kontak`

> Catatan: Karena Tailwind via CDN, kamu tidak perlu `npm run dev` untuk memulai. Jika ingin tema kustom lebih lanjut, silakan pindah ke Tailwind CLI atau Vite sesuai kebutuhan.

## Kustomisasi Cepat
- Ubah judul/branding di `resources/views/layouts/main.blade.php`
- Ganti gambar di `public/images/` (news1.jpg, galeri1.jpg, galeri2.jpg)
- Modifikasi tabel biaya PPDB di `resources/views/ppdb.blade.php`

Selamat menggunakan 🎓
