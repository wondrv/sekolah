@php
$siteInfo = App\Support\Theme::getSiteInfo();
$footerMenuItems = App\Support\Theme::getMenu('footer');
$footerWidgets = App\Support\Theme::getWidgets('footer_col1');
@endphp

<footer class="bg-slate-900 text-slate-200">
  <div class="container mx-auto px-4 py-10">
    <div class="grid md:grid-cols-3 gap-8">
      {{-- Address Widget --}}
      <div>
        <h3 class="font-semibold mb-4">Alamat</h3>
        <p>{{ $siteInfo['contact']['address'] ?? 'Jl. Pendidikan No. 123, Jakarta Pusat 10430' }}</p>
      </div>

      {{-- Contact Widget --}}
      <div>
        <h3 class="font-semibold mb-4">Kontak</h3>
        <p>
          Telp: {{ $siteInfo['contact']['phone'] ?? '(021) 123-4567' }}<br>
          Email: {{ $siteInfo['contact']['email'] ?? 'info@namasekolah.sch.id' }}
        </p>
      </div>

      {{-- Social Media Widget --}}
      <div>
        <h3 class="font-semibold mb-4">Ikuti Kami</h3>
        <div class="flex gap-4">
          @if(isset($siteInfo['social']['facebook']) && $siteInfo['social']['facebook'] !== '#')
            <a href="{{ $siteInfo['social']['facebook'] }}" class="hover:text-white" target="_blank">Facebook</a>
          @endif
          @if(isset($siteInfo['social']['instagram']) && $siteInfo['social']['instagram'] !== '#')
            <a href="{{ $siteInfo['social']['instagram'] }}" class="hover:text-white" target="_blank">Instagram</a>
          @endif
          @if(isset($siteInfo['social']['youtube']) && $siteInfo['social']['youtube'] !== '#')
            <a href="{{ $siteInfo['social']['youtube'] }}" class="hover:text-white" target="_blank">YouTube</a>
          @endif
        </div>
      </div>
    </div>

    {{-- Footer Menu --}}
    @if($footerMenuItems && $footerMenuItems->count() > 0)
    <div class="border-t border-slate-700 pt-8 mt-8">
      <ul class="flex flex-wrap gap-6 justify-center">
        @foreach($footerMenuItems as $item)
          <li>
            <a href="{{ $item->url }}"
               target="{{ $item->is_external ? '_blank' : '_self' }}"
               class="text-sm hover:text-white">
              {{ $item->label }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>

  <div class="border-t border-slate-700 text-center py-4 text-sm">
    © {{ date('Y') }} {{ $siteInfo['name'] }}. All rights reserved.
  </div>
</footer>
