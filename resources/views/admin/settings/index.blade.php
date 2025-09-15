@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('subtitle', 'Kelola tampilan, konten, dan konfigurasi seluruh website')

@section('content')
@include('components.admin.alerts')

<div class="space-y-8">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200" x-data="{ activeTab: 'general' }">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                🏫 Informasi Umum
            </button>
            <button @click="activeTab = 'header'"
                    :class="activeTab === 'header' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                🎨 Header & Navigasi
            </button>
            <button @click="activeTab = 'theme'"
                    :class="activeTab === 'theme' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                🌈 Tema & Warna
            </button>
            <button @click="activeTab = 'footer'"
                    :class="activeTab === 'footer' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                📍 Footer & Kontak
            </button>
            <button @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                📱 Media Sosial
            </button>
        </nav>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" x-data="{ activeTab: 'general' }">
        @csrf
        @method('PUT')

        <!-- General Information Tab -->
        <div x-show="activeTab === 'general'" class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🏫</span>
                Informasi Umum Website
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                        <input type="text" name="site_name" id="site_name"
                            value="{{ old('site_name', $settings['site_name'] ?? 'SMK Teknologi Informatika') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <p class="text-xs text-gray-500 mt-1">Akan tampil di header dan title halaman</p>
                    </div>

                    <div>
                        <label for="site_tagline" class="block text-sm font-medium text-gray-700">Tagline/Slogan</label>
                        <input type="text" name="site_tagline" id="site_tagline"
                            value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Mencetak Generasi Digital Unggul') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Deskripsi Website</label>
                        <textarea name="site_description" id="site_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('site_description', $settings['site_description'] ?? 'Sekolah teknologi terdepan yang mempersiapkan siswa menghadapi era digital dengan pendidikan berkualitas dan fasilitas modern.') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Untuk SEO dan meta description</p>
                    </div>

                    <div>
                        <label for="site_keywords" class="block text-sm font-medium text-gray-700">Kata Kunci SEO</label>
                        <input type="text" name="site_keywords" id="site_keywords"
                            value="{{ old('site_keywords', $settings['site_keywords'] ?? 'SMK, sekolah teknologi, informatika, komputer, digital') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="pisahkan dengan koma">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo Sekolah</label>
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if(isset($settings['logo']) && $settings['logo'])
                            <div class="mt-2">
                                <img src="{{ asset($settings['logo']) }}" alt="Current Logo" class="h-16 object-contain">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                        <input type="file" name="favicon" id="favicon" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if(isset($settings['favicon']) && $settings['favicon'])
                            <div class="mt-2">
                                <img src="{{ asset($settings['favicon']) }}" alt="Current Favicon" class="h-8 w-8 object-contain">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="hero_image" class="block text-sm font-medium text-gray-700">Gambar Hero/Banner Utama</label>
                        <input type="file" name="hero_image" id="hero_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if(isset($settings['hero_image']) && $settings['hero_image'])
                            <div class="mt-2">
                                <img src="{{ asset($settings['hero_image']) }}" alt="Current Hero" class="h-24 object-cover rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Header & Navigation Tab -->
        <div x-show="activeTab === 'header'" class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🎨</span>
                Header & Navigasi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi Logo</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="header_logo_position" value="left"
                                       {{ ($settings['header_logo_position'] ?? 'left') === 'left' ? 'checked' : '' }}
                                       class="form-radio text-blue-600">
                                <span class="ml-2">Kiri</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="header_logo_position" value="center"
                                       {{ ($settings['header_logo_position'] ?? 'left') === 'center' ? 'checked' : '' }}
                                       class="form-radio text-blue-600">
                                <span class="ml-2">Tengah</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="header_sticky" value="1"
                                   {{ ($settings['header_sticky'] ?? false) ? 'checked' : '' }}
                                   class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Header Menempel (Sticky)</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="header_transparent" value="1"
                                   {{ ($settings['header_transparent'] ?? false) ? 'checked' : '' }}
                                   class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Header Transparan</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="header_bg_color" class="block text-sm font-medium text-gray-700">Warna Background Header</label>
                        <input type="color" name="header_bg_color" id="header_bg_color"
                            value="{{ old('header_bg_color', $settings['header_bg_color'] ?? '#ffffff') }}"
                            class="mt-1 block w-20 h-10 rounded border border-gray-300">
                    </div>

                    <div>
                        <label for="header_text_color" class="block text-sm font-medium text-gray-700">Warna Teks Header</label>
                        <input type="color" name="header_text_color" id="header_text_color"
                            value="{{ old('header_text_color', $settings['header_text_color'] ?? '#000000') }}"
                            class="mt-1 block w-20 h-10 rounded border border-gray-300">
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
                            💡 <strong>Tips:</strong> Untuk mengelola menu navigasi, gunakan halaman
                            <a href="{{ route('admin.menus.index') }}" class="text-blue-600 underline">Menu Management</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme & Colors Tab -->
        <div x-show="activeTab === 'theme'" class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🌈</span>
                Tema & Warna Website
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700">Warna Primer</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="primary_color" id="primary_color"
                                value="{{ old('primary_color', $settings['primary_color'] ?? '#3b82f6') }}"
                                class="w-12 h-10 rounded border border-gray-300">
                            <input type="text" name="primary_color_hex"
                                value="{{ old('primary_color', $settings['primary_color'] ?? '#3b82f6') }}"
                                class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Untuk tombol, link, dan aksen utama</p>
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700">Warna Sekunder</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="secondary_color" id="secondary_color"
                                value="{{ old('secondary_color', $settings['secondary_color'] ?? '#64748b') }}"
                                class="w-12 h-10 rounded border border-gray-300">
                            <input type="text" name="secondary_color_hex"
                                value="{{ old('secondary_color', $settings['secondary_color'] ?? '#64748b') }}"
                                class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly>
                        </div>
                    </div>

                    <div>
                        <label for="accent_color" class="block text-sm font-medium text-gray-700">Warna Aksen</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="accent_color" id="accent_color"
                                value="{{ old('accent_color', $settings['accent_color'] ?? '#f59e0b') }}"
                                class="w-12 h-10 rounded border border-gray-300">
                            <input type="text" name="accent_color_hex"
                                value="{{ old('accent_color', $settings['accent_color'] ?? '#f59e0b') }}"
                                class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="font_family" class="block text-sm font-medium text-gray-700">Font Utama</label>
                        <select name="font_family" id="font_family"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="Inter" {{ ($settings['font_family'] ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter (Default)</option>
                            <option value="Roboto" {{ ($settings['font_family'] ?? 'Inter') === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans" {{ ($settings['font_family'] ?? 'Inter') === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                            <option value="Poppins" {{ ($settings['font_family'] ?? 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Nunito" {{ ($settings['font_family'] ?? 'Inter') === 'Nunito' ? 'selected' : '' }}>Nunito</option>
                        </select>
                    </div>

                    <div>
                        <label for="border_radius" class="block text-sm font-medium text-gray-700">Border Radius</label>
                        <select name="border_radius" id="border_radius"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="0.375rem" {{ ($settings['border_radius'] ?? '0.375rem') === '0.375rem' ? 'selected' : '' }}>Standard (6px)</option>
                            <option value="0.25rem" {{ ($settings['border_radius'] ?? '0.375rem') === '0.25rem' ? 'selected' : '' }}>Sharp (4px)</option>
                            <option value="0.5rem" {{ ($settings['border_radius'] ?? '0.375rem') === '0.5rem' ? 'selected' : '' }}>Rounded (8px)</option>
                            <option value="1rem" {{ ($settings['border_radius'] ?? '0.375rem') === '1rem' ? 'selected' : '' }}>Very Rounded (16px)</option>
                        </select>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg">
                        <h4 class="font-medium text-sm text-gray-900 mb-2">Preview Warna</h4>
                        <div class="flex space-x-2">
                            <div class="w-8 h-8 rounded" style="background-color: var(--color-primary, #3b82f6)"></div>
                            <div class="w-8 h-8 rounded" style="background-color: var(--color-secondary, #64748b)"></div>
                            <div class="w-8 h-8 rounded" style="background-color: var(--color-accent, #f59e0b)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer & Contact Tab -->
        <div x-show="activeTab === 'footer'" class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">📍</span>
                Footer & Informasi Kontak
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="contact_address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="contact_address" id="contact_address" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('contact_address', $settings['contact_address'] ?? 'Jl. Pendidikan No. 123, Jakarta Pusat 10430, Indonesia') }}</textarea>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="contact_phone" id="contact_phone"
                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '(021) 123-4567') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Resmi</label>
                        <input type="email" name="contact_email" id="contact_email"
                            value="{{ old('contact_email', $settings['contact_email'] ?? 'info@sekolah.sch.id') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="contact_whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                        <input type="text" name="contact_whatsapp" id="contact_whatsapp"
                            value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '+62812-3456-7890') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="+62812-3456-7890">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="footer_copyright" class="block text-sm font-medium text-gray-700">Teks Copyright</label>
                        <input type="text" name="footer_copyright" id="footer_copyright"
                            value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '© 2024 SMK Teknologi Informatika. All rights reserved.') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="footer_bg_color" class="block text-sm font-medium text-gray-700">Warna Background Footer</label>
                        <input type="color" name="footer_bg_color" id="footer_bg_color"
                            value="{{ old('footer_bg_color', $settings['footer_bg_color'] ?? '#1e293b') }}"
                            class="mt-1 block w-20 h-10 rounded border border-gray-300">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="footer_show_map" value="1"
                                   {{ ($settings['footer_show_map'] ?? false) ? 'checked' : '' }}
                                   class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Tampilkan Peta Lokasi</span>
                        </label>
                    </div>

                    <div>
                        <label for="google_maps_embed" class="block text-sm font-medium text-gray-700">Google Maps Embed URL</label>
                        <textarea name="google_maps_embed" id="google_maps_embed" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://www.google.com/maps/embed?pb=...">{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">URL embed dari Google Maps</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Tab -->
        <div x-show="activeTab === 'social'" class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">📱</span>
                Media Sosial & Platform Digital
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="social_facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" name="social_facebook" id="social_facebook"
                            value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://facebook.com/sekolah">
                    </div>

                    <div>
                        <label for="social_instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" name="social_instagram" id="social_instagram"
                            value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://instagram.com/sekolah">
                    </div>

                    <div>
                        <label for="social_youtube" class="block text-sm font-medium text-gray-700">YouTube</label>
                        <input type="url" name="social_youtube" id="social_youtube"
                            value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://youtube.com/c/sekolah">
                    </div>

                    <div>
                        <label for="social_twitter" class="block text-sm font-medium text-gray-700">Twitter/X</label>
                        <input type="url" name="social_twitter" id="social_twitter"
                            value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://twitter.com/sekolah">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="social_tiktok" class="block text-sm font-medium text-gray-700">TikTok</label>
                        <input type="url" name="social_tiktok" id="social_tiktok"
                            value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://tiktok.com/@sekolah">
                    </div>

                    <div>
                        <label for="social_linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
                        <input type="url" name="social_linkedin" id="social_linkedin"
                            value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="https://linkedin.com/school/sekolah">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="social_show_in_header" value="1"
                                   {{ ($settings['social_show_in_header'] ?? false) ? 'checked' : '' }}
                                   class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Tampilkan di Header</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="social_show_in_footer" value="1"
                                   {{ ($settings['social_show_in_footer'] ?? true) ? 'checked' : '' }}
                                   class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Tampilkan di Footer</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end space-x-3 bg-gray-50 px-6 py-4 rounded-lg">
            <button type="button" onclick="window.location.reload()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Reset
            </button>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                💾 Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
// Auto-update hex values when color picker changes
document.getElementById('primary_color').addEventListener('input', function(e) {
    document.querySelector('input[name="primary_color_hex"]').value = e.target.value;
});
document.getElementById('secondary_color').addEventListener('input', function(e) {
    document.querySelector('input[name="secondary_color_hex"]').value = e.target.value;
});
document.getElementById('accent_color').addEventListener('input', function(e) {
    document.querySelector('input[name="accent_color_hex"]').value = e.target.value;
});
</script>
                            <input type="text" name="contact_phone" id="contact_phone"
                                value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="contact_address" id="contact_address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Theme Settings -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Theme Settings</h3>

                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                            <input type="color" name="primary_color" id="primary_color"
                                value="{{ old('primary_color', $settings['primary_color'] ?? '#3B82F6') }}"
                                class="mt-1 block w-20 h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
                            <input type="color" name="secondary_color" id="secondary_color"
                                value="{{ old('secondary_color', $settings['secondary_color'] ?? '#10B981') }}"
                                class="mt-1 block w-20 h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-700">Accent Color</label>
                            <input type="color" name="accent_color" id="accent_color"
                                value="{{ old('accent_color', $settings['accent_color'] ?? '#F59E0B') }}"
                                class="mt-1 block w-20 h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="font_family" class="block text-sm font-medium text-gray-700">Font Family</label>
                            <select name="font_family" id="font_family"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Inter" {{ ($settings['font_family'] ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Roboto" {{ ($settings['font_family'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ ($settings['font_family'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Lato" {{ ($settings['font_family'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                            </select>
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['logo']) && $settings['logo'])
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Current Logo" class="h-16 w-auto">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                            <input type="file" name="favicon" id="favicon" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['favicon']) && $settings['favicon'])
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Current Favicon" class="h-8 w-auto">
                                </div>
                            @endif
                        </div>

                        <!-- Social Media -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Social Media</h4>

                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <input type="url" name="facebook_url" id="facebook_url"
                                value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                            <input type="url" name="instagram_url" id="instagram_url"
                                value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                            <input type="url" name="youtube_url" id="youtube_url"
                                value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-6 border-t">
                    <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
