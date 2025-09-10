@props(['data'])

<section class="events-teaser-block py-16 {{ $data['background_color'] ?? 'bg-gray-50' }}">
  <div class="container mx-auto px-4">
    @if(isset($data['title']))
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">{{ $data['title'] }}</h2>
    @endif

    @php
      $events = \App\Models\Event::where('status', 'published')
        ->where('date', '>=', now())
        ->orderBy('date', 'asc')
        ->limit($data['limit'] ?? 3)
        ->get();
    @endphp

    @if($events->count() > 0)
      <div class="grid md:grid-cols-{{ min($events->count(), 3) }} gap-8">
        @foreach($events as $event)
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($event->thumbnail)
              <div class="aspect-video bg-gray-200">
                <img src="{{ asset('storage/' . $event->thumbnail) }}"
                     alt="{{ $event->title }}"
                     class="w-full h-full object-cover">
              </div>
            @endif

            <div class="p-6">
              <div class="flex items-center text-sm text-gray-500 mb-3">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                {{ $event->date ? $event->date->format('d M Y') : '' }}
              </div>

              <h3 class="text-xl font-semibold mb-3">
                <a href="{{ route('events.show', $event->id) }}" class="hover:text-blue-600">
                  {{ $event->title }}
                </a>
              </h3>

              @if($event->excerpt)
                <p class="text-gray-600 mb-4">{{ $event->excerpt }}</p>
              @endif

              <a href="{{ route('events.show', $event->id) }}"
                 class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                Selengkapnya
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        @endforeach
      </div>

      @if(isset($data['show_more_link']) && $data['show_more_link'])
        <div class="text-center mt-12">
          <a href="{{ route('events.index') }}"
             class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Lihat Semua Agenda
          </a>
        </div>
      @endif
    @else
      <div class="text-center text-gray-500">
        <p>Belum ada agenda yang akan datang.</p>
      </div>
    @endif
  </div>
</section>
