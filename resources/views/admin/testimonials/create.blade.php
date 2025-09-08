@extends('layouts.admin')

@section('title', 'Tambah Testimoni')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.testimonials.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Testimoni</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required placeholder="Nama lengkap pemberi testimoni">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                            <select id="type" name="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Tipe</option>
                                <option value="student" {{ old('type') == 'student' ? 'selected' : '' }}>Siswa</option>
                                <option value="alumni" {{ old('type') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="parent" {{ old('type') == 'parent' ? 'selected' : '' }}>Orang Tua</option>
                                <option value="teacher" {{ old('type') == 'teacher' ? 'selected' : '' }}>Guru</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <select id="rating" name="rating"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rating') border-red-500 @enderror">
                                <option value="">Pilih Rating</option>
                                <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                                <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                                <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                                <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                                <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                            </select>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Posisi/Jabatan</label>
                        <input type="text" id="position" name="position" value="{{ old('position') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror"
                               placeholder="Contoh: Siswa Kelas XII IPA, Alumni 2020, Orang Tua Siswa">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Isi Testimoni</label>
                        <textarea id="content" name="content" rows="5"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                                  required placeholder="Tuliskan testimoni yang diberikan...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror">
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, maksimal 2MB (opsional)</p>
                    </div>

                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus</label>
                        <input type="number" id="graduation_year" name="graduation_year" value="{{ old('graduation_year') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('graduation_year') border-red-500 @enderror"
                               min="1950" max="{{ date('Y') + 10 }}" placeholder="Contoh: 2020">
                        @error('graduation_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Khusus untuk alumni (opsional)</p>
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Perusahaan/Institusi</label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company') border-red-500 @enderror"
                               placeholder="Nama perusahaan atau institusi tempat bekerja/belajar">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Opsional</p>
                    </div>

                    <div>
                        <label for="contact_info" class="block text-sm font-medium text-gray-700 mb-2">Kontak</label>
                        <input type="text" id="contact_info" name="contact_info" value="{{ old('contact_info') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_info') border-red-500 @enderror"
                               placeholder="Email, telepon, atau media sosial">
                        @error('contact_info')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Opsional, untuk verifikasi</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">Tampilkan di halaman utama</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_verified" name="is_verified" value="1"
                                   {{ old('is_verified') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_verified" class="ml-2 block text-sm text-gray-700">Testimoni terverifikasi</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.testimonials.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Testimoni
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
