@extends('layouts.app')
@section('title', 'Halaman Tidak Tersedia')
@section('content')
<div class="container mx-auto px-4 py-24">
    <div class="max-w-2xl mx-auto text-center bg-white rounded-lg shadow p-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Pendaftaran Online Tidak Tersedia</h1>
        <p class="text-gray-600 mb-6">Fitur pendaftaran online telah dinonaktifkan. Untuk informasi pendaftaran, silakan hubungi kami melalui halaman kontak.</p>
        <div class="flex justify-center gap-4">
            <a href="/" class="btn-secondary px-6 py-3 rounded-lg">Kembali ke Beranda</a>
            <a href="/kontak" class="btn-primary px-6 py-3 rounded-lg">Halaman Kontak</a>
        </div>
    </div>
</div>
@endsection
