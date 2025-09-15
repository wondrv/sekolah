@props(['data'])

<section class="events-teaser-block py-16 bg-white"
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
    <h2
      class="text-3xl md:text-4xl font-bold text-center mb-12 transform transition-all duration-700 ease-out"
      :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
    >
      Agenda Mendatang
    </h2>

    <div
      class="text-center text-gray-500 transform transition-all duration-500"
      :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
    >
      <p>Belum ada agenda yang akan datang.</p>
    </div>
  </div>
</section>
