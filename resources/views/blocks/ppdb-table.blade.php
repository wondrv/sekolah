@php
    $students = \App\Models\Student::approved()
        ->orderBy('final_score', 'desc')
        ->take($settings['limit'] ?? 50)
        ->get();
        
    $title = $settings['title'] ?? 'Daftar Siswa Diterima PPDB';
    $showScore = $settings['show_score'] ?? true;
    $showRegistrationNumber = $settings['show_registration_number'] ?? true;
@endphp

<div class="py-12 bg-white" id="{{ $blockId }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
            @if(isset($settings['description']) && $settings['description'])
                <p class="mt-4 text-lg text-gray-600">{{ $settings['description'] }}</p>
            @endif
        </div>

        @if($students->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                @if($showRegistrationNumber)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Pendaftaran
                                    </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Lengkap
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Asal Sekolah
                                </th>
                                @if($showScore)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Akhir
                                    </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $index => $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    @if($showRegistrationNumber)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            {{ $student->registration_number }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        @if($student->nickname)
                                            <div class="text-sm text-gray-500">({{ $student->nickname }})</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $student->previous_school ?? '-' }}
                                    </td>
                                    @if($showScore)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($student->final_score)
                                                <span class="font-semibold">{{ number_format($student->final_score, 2) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($student->status === 'approved') bg-green-100 text-green-800
                                            @elseif($student->status === 'enrolled') bg-blue-100 text-blue-800
                                            @elseif($student->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $student->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if(isset($settings['show_download']) && $settings['show_download'])
                <div class="mt-6 text-center">
                    <a href="{{ route('ppdb.download-list') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Download Daftar Lengkap
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Siswa Diterima</h3>
                <p class="text-gray-500">Daftar siswa yang diterima akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</div>