<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nama Sekolah') }}</title>

        <!-- Tailwind CSS -->
        <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

        <!-- Alpine.js for interactive components -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- School Logo & Branding -->
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center group">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-xl group-hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105">
                        <i class="fas fa-graduation-cap text-white text-3xl"></i>
                    </div>
                    <h1 class="mt-4 text-2xl font-bold text-gray-800 text-center">Sekolah</h1>
                    <p class="text-sm text-gray-600 mt-1">Portal Akademik</p>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="w-full sm:max-w-md">
                <div class="bg-white/80 backdrop-blur-sm shadow-xl border border-white/20 overflow-hidden sm:rounded-2xl">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="mt-6 text-center">
                    <a href="/" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <!-- Custom Styles -->
        <style>
            .bg-grid-pattern {
                background-image: radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.1) 1px, transparent 0);
                background-size: 20px 20px;
            }
        </style>
    </body>
</html>
