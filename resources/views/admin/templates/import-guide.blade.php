@extends('layouts.admin')

@section('title', 'Template Import Guide')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Template Import Guide</h1>
                        <p class="mt-2 text-gray-600">Panduan lengkap untuk mengimpor template dengan benar</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.templates.smart-import.index') }}" class="btn btn-primary">
                            <i class="fas fa-upload mr-2"></i>Start Importing
                        </a>
                        <a href="{{ route('admin.templates.my-templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Fix Section -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <h2 class="ml-3 text-xl font-bold text-red-900">Mengalami Error "<!DOCTYPE" atau "Unexpected token '<'"?</h2>
            </div>

            <div class="space-y-4 text-red-800">
                <p class="font-semibold">Ini terjadi karena sistem menerima file HTML, bukan JSON. Berikut solusinya:</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded border border-red-200">
                        <h3 class="font-semibold text-red-900 mb-2">❌ Yang SALAH:</h3>
                        <ul class="space-y-1 text-sm">
                            <li>• Link ke halaman GitHub repository (https://github.com/user/repo)</li>
                            <li>• File ZIP yang berisi HTML, bukan JSON</li>
                            <li>• URL website biasa (yang mengembalikan HTML)</li>
                            <li>• File JSON yang sebenarnya berisi HTML</li>
                        </ul>
                    </div>

                    <div class="bg-white p-4 rounded border border-green-200">
                        <h3 class="font-semibold text-green-900 mb-2">✅ Yang BENAR:</h3>
                        <ul class="space-y-1 text-sm">
                            <li>• Link langsung ke file JSON di GitHub raw</li>
                            <li>• File JSON yang valid dengan struktur template</li>
                            <li>• File ZIP yang berisi template.json yang valid</li>
                            <li>• Gunakan example JSON yang disediakan sistem</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Methods -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- GitHub Import -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-github text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">GitHub Import</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-green-50 p-3 rounded border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-2">✅ Format URL Yang Benar:</h4>
                            <code class="text-xs bg-white px-2 py-1 rounded block">
                                https://github.com/user/repo/blob/main/template.json
                            </code>
                            <p class="text-xs text-green-700 mt-1">Sistem akan otomatis mengkonversi ke raw URL</p>
                        </div>

                        <div class="bg-red-50 p-3 rounded border border-red-200">
                            <h4 class="font-semibold text-red-900 mb-2">❌ Yang TIDAK Bisa:</h4>
                            <code class="text-xs bg-white px-2 py-1 rounded block mb-1">
                                https://github.com/user/repo
                            </code>
                            <p class="text-xs text-red-700">Hanya halaman repository, bukan file spesifik</p>
                        </div>

                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-900">Langkah-langkah:</h4>
                            <ol class="text-sm space-y-1 text-gray-700">
                                <li>1. Buka repository GitHub</li>
                                <li>2. Navigate ke file template.json</li>
                                <li>3. Copy URL dari address bar</li>
                                <li>4. Paste ke form import</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-upload text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">File Upload</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-green-50 p-3 rounded border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-2">✅ File Yang Didukung:</h4>
                            <ul class="text-xs space-y-1">
                                <li>• <strong>.json</strong> - Template JSON langsung</li>
                                <li>• <strong>.zip</strong> - Archive dengan template.json</li>
                                <li>• <strong>.html</strong> - File HTML untuk konversi</li>
                            </ul>
                        </div>

                        <div class="bg-blue-50 p-3 rounded border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2">🔧 Format JSON Yang Benar:</h4>
                            <div class="text-xs">
                                <a href="{{ url('sample-template.json') }}" target="_blank" class="text-blue-600 hover:underline">
                                    📄 Download contoh template.json
                                </a>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-900">Tips:</h4>
                            <ul class="text-sm space-y-1 text-gray-700">
                                <li>• Pastikan file JSON valid</li>
                                <li>• ZIP harus berisi template.json</li>
                                <li>• Ukuran maksimal 10MB</li>
                                <li>• Gunakan UTF-8 encoding</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- URL Import -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-link text-green-600 text-xl"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">URL Import</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-green-50 p-3 rounded border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-2">✅ URL Yang Didukung:</h4>
                            <ul class="text-xs space-y-1">
                                <li>• Template website sekolah</li>
                                <li>• Landing page pendidikan</li>
                                <li>• Demo template gratis</li>
                                <li>• GitHub raw file JSON/HTML</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                            <h4 class="font-semibold text-yellow-900 mb-2">⚠️ Perhatian:</h4>
                            <ul class="text-xs space-y-1 text-yellow-700">
                                <li>• Website harus dapat diakses publik</li>
                                <li>• Tidak memerlukan login</li>
                                <li>• Konten dalam HTML</li>
                                <li>• Bandwidth akan digunakan untuk download</li>
                            </ul>
                        </div>

                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-900">Fitur Otomatis:</h4>
                            <ul class="text-sm space-y-1 text-gray-700">
                                <li>• Deteksi bahasa otomatis</li>
                                <li>• Terjemahan ke Bahasa Indonesia</li>
                                <li>• Konversi ke format CMS</li>
                                <li>• Optimasi untuk sekolah</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Issues -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">🔧 Troubleshooting - Masalah Umum</h2>

                <div class="space-y-6">
                    <!-- JSON Error -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-times text-red-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">Error: "Unexpected token '<', '<!DOCTYPE'..."</h3>
                                <p class="text-gray-600 mb-2">Sistem menerima HTML instead of JSON</p>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-semibold text-gray-900 mb-1">Solusi:</p>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• Pastikan URL mengarah ke file JSON, bukan halaman HTML</li>
                                        <li>• Untuk GitHub: gunakan link ke file spesifik, bukan repository</li>
                                        <li>• Periksa file ZIP berisi template.json yang valid</li>
                                        <li>• Download dan periksa isi file sebelum upload</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GitHub Issues -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 mt-1">
                                <i class="fab fa-github text-purple-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">GitHub Import Gagal</h3>
                                <p class="text-gray-600 mb-2">Repository tidak bisa diakses atau file tidak ditemukan</p>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-semibold text-gray-900 mb-1">Checklist:</p>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• ✅ Repository public (bukan private)</li>
                                        <li>• ✅ File template.json ada dan accessible</li>
                                        <li>• ✅ Branch name correct (main/master)</li>
                                        <li>• ✅ URL format: github.com/user/repo/blob/branch/file.json</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ZIP Issues -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-file-archive text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">ZIP Import Error</h3>
                                <p class="text-gray-600 mb-2">File ZIP tidak berisi template JSON yang valid</p>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="font-semibold text-gray-900 mb-1">Requirements:</p>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• File ZIP harus berisi file JSON (template.json preferred)</li>
                                        <li>• JSON harus valid dan mengikuti struktur CMS</li>
                                        <li>• File tidak corrupt dan dapat dibuka</li>
                                        <li>• Ukuran file maksimal 10MB</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON Structure Guide -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">📋 Struktur JSON Template</h2>

                <div class="grid lg:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Format Yang Benar:</h3>
                        <pre class="bg-gray-50 p-4 rounded-lg text-xs overflow-auto"><code>{
  "name": "Template Sekolah",
  "description": "Template untuk website sekolah",
  "template_data": {
    "templates": [
      {
        "name": "Homepage",
        "slug": "home",
        "description": "Halaman utama",
        "active": true,
        "type": "page",
        "sections": [
          {
            "name": "Hero Section",
            "order": 1,
            "blocks": [
              {
                "type": "hero",
                "name": "Hero Utama",
                "order": 1,
                "content": {
                  "title": "Selamat Datang",
                  "subtitle": "Website Sekolah"
                }
              }
            ]
          }
        ]
      }
    ]
  }
}</code></pre>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Block Types Yang Tersedia:</h3>
                        <div class="space-y-2 text-sm">
                            <div class="bg-blue-50 p-2 rounded">
                                <strong>hero</strong> - Hero section dengan judul dan subtitle
                            </div>
                            <div class="bg-green-50 p-2 rounded">
                                <strong>card_grid</strong> - Grid kartu informasi
                            </div>
                            <div class="bg-yellow-50 p-2 rounded">
                                <strong>rich_text</strong> - Konten teks kaya
                            </div>
                            <div class="bg-purple-50 p-2 rounded">
                                <strong>stats</strong> - Statistik sekolah
                            </div>
                            <div class="bg-red-50 p-2 rounded">
                                <strong>cta_banner</strong> - Call-to-action banner
                            </div>
                            <div class="bg-indigo-50 p-2 rounded">
                                <strong>gallery_teaser</strong> - Preview galeri
                            </div>
                            <div class="bg-pink-50 p-2 rounded">
                                <strong>events_teaser</strong> - Preview event
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ url('sample-template.json') }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-2"></i>
                                Download Template JSON Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">🎯 Resources & Links</h2>

                <div class="grid md:grid-cols-3 gap-4">
                    <a href="{{ url('sample-template.json') }}" target="_blank" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-download text-blue-600 text-lg mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Sample JSON</p>
                                <p class="text-sm text-gray-600">Template contoh</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.templates.smart-import.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-upload text-green-600 text-lg mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Start Import</p>
                                <p class="text-sm text-gray-600">Mulai import template</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.templates.my-templates.index') }}" class="bg-white p-4 rounded-lg border border-gray-200 hover:border-purple-300 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-list text-purple-600 text-lg mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-900">My Templates</p>
                                <p class="text-sm text-gray-600">Template saya</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
