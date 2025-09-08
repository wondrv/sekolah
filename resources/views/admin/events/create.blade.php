@extends('layouts.admin')

@section('title', 'Tambah Event')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Event</h2>
        <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.events.store') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul event" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Event
                    </label>
                    <textarea id="description" name="description" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Deskripsi lengkap tentang event ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('starts_at') border-red-500 @enderror"
                               required>
                        @error('starts_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal & Waktu Selesai
                        </label>
                        <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ends_at') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada waktu selesai spesifik</p>
                        @error('ends_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                           placeholder="Contoh: Aula Sekolah, Lapangan, Ruang Kelas 3A">
                    @error('location')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing Actions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi</h3>

                    <!-- Event Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Event</label>
                        <select id="type" name="type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="academic" {{ old('type') == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="extracurricular" {{ old('type') == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                        </select>
                    </div>

                    <!-- Featured Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Event unggulan</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Event unggulan akan ditampilkan di beranda</p>
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

                <!-- Event Types Info -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">Jenis Event</h3>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div>
                            <strong>General:</strong> Event umum sekolah seperti upacara, rapat, dll.
                        </div>
                        <div>
                            <strong>Academic:</strong> Kegiatan akademik seperti ujian, seminar, workshop.
                        </div>
                        <div>
                            <strong>Ekstrakurikuler:</strong> Kegiatan ekstrakurikuler, olahraga, seni.
                        </div>
                    </div>
                </div>

                <!-- Quick Templates -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Template Event</h3>
                    <div class="space-y-2">
                        <button type="button" onclick="loadTemplate('upacara')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Upacara Bendera
                        </button>
                        <button type="button" onclick="loadTemplate('ujian')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Ujian/Ulangan
                        </button>
                        <button type="button" onclick="loadTemplate('seminar')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Seminar/Workshop
                        </button>
                        <button type="button" onclick="loadTemplate('olahraga')"
                                class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Kegiatan Olahraga
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Load template content
    function loadTemplate(template) {
        const templates = {
            'upacara': {
                title: 'Upacara Bendera',
                description: 'Upacara bendera rutin setiap hari Senin untuk seluruh siswa dan guru.',
                type: 'general',
                location: 'Lapangan Sekolah',
                is_featured: false
            },
            'ujian': {
                title: 'Ujian Tengah Semester',
                description: 'Ujian tengah semester untuk semua mata pelajaran. Siswa diharapkan datang tepat waktu dan membawa perlengkapan ujian.',
                type: 'academic',
                location: 'Ruang Kelas',
                is_featured: true
            },
            'seminar': {
                title: 'Seminar Pendidikan Karakter',
                description: 'Seminar mengenai pentingnya pendidikan karakter dalam pembentukan pribadi siswa.',
                type: 'academic',
                location: 'Aula Sekolah',
                is_featured: false
            },
            'olahraga': {
                title: 'Pertandingan Futsal Antar Kelas',
                description: 'Turnamen futsal antar kelas untuk mempererat tali silaturahmi dan sportivitas siswa.',
                type: 'extracurricular',
                location: 'Lapangan Futsal',
                is_featured: false
            }
        };

        if (templates[template]) {
            const tmpl = templates[template];
            document.getElementById('title').value = tmpl.title;
            document.getElementById('description').value = tmpl.description;
            document.getElementById('type').value = tmpl.type;
            document.getElementById('location').value = tmpl.location;
            document.querySelector('input[name="is_featured"]').checked = tmpl.is_featured;
        }
    }

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.getElementById('starts_at').min = currentDateTime;

        // Update end date minimum when start date changes
        document.getElementById('starts_at').addEventListener('change', function() {
            document.getElementById('ends_at').min = this.value;
        });
    });
</script>
@endsection
