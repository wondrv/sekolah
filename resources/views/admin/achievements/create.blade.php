@extends('layouts.admin')

@section('title', 'Tambah Prestasi')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.achievements.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Prestasi</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Prestasi</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               required placeholder="Contoh: Juara 1 Olimpiade Matematika">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Siswa/Tim</label>
                        <input type="text" id="student_name" name="student_name" value="{{ old('student_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_name') border-red-500 @enderror"
                               required placeholder="Nama lengkap siswa atau nama tim">
                        @error('student_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select id="category" name="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>Akademik</option>
                                <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Olahraga</option>
                                <option value="arts" {{ old('category') == 'arts' ? 'selected' : '' }}>Seni</option>
                                <option value="technology" {{ old('category') == 'technology' ? 'selected' : '' }}>Teknologi</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Tingkat</label>
                            <select id="level" name="level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('level') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Tingkat</option>
                                <option value="school" {{ old('level') == 'school' ? 'selected' : '' }}>Sekolah</option>
                                <option value="district" {{ old('level') == 'district' ? 'selected' : '' }}>Kabupaten</option>
                                <option value="provincial" {{ old('level') == 'provincial' ? 'selected' : '' }}>Provinsi</option>
                                <option value="national" {{ old('level') == 'national' ? 'selected' : '' }}>Nasional</option>
                                <option value="international" {{ old('level') == 'international' ? 'selected' : '' }}>Internasional</option>
                            </select>
                            @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Deskripsi detail tentang prestasi ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Prestasi</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror">
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, maksimal 2MB</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="achievement_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Prestasi</label>
                            <input type="date" id="achievement_date" name="achievement_date" value="{{ old('achievement_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('achievement_date') border-red-500 @enderror">
                            @error('achievement_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror"
                                   required min="1950" max="{{ date('Y') + 5 }}" placeholder="{{ date('Y') }}">
                            @error('year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">Peringkat</label>
                            <input type="text" id="rank" name="rank" value="{{ old('rank') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rank') border-red-500 @enderror"
                                   placeholder="Contoh: Juara 1, Medali Emas">
                            @error('rank')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="organizer" class="block text-sm font-medium text-gray-700 mb-2">Penyelenggara</label>
                        <input type="text" id="organizer" name="organizer" value="{{ old('organizer') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('organizer') border-red-500 @enderror"
                               placeholder="Nama institusi/lembaga penyelenggara">
                        @error('organizer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="certificate_url" class="block text-sm font-medium text-gray-700 mb-2">Link Sertifikat/Piagam</label>
                        <input type="url" id="certificate_url" name="certificate_url" value="{{ old('certificate_url') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_url') border-red-500 @enderror"
                               placeholder="https://example.com/certificate.pdf">
                        @error('certificate_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Opsional: Link ke file sertifikat online</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">Tampilkan di halaman utama</label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.achievements.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
