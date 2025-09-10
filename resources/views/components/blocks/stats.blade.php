@props(['data'])

<section class="stats-block py-16 {{ $data['background_color'] ?? 'bg-blue-900' }} text-white">
  <div class="container mx-auto px-4">
    @if(isset($data['title']))
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">{{ $data['title'] }}</h2>
    @endif

    @if(isset($data['stats']) && is_array($data['stats']))
      <div class="grid grid-cols-2 md:grid-cols-{{ count($data['stats']) <= 4 ? count($data['stats']) : 4 }} gap-8">
        @foreach($data['stats'] as $stat)
          <div class="text-center">
            @if(isset($stat['number']))
              <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stat['number'] }}</div>
            @endif
            @if(isset($stat['label']))
              <div class="text-lg opacity-90">{{ $stat['label'] }}</div>
            @endif
            @if(isset($stat['description']))
              <div class="text-sm opacity-75 mt-1">{{ $stat['description'] }}</div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
