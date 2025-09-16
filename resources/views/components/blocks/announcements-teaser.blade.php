@props(['data'])

@php
  $limit = $data['limit'] ?? (int) (\App\Models\Setting::get('announcements_items_home', 3) ?: 3);
  $title = $data['title'] ?? (\App\Models\Setting::get('announcements_section_title', 'Pengumuman') ?: 'Pengumuman');
  $show = (\App\Models\Setting::get('announcements_show_on_home', '1') === '1');
  $slug = $data['category'] ?? (\App\Models\Setting::get('announcements_category_slug', 'pengumuman') ?: 'pengumuman');
@endphp

@if($show)
<section class="announcements-teaser-block py-16 {{ $data['background_color'] ?? 'bg-white' }}">
  <div class="container mx-auto px-4">
    @if($title)
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">{{ $title }}</h2>
    @endif

    @php
      $category = \App\Models\Category::where('slug', $slug)->first();
      $posts = collect();
      if ($category) {
          $posts = \App\Models\Post::with('category', 'user')
            ->published()
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->limit(max(1, $limit))
            ->get();
      }
    @endphp

    @if($posts->count() > 0)
      <div class="grid md:grid-cols-{{ min($posts->count(), 3) }} gap-8">
        @foreach($posts as $post)
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($post->cover_path)
              <div class="aspect-video bg-gray-200">
                <img src="{{ Storage::url($post->cover_path) }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
              </div>
            @endif
            <div class="p-6">
              <div class="flex items-center text-sm text-gray-500 mb-3">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                {{ optional($post->published_at)->format('d M Y') }}
                @if($post->category)
                  <span class="mx-2">•</span>
                  <span>{{ $post->category->name }}</span>
                @endif
              </div>
              <h3 class="text-xl font-semibold mb-3">
                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
              </h3>
              @if($post->excerpt)
                <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
              @endif
              <a href="{{ route('posts.show', $post->slug) }}"
                 class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                Baca Selengkapnya
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        @endforeach
      </div>

      @if(($data['show_more_link'] ?? true) && $category)
        <div class="text-center mt-12">
          <a href="{{ route('posts.index', ['kategori' => $category->slug]) }}"
             class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Lihat Semua Pengumuman
          </a>
        </div>
      @endif
    @else
      <div class="text-center text-gray-500">
        <p>Belum ada pengumuman.</p>
      </div>
    @endif
  </div>
</section>
@endif
