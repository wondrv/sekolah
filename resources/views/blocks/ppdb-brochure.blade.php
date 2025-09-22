@php
    $brochures = collect();
    if (isset($settings['brochure_files']) && is_array($settings['brochure_files'])) {
        $brochures = collect($settings['brochure_files']);
    }
    
    $title = $settings['title'] ?? 'Download Brosur PPDB';
    $description = $settings['description'] ?? 'Unduh brosur dan informasi lengkap tentang Penerimaan Peserta Didik Baru.';
@endphp

<div class="py-12 bg-blue-50" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
            @if($description)
                <p class="mt-4 text-lg text-gray-600">{{ $description }}</p>
            @endif
        </div>

        @if($brochures->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($brochures as $brochure)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @if(isset($brochure['image']) && $brochure['image'])
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 relative overflow-hidden">
                                <img src="{{ $brochure['image'] }}" 
                                     alt="{{ $brochure['title'] ?? 'Brosur' }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-blue-600 bg-opacity-20"></div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-file-pdf text-6xl text-white opacity-50"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $brochure['title'] ?? 'Brosur PPDB' }}
                            </h3>
                            
                            @if(isset($brochure['description']) && $brochure['description'])
                                <p class="text-gray-600 text-sm mb-4">{{ $brochure['description'] }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                @if(isset($brochure['file_size']) && $brochure['file_size'])
                                    <span class="text-xs text-gray-500">{{ $brochure['file_size'] }}</span>
                                @endif
                                
                                @if(isset($brochure['file_url']) && $brochure['file_url'])
                                    <a href="{{ $brochure['file_url'] }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Default brochure if none configured -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-file-pdf text-6xl text-white opacity-50"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Brosur PPDB {{ date('Y') }}</h3>
                        <p class="text-gray-600 text-sm mb-4">Informasi lengkap tentang pendaftaran peserta didik baru tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }}.</p>
                        <div class="text-center">
                            <span class="text-gray-400 text-sm">Brosur akan tersedia segera</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-file-alt text-6xl text-white opacity-50"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Panduan Pendaftaran</h3>
                        <p class="text-gray-600 text-sm mb-4">Panduan lengkap proses pendaftaran dan persyaratan yang harus dipenuhi.</p>
                        <div class="text-center">
                            <span class="text-gray-400 text-sm">Panduan akan tersedia segera</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Info Section -->
        @if(isset($settings['show_quick_info']) && $settings['show_quick_info'])
            <div class="mt-12 bg-white rounded-lg shadow-md p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="text-blue-600 text-3xl mb-2">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pendaftaran Dibuka</h4>
                        <p class="text-gray-600">{{ $settings['registration_start'] ?? 'Segera diumumkan' }}</p>
                    </div>
                    
                    <div>
                        <div class="text-green-600 text-3xl mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Kuota Tersedia</h4>
                        <p class="text-gray-600">{{ $settings['quota'] ?? '100' }} siswa</p>
                    </div>
                    
                    <div>
                        <div class="text-purple-600 text-3xl mb-2">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Kontak Info</h4>
                        <p class="text-gray-600">{{ $settings['contact_phone'] ?? setting('school_phone', '(021) 123-4567') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>