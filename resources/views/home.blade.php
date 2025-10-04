@extends('layouts.main')
@section('title','Beranda')
@section('content')
<div class="text-center">
    <h2 class="text-4xl font-bold text-blue-900 mb-4">Selamat Datang di SMA Harapan Nusantara</h2>
    <p class="text-lg text-gray-700 mb-8">Mencetak Generasi Unggul, Berakhlak, dan Berprestasi</p>
    <a href="/ppdb" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 px-6 py-3 rounded font-semibold">Daftar PPDB Sekarang</a>
</div>

<section class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded shadow-lg p-6 text-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Guru Profesional</h3>
        <p>Tenaga pendidik berpengalaman, berdedikasi, dan berprestasi di bidangnya.</p>
    </div>
    <div class="bg-white rounded shadow-lg p-6 text-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Fasilitas Lengkap</h3>
        <p>Dilengkapi laboratorium, ruang multimedia, dan lapangan olahraga.</p>
    </div>
    <div class="bg-white rounded shadow-lg p-6 text-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Lingkungan Nyaman</h3>
        <p>Suasana belajar yang aman, bersih, dan kondusif bagi siswa.</p>
    </div>
</section>
@endsection
