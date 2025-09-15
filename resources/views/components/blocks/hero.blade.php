@props(['data'])

<section class="hero-block relative overflow-hidden {{ $data['background_color'] ?? 'bg-gradient-to-r from-blue-600 to-blue-800' }} text-white">
  @if(isset($data['background_image']))
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $data['background_image'] }}')"></div>
    <div class="absolute inset-0 bg-black/50"></div>
  @endif

  <div class="relative container mx-auto px-4 py-24">
    <div
      class="max-w-4xl text-center mx-auto transform transition-all duration-700 ease-out"
      x-data="{ entered: false }"
      x-init="requestAnimationFrame(() => entered = true)"
      :class="entered ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-6 scale-95'">
      
      @if(isset($data['title']))
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
          {{ $data['title'] }}
        </h1>
      @endif

      @if(isset($data['subtitle']))
        <p class="text-xl md:text-2xl mb-8 opacity-90">
          {{ $data['subtitle'] }}
        </p>
      @endif

      @if(isset($data['buttons']) && is_array($data['buttons']))
        <div class="flex flex-wrap gap-4 justify-center">
          @foreach($data['buttons'] as $button)
            <a href="{{ $button['url'] ?? '#' }}"
               class="inline-block px-8 py-3 rounded-lg font-semibold transition-all duration-200 hover:scale-105 hover:shadow-lg {{ ($button['style'] ?? '') === 'secondary' ? 'bg-white text-gray-900 hover:bg-gray-100' : 'bg-accent hover:bg-accent/90' }}">
              {{ $button['text'] ?? 'Learn More' }}
            </a>
          @endforeach
        </div>
      @endif

    </div>
  </div>
</section>
