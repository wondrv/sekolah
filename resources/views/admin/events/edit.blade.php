@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Edit Event</h2>
        <div class="flex space-x-2">
            <a href="{{ route('events.show', $event->id) }}" target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Lihat Event
            </a>
            <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.events.update', $event) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
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
                              placeholder="Deskripsi lengkap tentang event ini...">{{ old('description', $event->description) }}</textarea>
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
                        <input type="datetime-local" id="starts_at" name="starts_at"
                               value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}"
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
                        <input type="datetime-local" id="ends_at" name="ends_at"
                               value="{{ old('ends_at', $event->ends_at ? $event->ends_at->format('Y-m-d\TH:i') : '') }}"
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
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
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
                            <option value="general" {{ old('type', $event->type) == 'general' ? 'selected' : '' }}>General</option>
                            <option value="academic" {{ old('type', $event->type) == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="extracurricular" {{ old('type', $event->type) == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                        </select>
                    </div>

                    <!-- Featured Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Event unggulan</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Event unggulan akan ditampilkan di beranda</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button type="submit" name="action" value="save"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            Update
                        </button>
                        <button type="submit" name="action" value="save_and_continue"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            Update & Lanjut
                        </button>
                    </div>

                    <!-- Event Info -->
                    <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500">
                        <p>Dibuat: {{ $event->created_at->format('d/m/Y H:i') }}</p>
                        <p>Diupdate: {{ $event->updated_at->format('d/m/Y H:i') }}</p>
                        <p>Status:
                            @if($event->isUpcoming())
                                <span class="text-blue-600 font-medium">Akan Datang</span>
                            @elseif($event->isOngoing())
                                <span class="text-green-600 font-medium">Berlangsung</span>
                            @else
                                <span class="text-gray-600">Sudah Lewat</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Event Status -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">Status Event</h3>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div class="flex justify-between">
                            <span>Mulai:</span>
                            <span>{{ $event->starts_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($event->ends_at)
                        <div class="flex justify-between">
                            <span>Selesai:</span>
                            <span>{{ $event->ends_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Durasi:</span>
                            <span>{{ $event->duration }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span>
                                @if($event->isUpcoming())
                                    <span class="text-blue-600 font-medium">Akan Datang</span>
                                @elseif($event->isOngoing())
                                    <span class="text-green-600 font-medium">Berlangsung</span>
                                @else
                                    <span class="text-gray-600">Sudah Lewat</span>
                                @endif
                            </span>
                        </div>
                        @if($event->is_featured)
                        <div class="pt-2 border-t border-blue-200">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                Event Unggulan
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h3 class="text-lg font-medium text-red-900 mb-4">Zona Berbahaya</h3>
                    <button type="button" onclick="confirmDelete()"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        Hapus Event
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Batal
                </button>
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Update end date minimum when start date changes
    document.getElementById('starts_at').addEventListener('change', function() {
        document.getElementById('ends_at').min = this.value;
    });

    // Set initial minimum for end date
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('starts_at').value;
        if (startDate) {
            document.getElementById('ends_at').min = startDate;
        }
    });
</script>
@endsection
