@extends('layouts.main')
@section('title','Program Unggulan')
@section('content')
<h2 class="text-3xl font-bold text-blue-900 mb-4">Program Unggulan</h2>
<table class="min-w-full bg-white rounded shadow-md overflow-hidden">
    <thead class="bg-blue-900 text-yellow-400">
        <tr>
            <th class="py-3 px-4 text-left">Nama Program</th>
            <th class="py-3 px-4 text-left">Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        <tr class="border-b">
            <td class="py-3 px-4">Kelas Sains & Riset</td>
            <td class="py-3 px-4">Program akademik berbasis eksperimen dan penelitian ilmiah.</td>
        </tr>
        <tr class="border-b">
            <td class="py-3 px-4">Kelas ICT</td>
            <td class="py-3 px-4">Berbasis teknologi digital dengan fokus pada literasi komputer dan coding.</td>
        </tr>
        <tr>
            <td class="py-3 px-4">Kelas Tahfidz</td>
            <td class="py-3 px-4">Meningkatkan kemampuan spiritual dan hafalan Al-Qur'an bagi siswa.</td>
        </tr>
    </tbody>
</table>
@endsection
