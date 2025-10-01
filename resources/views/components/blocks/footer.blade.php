@props(['data' => []])

@php
$content = $data['content'] ?? '';
$contactInfo = $data['contact_info'] ?? [];
$footerLinks = $data['footer_links'] ?? [];
$backgroundColor = $data['background_color'] ?? 'bg-gray-900';
$textColor = $data['text_color'] ?? 'text-white';
$copyright = $data['copyright'] ?? '© ' . date('Y') . ' School CMS. All rights reserved.';
@endphp

<footer class="{{ $backgroundColor }} {{ $textColor }} py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- School Info -->
            <div class="md:col-span-2">
                <h3 class="text-xl font-bold mb-4">{{ config('app.name', 'School CMS') }}</h3>
                @if($content)
                    <div class="text-gray-300 mb-4">
                        {!! $content !!}
                    </div>
                @endif
                @if(isset($contactInfo['address']))
                    <p class="text-gray-300 text-sm">{{ $contactInfo['address'] }}</p>
                @endif
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <div class="space-y-2 text-sm text-gray-300">
                    @if(isset($contactInfo['email']))
                        <p>📧 {{ $contactInfo['email'] }}</p>
                    @endif
                    @if(isset($contactInfo['phone']))
                        <p>📞 {{ $contactInfo['phone'] }}</p>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Menu Cepat</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    @forelse($footerLinks as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}"
                               class="hover:text-white transition-colors">
                                {{ $link['title'] ?? 'Link' }}
                            </a>
                        </li>
                    @empty
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Program</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Berita</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>{{ $copyright }}</p>
            <p class="mt-2">Powered by School CMS</p>
        </div>
    </div>
</footer>
