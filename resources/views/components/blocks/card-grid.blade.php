@props(['data'])

<section
  class="card-grid-block py-16 bg-gray-50"
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
    <div class="text-center mb-12">
      <h2
        class="text-3xl md:text-4xl font-bold mb-4 transform transition-all duration-700 ease-out"
        :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
      >
        Program Unggulan
      </h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto"
         :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
         :style="visible ? 'transition-delay:150ms' : ''">
        Program-program unggulan yang kami tawarkan untuk mengembangkan potensi siswa
      </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-500 delay-100 hover:shadow-lg"
           :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-6 scale-95'">
        <div class="aspect-video bg-gray-200">
          <img src="/images/program-ipa.jpg" alt="Program IPA" class="w-full h-full object-cover">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-3">Program IPA</h3>
          <p class="text-gray-600 mb-4">Program Ilmu Pengetahuan Alam dengan laboratorium lengkap dan modern</p>
          <a href="/profil/program-ipa" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            Selengkapnya
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-500 delay-200 hover:shadow-lg"
           :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-6 scale-95'">
        <div class="aspect-video bg-gray-200">
          <img src="/images/program-ips.jpg" alt="Program IPS" class="w-full h-full object-cover">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-3">Program IPS</h3>
          <p class="text-gray-600 mb-4">Program Ilmu Pengetahuan Sosial dengan kurikulum yang komprehensif</p>
          <a href="/profil/program-ips" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            Selengkapnya
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-500 delay-300 hover:shadow-lg"
           :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-6 scale-95'">
        <div class="aspect-video bg-gray-200">
          <img src="/images/program-bahasa.jpg" alt="Program Bahasa" class="w-full h-full object-cover">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-3">Program Bahasa</h3>
          <p class="text-gray-600 mb-4">Program Bahasa dengan fokus pada penguasaan bahasa asing</p>
          <a href="/profil/program-bahasa" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            Selengkapnya
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
