@php
$siteInfo = App\Support\Theme::getSiteInfo();
$footerMenuItems = App\Support\Theme::getMenu('footer');
$footerSettings = App\Support\Theme::getSettings(['footer_bg_color', 'footer_show_map', 'footer_copyright', 'social_show_in_footer', 'google_maps_embed']);
$socialMedia = $siteInfo['social'] ?? [];
@endphp

<footer class="text-white" style="background-color: {{ $footerSettings['footer_bg_color'] ?? '#1e293b' }};">
  <!-- Main Footer Content -->
  <div class="container mx-auto px-4 py-12">
    <div class="grid md:grid-cols-4 gap-8">

      <!-- School Information -->
      <div class="md:col-span-1">
        <div class="flex items-center gap-3 mb-4">
          @if($siteInfo['logo'])
            <img src="{{ asset($siteInfo['logo']) }}" alt="Logo {{ $siteInfo['name'] }}" class="h-12 w-auto object-contain">
          @endif
          <div>
            <h3 class="font-bold text-lg">{{ $siteInfo['name'] ?? 'Nama Sekolah' }}</h3>
            @if(isset($siteInfo['tagline']) && $siteInfo['tagline'])
              <p class="text-sm opacity-75">{{ $siteInfo['tagline'] }}</p>
            @endif
          </div>
        </div>
        @if(isset($siteInfo['description']) && $siteInfo['description'])
          <p class="text-sm opacity-90 leading-relaxed">{{ Str::limit($siteInfo['description'], 120) }}</p>
        @endif
      </div>

      <!-- Contact Information -->
      <div class="md:col-span-1">
        <h4 class="font-semibold mb-4 text-lg">Kontak Kami</h4>
        <div class="space-y-3 text-sm">
          @if(isset($siteInfo['contact']['address']) && $siteInfo['contact']['address'])
            <div class="flex items-start gap-2">
              <svg class="w-5 h-5 mt-0.5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>{{ $siteInfo['contact']['address'] }}</span>
            </div>
          @endif

          @if(isset($siteInfo['contact']['phone']) && $siteInfo['contact']['phone'])
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              <a href="tel:{{ $siteInfo['contact']['phone'] }}" class="hover:text-blue-300 transition-colors">
                {{ $siteInfo['contact']['phone'] }}
              </a>
            </div>
          @endif

          @if(isset($siteInfo['contact']['email']) && $siteInfo['contact']['email'])
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-red-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              <a href="mailto:{{ $siteInfo['contact']['email'] }}" class="hover:text-blue-300 transition-colors">
                {{ $siteInfo['contact']['email'] }}
              </a>
            </div>
          @endif

          @if(isset($siteInfo['contact']['whatsapp']) && $siteInfo['contact']['whatsapp'])
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-300 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteInfo['contact']['whatsapp']) }}" target="_blank" class="hover:text-green-300 transition-colors">
                WhatsApp
              </a>
            </div>
          @endif
        </div>
      </div>

      <!-- Quick Links -->
      <div class="md:col-span-1">
        <h4 class="font-semibold mb-4 text-lg">Menu Cepat</h4>
        <div class="space-y-2">
          @if($footerMenuItems && $footerMenuItems->count() > 0)
            @foreach($footerMenuItems as $item)
              <a href="{{ $item->url }}"
                 target="{{ $item->is_external ? '_blank' : '_self' }}"
                 class="block text-sm hover:text-blue-300 transition-colors py-1">
                {{ $item->label }}
              </a>
            @endforeach
          @else
            <!-- Fallback links -->
            <a href="/tentang-kita" class="block text-sm hover:text-blue-300 transition-colors py-1">Tentang Kita</a>
            <a href="/berita" class="block text-sm hover:text-blue-300 transition-colors py-1">Berita</a>
            <a href="/agenda" class="block text-sm hover:text-blue-300 transition-colors py-1">Agenda</a>
            <a href="/galeri" class="block text-sm hover:text-blue-300 transition-colors py-1">Galeri</a>
            <a href="/kontak" class="block text-sm hover:text-blue-300 transition-colors py-1">Kontak</a>
          @endif
        </div>
      </div>

      <!-- Social Media & Additional Info -->
      <div class="md:col-span-1">
        <h4 class="font-semibold mb-4 text-lg">Ikuti Kami</h4>

        @if($footerSettings['social_show_in_footer'] ?? true)
          <div class="flex space-x-4 mb-6">
            @if(isset($socialMedia['facebook']) && $socialMedia['facebook'])
              <a href="{{ $socialMedia['facebook'] }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                <span class="sr-only">Facebook</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
            @endif

            @if(isset($socialMedia['instagram']) && $socialMedia['instagram'])
              <a href="{{ $socialMedia['instagram'] }}" target="_blank" class="text-pink-400 hover:text-pink-300 transition-colors">
                <span class="sr-only">Instagram</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297-.807-.875-1.297-2.026-1.297-3.323s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323c-.875.807-2.026 1.297-3.323 1.297z"/>
                </svg>
              </a>
            @endif

            @if(isset($socialMedia['youtube']) && $socialMedia['youtube'])
              <a href="{{ $socialMedia['youtube'] }}" target="_blank" class="text-red-400 hover:text-red-300 transition-colors">
                <span class="sr-only">YouTube</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
            @endif

            @if(isset($socialMedia['twitter']) && $socialMedia['twitter'])
              <a href="{{ $socialMedia['twitter'] }}" target="_blank" class="text-blue-400 hover:text-blue-300 transition-colors">
                <span class="sr-only">Twitter</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
            @endif

            @if(isset($socialMedia['tiktok']) && $socialMedia['tiktok'])
              <a href="{{ $socialMedia['tiktok'] }}" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                <span class="sr-only">TikTok</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                </svg>
              </a>
            @endif

            @if(isset($socialMedia['linkedin']) && $socialMedia['linkedin'])
              <a href="{{ $socialMedia['linkedin'] }}" target="_blank" class="text-blue-500 hover:text-blue-400 transition-colors">
                <span class="sr-only">LinkedIn</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
            @endif
          </div>
        @endif

        <!-- Additional Info -->
        <div class="text-sm space-y-2">
          <p class="text-gray-300">
            <span class="font-medium">Jam Operasional:</span><br>
            Senin - Jumat: 07:00 - 16:00<br>
            Sabtu: 07:00 - 12:00
          </p>
        </div>
      </div>
    </div>

    <!-- Map Section (if enabled) -->
    @if(($footerSettings['footer_show_map'] ?? false) && isset($footerSettings['google_maps_embed']) && $footerSettings['google_maps_embed'])
      <div class="mt-8 pt-8 border-t border-gray-600">
        <h4 class="font-semibold mb-4 text-lg">Lokasi Kami</h4>
        <div class="aspect-video bg-gray-700 rounded-lg overflow-hidden">
          <iframe
            src="{{ $footerSettings['google_maps_embed'] }}"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi {{ $siteInfo['name'] ?? 'Sekolah' }}">
          </iframe>
        </div>
      </div>
    @endif
  </div>

  <!-- Bottom Footer -->
  <div class="border-t border-gray-600" style="background-color: {{ $footerSettings['footer_bg_color'] ?? '#1e293b' }};">
    <div class="container mx-auto px-4 py-6">
      <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <div class="text-sm text-gray-300">
          {{ $footerSettings['footer_copyright'] ?? '© ' . date('Y') . ' ' . ($siteInfo['name'] ?? 'Nama Sekolah') . '. All rights reserved.' }}
        </div>
      </div>
    </div>
  </div>
</footer>
