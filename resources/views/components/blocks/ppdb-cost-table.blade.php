@php
    use App\Models\PpdbSetting;
    use App\Models\PpdbCost;
    
    // Block data and settings
    $blockId = $blockId ?? 'ppdb-cost-table-' . uniqid();
    $settings = $settings ?? [];
    
    // PPDB Settings from database (can be overridden by block settings)
    $costs_enabled = $settings['enabled'] ?? PpdbSetting::get('costs_enabled', true);
    $costs_title = $settings['title'] ?? PpdbSetting::get('costs_title', 'Rincian Biaya PPDB');
    $costs_description = $settings['description'] ?? PpdbSetting::get('costs_description', 'Berikut adalah rincian biaya untuk Penerimaan Peserta Didik Baru.');
    $academic_year = $settings['academic_year'] ?? PpdbCost::getCurrentAcademicYear();
    $show_total = $settings['show_total'] ?? true;
    
    $costsByCategory = [];
    if ($costs_enabled) {
        $costsByCategory = PpdbCost::getCostsByCategory($academic_year);
    }
@endphp

@if($costs_enabled && !empty($costsByCategory))
<section id="{{ $blockId }}" class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $costs_title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-2">
                {{ $costs_description }}
            </p>
            <p class="text-sm text-gray-500">
                Tahun Akademik: {{ $academic_year }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            @foreach($costsByCategory as $category => $costs)
                <div class="border-b border-gray-200 last:border-b-0">
                    <div class="bg-gray-100 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 capitalize">
                            {{ str_replace('_', ' ', $category) }}
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Biaya
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($costs as $cost)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $cost['item_name'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">
                                            {{ $cost['description'] ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($cost['amount'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($cost['is_mandatory'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Opsional
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @if($show_total)
                @php
                    $totalMandatory = PpdbCost::getTotalMandatoryCosts($academic_year);
                @endphp
                
                @if($totalMandatory > 0)
                <div class="bg-blue-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">
                                Total Biaya Wajib
                            </h4>
                            <p class="text-sm text-gray-600">
                                Biaya yang harus dibayar untuk pendaftaran
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($totalMandatory, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <div class="mt-8 text-center">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <h4 class="text-sm font-medium text-yellow-800">Informasi Penting</h4>
                </div>
                <p class="text-sm text-yellow-700">
                    Biaya dapat berubah sewaktu-waktu. Untuk informasi terkini, silakan hubungi pihak sekolah secara langsung.
                </p>
            </div>
        </div>
    </div>
</section>
@endif
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $costs_title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-2">
                {{ $costs_description }}
            </p>
            <p class="text-sm text-gray-500">
                Tahun Akademik: {{ $academic_year }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            @foreach($costsByCategory as $category => $costs)
                <div class="border-b border-gray-200 last:border-b-0">
                    <div class="bg-gray-100 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 capitalize">
                            {{ str_replace('_', ' ', $category) }}
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Biaya
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($costs as $cost)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $cost['item_name'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">
                                            {{ $cost['description'] ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($cost['amount'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($cost['is_mandatory'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Opsional
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @php
                $totalMandatory = PpdbCost::getTotalMandatoryCosts($academic_year);
            @endphp
            
            @if($totalMandatory > 0)
            <div class="bg-blue-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">
                            Total Biaya Wajib
                        </h4>
                        <p class="text-sm text-gray-600">
                            Biaya yang harus dibayar untuk pendaftaran
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($totalMandatory, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-8 text-center">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <h4 class="text-sm font-medium text-yellow-800">Informasi Penting</h4>
                </div>
                <p class="text-sm text-yellow-700">
                    Biaya dapat berubah sewaktu-waktu. Untuk informasi terkini, silakan hubungi pihak sekolah secara langsung.
                </p>
            </div>
        </div>
    </div>
</section>
@endif