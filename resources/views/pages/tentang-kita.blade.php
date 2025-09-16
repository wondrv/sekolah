@extends('layouts.app')
@section('title','Tentang Kita')
@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Tentang Kita</h1>

    <div class="max-w-4xl mx-auto">
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Visi & Misi</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-blue-600">Visi</h3>
                    <p class="text-gray-700">Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan siap menghadapi tantangan global.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-blue-600">Misi</h3>
                    <ul class="text-gray-700 space-y-2">
                        <li>• Menyelenggarakan pendidikan berkualitas tinggi</li>
                        <li>• Mengembangkan karakter siswa yang berakhlak mulia</li>
                        <li>• Memfasilitasi pengembangan potensi akademik dan non-akademik</li>
                        <li>• Menciptakan lingkungan belajar yang kondusif</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Sejarah</h2>
            <p class="text-gray-700 leading-relaxed">
                Didirikan pada tahun 1990, sekolah kami telah mengabdi dalam dunia pendidikan selama lebih dari 30 tahun.
                Dengan komitmen untuk memberikan pendidikan terbaik, kami terus berinovasi dan berkembang mengikuti
                perkembangan zaman sambil tetap mempertahankan nilai-nilai luhur pendidikan.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-semibold mb-6">Akreditasi</h2>
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">A</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-green-800">Akreditasi A</h3>
                        <p class="text-green-700">Badan Akreditasi Nasional Sekolah/Madrasah (BAN-S/M)</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
