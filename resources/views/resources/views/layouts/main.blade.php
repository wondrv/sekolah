<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SMA HARAPAN NUSANTARA')</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900">
  <header class="bg-blue-900 text-yellow-400 sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <h1 class="text-2xl font-extrabold tracking-wide">SMA HARAPAN NUSANTARA</h1>
      <nav class="space-x-4 text-white">
        <a href="{{ route('home') }}" class="hover:text-yellow-400">Home</a>
        <a href="{{ route('tentang') }}" class="hover:text-yellow-400">Tentang</a>
        <a href="{{ route('program') }}" class="hover:text-yellow-400">Program</a>
        <a href="{{ route('berita') }}" class="hover:text-yellow-400">Berita</a>
        <a href="{{ route('galeri') }}" class="hover:text-yellow-400">Galeri</a>
        <a href="{{ route('ppdb') }}" class="hover:text-yellow-400">PPDB</a>
        <a href="{{ route('kontak') }}" class="hover:text-yellow-400">Kontak</a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto px-6 py-10">
    @yield('content')
  </main>

  <footer class="bg-blue-950 text-yellow-400 py-6 text-center mt-10">
    <p class="text-sm">© {{ date('Y') }} SMA Harapan Nusantara. All rights reserved.</p>
    <p>Jl. Merdeka No.45, Sidoarjo | Telp: 031-456789 | Email: info@harapannusantara.sch.id</p>
  </footer>
</body>
</html>
