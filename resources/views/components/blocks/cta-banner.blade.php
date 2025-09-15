@props(['data'])

<section
  class="cta-banner-block py-16 bg-gradient-to-r from-blue-600 to-blue-800 text-white"
  x-data="{ visible: false }"
  x-init="(() => {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { visible = true; io.disconnect(); }
      });
    }, { threshold: 0.15 });
    io.observe($el);
  })()"
>
  <div class="container mx-auto px-4">
    <div class="text-center max-w-4xl mx-auto">
      <h2
        class="text-3xl md:text-4xl font-bold mb-4 transform transition-all duration-700 ease-out"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        Bergabunglah dengan Kami
      </h2>

      <p
        class="text-xl mb-8 opacity-90 transform transition-all duration-700"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        :style="visible ? 'transition-delay:100ms' : ''"
      >
        Daftarkan diri Anda dan menjadi bagian dari keluarga besar SMA Negeri 1 Contoh
      </p>

      <a
        href="/profil/penerimaan-siswa-baru"
        class="inline-block px-8 py-4 bg-white text-blue-900 rounded-lg font-semibold text-lg hover:bg-gray-100 transform transition-all duration-300"
        :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-4 scale-95'"
        :style="visible ? 'transition-delay:200ms' : ''"
      >
        Daftar Sekarang
      </a>
    </div>
  </div>
</section>
