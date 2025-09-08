<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Nama Sekolah')</title>
  <meta name="description" content="@yield('meta_description','Profil resmi sekolah')">
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
  @stack('head')
</head>
<body class="min-h-dvh flex flex-col">
  <a href="#main" class="sr-only focus:not-sr-only">Lewati ke konten</a>

  {{-- Navbar tanpa tombol login --}}
  <header class="border-b bg-white/80 backdrop-blur">
    <nav class="container mx-auto px-4 py-3 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
        <span class="font-semibold">Nama Sekolah</span>
      </a>
      <ul class="hidden md:flex items-center gap-6">
        <li><a href="/profil" class="hover:underline">Profil</a></li>
        <li><a href="/berita" class="hover:underline">Berita</a></li>
        <li><a href="/agenda" class="hover:underline">Agenda</a></li>
        <li><a href="/galeri" class="hover:underline">Galeri</a></li>
        <li><a href="/kontak" class="hover:underline">Kontak</a></li>
        {{-- Tidak ada link /admin/login di sini --}}
      </ul>
    </nav>
  </header>

  <main id="main" class="flex-1">
    @yield('content')
  </main>

  <footer class="bg-slate-900 text-slate-200">
    <div class="container mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">
      <div><h3 class="font-semibold mb-2">Alamat</h3><p>Jl. Pendidikan No. 123<br>Jakarta Pusat 10430</p></div>
      <div><h3 class="font-semibold mb-2">Kontak</h3><p>Telp: (021) 123-4567<br>Email: info@namasekolah.sch.id</p></div>
      <div><h3 class="font-semibold mb-2">Ikuti Kami</h3><p>Facebook • Instagram • YouTube</p></div>
    </div>
    <div class="border-t border-slate-700 text-center py-4 text-sm">© {{ date('Y') }} Nama Sekolah</div>
  </footer>
  @stack('scripts')
</body>
</html>
