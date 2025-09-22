@php
    use App\Models\PpdbSetting;
    
    // Block data and settings
    $blockId = $blockId ?? 'ppdb-brochure-' . uniqid();
    $settings = $settings ?? [];
    
    // PPDB Settings from database (can be overridden by block settings)
    $brochure_enabled = $settings['enabled'] ?? PpdbSetting::get('brochure_enabled', true);
    $brochure_title = $settings['title'] ?? PpdbSetting::get('brochure_title', 'Download Brosur PPDB');
    $brochure_description = $settings['description'] ?? PpdbSetting::get('brochure_description', 'Unduh brosur resmi Penerimaan Peserta Didik Baru untuk informasi lengkap.');
    $brochure_file = $settings['file'] ?? PpdbSetting::get('brochure_file');
    $brochure_size = $settings['size'] ?? PpdbSetting::get('brochure_size', '2.5 MB');
    $brochure_format = $settings['format'] ?? PpdbSetting::get('brochure_format', 'PDF');
@endphp

@if($brochure_enabled && $brochure_file)
<section id="{{ $blockId }}" class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $brochure_title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ $brochure_description }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center mb-6 md:mb-0">
                    <div class="bg-red-100 p-4 rounded-full mr-4">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">
                            Brosur PPDB {{ date('Y') }}
                        </h3>
                        <p class="text-gray-500 text-sm">
                            Format: {{ $brochure_format }} • Ukuran: {{ $brochure_size }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ asset('storage/' . $brochure_file) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Brosur
                    </a>
                    
                    <a href="{{ asset('storage/' . $brochure_file) }}" 
                       download
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Brosur ini berisi informasi lengkap tentang prosedur pendaftaran, persyaratan, dan jadwal kegiatan PPDB.
                </div>
            </div>
        </div>
    </div>
</section>
@endif
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $brochure_title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ $brochure_description }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center mb-6 md:mb-0">
                    <div class="bg-red-100 p-4 rounded-full mr-4">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">
                            Brosur PPDB {{ date('Y') }}
                        </h3>
                        <p class="text-gray-500 text-sm">
                            Format: {{ $brochure_format }} • Ukuran: {{ $brochure_size }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ asset('storage/' . $brochure_file) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Brosur
                    </a>
                    
                    <a href="{{ asset('storage/' . $brochure_file) }}" 
                       download
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Brosur ini berisi informasi lengkap tentang prosedur pendaftaran, persyaratan, dan jadwal kegiatan PPDB.
                </div>
            </div>
        </div>
    </div>
</section>
@endif