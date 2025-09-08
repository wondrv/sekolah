@extends('layouts.admin')

@section('title', 'Tambah Halaman')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Halaman</h2>
        <a href="{{ route('admin.pages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.pages.store') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Halaman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul halaman" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug URL
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                           placeholder="otomatis-dari-judul">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan untuk generate otomatis dari judul</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body Content -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Halaman <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body" name="body" rows="15"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @enderror"
                              placeholder="Tulis konten halaman di sini..." required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing Actions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi</h3>

                    <!-- Pin Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Pin halaman ini</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Halaman yang di-pin akan ditampilkan di menu utama</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button type="submit" name="action" value="save"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            Simpan
                        </button>
                        <button type="submit" name="action" value="save_and_continue"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                <!-- Page Info -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">Tips Halaman</h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Gunakan halaman untuk konten statis seperti "Tentang Kami", "Visi Misi", dll.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Halaman yang di-pin akan muncul di menu navigasi utama.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Slug URL akan digunakan untuk mengakses halaman (contoh: /pages/tentang-kami).
                        </li>
                    </ul>
                </div>

                <!-- Common Pages Templates -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Template Halaman</h3>
                    <div class="space-y-2">
                        <button type="button" onclick="loadTemplate('tentang')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Tentang Kami
                        </button>
                        <button type="button" onclick="loadTemplate('visi-misi')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Visi & Misi
                        </button>
                        <button type="button" onclick="loadTemplate('fasilitas')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Fasilitas
                        </button>
                        <button type="button" onclick="loadTemplate('kontak')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Kontak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function(e) {
        const title = e.target.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('slug').value = slug;
    });

    // Load template content
    function loadTemplate(template) {
        const templates = {
            'tentang': {
                title: 'Tentang Kami',
                slug: 'tentang-kami',
                body: `# Tentang Kami

Selamat datang di [Nama Sekolah], sebuah institusi pendidikan yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada generasi muda Indonesia.

## Sejarah Singkat

[Nama Sekolah] didirikan pada tahun [tahun] dengan visi menjadi lembaga pendidikan yang unggul dalam membentuk karakter dan prestasi siswa.

## Komitmen Kami

- Memberikan pendidikan yang berkualitas
- Mengembangkan karakter dan moral siswa
- Mempersiapkan siswa untuk masa depan yang cerah
- Menciptakan lingkungan belajar yang nyaman dan kondusif

## Tim Pengajar

Kami memiliki tim pengajar yang berpengalaman dan berdedikasi tinggi dalam dunia pendidikan.`
            },
            'visi-misi': {
                title: 'Visi & Misi',
                slug: 'visi-misi',
                body: `# Visi & Misi

## Visi

Menjadi lembaga pendidikan yang unggul dalam membentuk generasi yang beriman, bertakwa, berakhlak mulia, dan berprestasi.

## Misi

1. Menyelenggarakan pendidikan yang berkualitas dan berkarakter
2. Mengembangkan potensi siswa secara optimal
3. Menciptakan lingkungan belajar yang kondusif dan inovatif
4. Membangun kerjasama yang baik dengan orang tua dan masyarakat
5. Menghasilkan lulusan yang kompeten dan siap menghadapi tantangan masa depan

## Tujuan

- Meningkatkan kualitas pendidikan dan pembelajaran
- Mengembangkan karakter dan kepribadian siswa
- Mempersiapkan siswa untuk jenjang pendidikan selanjutnya
- Menciptakan budaya sekolah yang positif`
            },
            'fasilitas': {
                title: 'Fasilitas',
                slug: 'fasilitas',
                body: `# Fasilitas Sekolah

Kami menyediakan berbagai fasilitas yang mendukung proses pembelajaran dan pengembangan siswa.

## Fasilitas Akademik

- Ruang kelas yang nyaman dan ber-AC
- Laboratorium komputer
- Laboratorium sains
- Perpustakaan dengan koleksi buku lengkap
- Ruang multimedia

## Fasilitas Olahraga

- Lapangan olahraga
- Ruang senam
- Lapangan futsal
- Lapangan basket

## Fasilitas Pendukung

- Kantin sekolah
- Mushola
- Parkir yang luas
- Ruang kesehatan (UKS)
- Taman sekolah

## Fasilitas Keamanan

- CCTV di seluruh area sekolah
- Satpam 24 jam
- Sistem keamanan terpadu`
            },
            'kontak': {
                title: 'Kontak',
                slug: 'kontak',
                body: `# Hubungi Kami

Untuk informasi lebih lanjut atau pertanyaan, silakan hubungi kami melalui:

## Alamat

[Alamat Lengkap Sekolah]
[Kota, Kode Pos]

## Kontak

**Telepon:** [Nomor Telepon]
**WhatsApp:** [Nomor WhatsApp]
**Email:** [Email Sekolah]
**Website:** [Website Sekolah]

## Jam Operasional

**Senin - Jumat:** 07.00 - 16.00 WIB
**Sabtu:** 07.00 - 12.00 WIB
**Minggu:** Tutup

## Peta Lokasi

[Embed Google Maps atau deskripsi lokasi]

Kami siap membantu Anda dan menjawab pertanyaan seputar pendidikan di sekolah kami.`
            }
        };

        if (templates[template]) {
            document.getElementById('title').value = templates[template].title;
            document.getElementById('slug').value = templates[template].slug;
            document.getElementById('body').value = templates[template].body;

            // Check pin for important pages
            if (['tentang', 'visi-misi', 'kontak'].includes(template)) {
                document.querySelector('input[name="is_pinned"]').checked = true;
            }
        }
    }
</script>
@endsection
