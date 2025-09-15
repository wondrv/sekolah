@props(['data'])

<section
  class="stats-block py-16 bg-blue-900 text-white"
  x-data="{ visible: false }"
  x-init="(() => {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { visible = true; io.disconnect(); }
      });
    }, { threshold: 0.2 });
    io.observe($el);
  })()"
>
  <div class="container mx-auto px-4">
    <h2
      class="text-3xl md:text-4xl font-bold text-center mb-12 transform transition-all duration-700 ease-out"
      :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
    >
      Prestasi Kami
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      <div
        class="text-center transform transition-all duration-500 delay-100"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        <div class="text-4xl md:text-5xl font-bold mb-2">1200+</div>
        <div class="text-lg opacity-90">Siswa Aktif</div>
        <div class="text-sm opacity-75 mt-1">Siswa yang terdaftar</div>
      </div>

      <div
        class="text-center transform transition-all duration-500 delay-200"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        <div class="text-4xl md:text-5xl font-bold mb-2">85+</div>
        <div class="text-lg opacity-90">Tenaga Pendidik</div>
        <div class="text-sm opacity-75 mt-1">Guru berpengalaman</div>
      </div>

      <div
        class="text-center transform transition-all duration-500 delay-300"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
        <div class="text-lg opacity-90">Prestasi</div>
        <div class="text-sm opacity-75 mt-1">Tingkat nasional</div>
      </div>

      <div
        class="text-center transform transition-all duration-500 delay-400"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        <div class="text-4xl md:text-5xl font-bold mb-2">98%</div>
        <div class="text-lg opacity-90">Kelulusan</div>
        <div class="text-sm opacity-75 mt-1">Tingkat kelulusan</div>
      </div>
    </div>
  </div>
</section>
