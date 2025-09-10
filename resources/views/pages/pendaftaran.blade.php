@extends('layouts.app')
@section('title', 'Pendaftaran Siswa Baru')
@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Pendaftaran Siswa Baru</h1>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex">
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('enrollment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Student Information -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b pb-2">Data Siswa</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Siswa *</label>
                            <input type="text" name="student_name" value="{{ old('student_name') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_name') border-red-500 @enderror"
                                   required>
                            @error('student_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror"
                                   required>
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                            <select name="gender"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea name="address" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b pb-2">Informasi Akademik</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program yang Dipilih *</label>
                            <select name="program"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('program') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Program</option>
                                @if(isset($programs) && $programs->count() > 0)
                                    @foreach($programs as $program)
                                        <option value="{{ $program->title }}" {{ old('program') === $program->title ? 'selected' : '' }}>
                                            {{ $program->title }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="TK">Taman Kanak-kanak (TK)</option>
                                    <option value="SD">Sekolah Dasar (SD)</option>
                                    <option value="SMP">Sekolah Menengah Pertama (SMP)</option>
                                    <option value="SMA">Sekolah Menengah Atas (SMA)</option>
                                @endif
                            </select>
                            @error('program')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat/Kelas yang Dituju *</label>
                            <input type="text" name="grade_level" value="{{ old('grade_level') }}"
                                   placeholder="Contoh: Kelas 1, Kelas 7, Kelas 10"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('grade_level') border-red-500 @enderror"
                                   required>
                            @error('grade_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah Asal (Opsional)</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('previous_school') border-red-500 @enderror">
                            @error('previous_school')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b pb-2">Data Orang Tua/Wali</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua/Wali</label>
                            <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_name') border-red-500 @enderror">
                            @error('parent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b pb-2">Informasi Tambahan</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan/Keterangan Tambahan</label>
                            <textarea name="additional_info" rows="4"
                                      placeholder="Tuliskan informasi tambahan yang perlu kami ketahui (minat khusus, kebutuhan khusus, dll.)"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('additional_info') border-red-500 @enderror">{{ old('additional_info') }}</textarea>
                            @error('additional_info')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Dokumen (Opsional)</label>
                            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('documents.*') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">
                                Anda dapat mengupload beberapa file sekaligus. Format yang didukung: PDF, JPG, PNG (maksimal 2MB per file)
                            </p>
                            @error('documents.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center pt-6">
                    <button type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium text-lg transition-colors">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Informasi Penting</h3>
            <ul class="text-blue-800 space-y-2 text-sm">
                <li>• Pastikan semua data yang diisi sudah benar dan lengkap</li>
                <li>• Tim kami akan menghubungi Anda dalam 1-2 hari kerja setelah pendaftaran diterima</li>
                <li>• Siapkan dokumen-dokumen yang diperlukan untuk proses selanjutnya</li>
                <li>• Untuk pertanyaan lebih lanjut, silakan hubungi kami melalui halaman kontak</li>
            </ul>
        </div>
    </div>
</div>
@endsection
