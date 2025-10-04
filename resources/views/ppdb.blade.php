@extends('layouts.main')
@section('title','PPDB - Brosur & Biaya Pendaftaran')
@section('content')
<h2 class="text-3xl font-bold text-blue-900 mb-6">Brosur & Biaya Pendaftaran</h2>
<div class="bg-white rounded shadow-md p-6">
    <p class="mb-4 text-gray-700">Berikut informasi biaya pendaftaran dan fasilitas yang tersedia:</p>
    <table class="min-w-full border border-gray-300">
        <thead class="bg-blue-900 text-yellow-400">
            <tr>
                <th class="py-3 px-4 text-left">Jenis Biaya</th>
                <th class="py-3 px-4 text-left">Keterangan</th>
                <th class="py-3 px-4 text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b">
                <td class="py-3 px-4">Formulir Pendaftaran</td>
                <td class="py-3 px-4">Dibayar sekali saat pendaftaran</td>
                <td class="py-3 px-4 text-right">Rp 150.000</td>
            </tr>
            <tr class="border-b">
                <td class="py-3 px-4">Uang Pangkal</td>
                <td class="py-3 px-4">Dapat dicicil hingga 3x</td>
                <td class="py-3 px-4 text-right">Rp 2.000.000</td>
            </tr>
            <tr>
                <td class="py-3 px-4">SPP Bulanan</td>
                <td class="py-3 px-4">Sudah termasuk kegiatan ekstrakurikuler</td>
                <td class="py-3 px-4 text-right">Rp 350.000</td>
            </tr>
        </tbody>
    </table>
    <div class="mt-6 text-center">
        <a href="#" class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 px-6 py-3 rounded font-semibold">Unduh Brosur PDF</a>
    </div>
</div>
@endsection
