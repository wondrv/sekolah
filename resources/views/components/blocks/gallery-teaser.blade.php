@props(['data'])

<section class="gallery-teaser-block py-16 {{ $data['background_color'] ?? 'bg-white' }}">
  <div class="container mx-auto px-4">
    @if(isset($data['title']))
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">{{ $data['title'] }}</h2>
    @endif

    @php
      $galleries = \App\Models\Gallery::with('photos')
        ->orderBy('created_at', 'desc')
        ->limit($data['limit'] ?? 6)
        ->get();
    @endphp

    @if($galleries->count() > 0)
      <div class="grid md:grid-cols-3 gap-8">
        @foreach($galleries as $gallery)
          <div class="group">
            <a href="{{ route('galleries.show', $gallery->slug) }}" class="block">
              <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden mb-4">
                @if($gallery->photos->count() > 0)
                  <img src="{{ $gallery->photos->first()->url }}"
                       alt="{{ $gallery->title }}"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                  <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                  </div>
                @endif
              </div>

              <h3 class="text-xl font-semibold mb-2 group-hover:text-blue-600">{{ $gallery->title }}</h3>
              @if($gallery->description)
                <p class="text-gray-600 text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($gallery->description), 120) }}</p>
              @endif

              <div class="mt-2 text-sm text-gray-500">
                {{ $gallery->photos->count() }} foto
              </div>
            </a>
          </div>
        @endforeach
      </div>

      @if(isset($data['show_more_link']) && $data['show_more_link'])
        <div class="text-center mt-12">
          <a href="{{ route('galleries.index') }}"
             class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Lihat Semua Galeri
          </a>
        </div>
      @endif
    @else
      <div class="text-center text-gray-500">
        <p>Belum ada galeri yang dipublikasikan.</p>
      </div>
    @endif
  </div>
</section>
