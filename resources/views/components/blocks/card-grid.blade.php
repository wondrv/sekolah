@props(['data'])

<section class="card-grid-block py-16 {{ $data['background_color'] ?? 'bg-gray-50' }}">
  <div class="container mx-auto px-4">
    @if(isset($data['title']) || isset($data['subtitle']))
      <div class="text-center mb-12">
        @if(isset($data['title']))
          <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ $data['title'] }}</h2>
        @endif
        @if(isset($data['subtitle']))
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $data['subtitle'] }}</p>
        @endif
      </div>
    @endif

    @if(isset($data['cards']) && is_array($data['cards']))
      <div class="grid md:grid-cols-{{ $data['columns'] ?? 3 }} gap-8">
        @foreach($data['cards'] as $card)
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if(isset($card['image']))
              <div class="aspect-video bg-gray-200">
                <img src="{{ $card['image'] }}"
                     alt="{{ $card['title'] ?? '' }}"
                     class="w-full h-full object-cover">
              </div>
            @endif

            <div class="p-6">
              @if(isset($card['title']))
                <h3 class="text-xl font-semibold mb-3">{{ $card['title'] }}</h3>
              @endif

              @if(isset($card['description']))
                <p class="text-gray-600 mb-4">{{ $card['description'] }}</p>
              @endif

              @if(isset($card['link']))
                <a href="{{ $card['link']['url'] ?? '#' }}"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                  {{ $card['link']['text'] ?? 'Read More' }}
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </a>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
