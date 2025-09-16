@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Site Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Site Information</h3>

                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                            <input type="text" name="site_name" id="site_name"
                                value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                            <textarea name="site_description" id="site_description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        </div>

                        <div>
                            <label for="site_tagline" class="block text-sm font-medium text-gray-700">Site Tagline</label>
                            <input type="text" name="site_tagline" id="site_tagline"
                                value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Mencetak Generasi Digital Unggul">
                        </div>

                        <div>
                            <label for="site_keywords" class="block text-sm font-medium text-gray-700">SEO Keywords</label>
                            <input type="text" name="site_keywords" id="site_keywords"
                                value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="keyword1, keyword2, keyword3">
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" name="contact_email" id="contact_email"
                                value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
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

                        <!-- Header / Navbar Settings -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Header / Navbar</h4>

                        <div>
                            <label for="header_logo_position" class="block text-sm font-medium text-gray-700">Logo Position</label>
                            <select name="header_logo_position" id="header_logo_position"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="left" {{ ($settings['header_logo_position'] ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                <option value="center" {{ ($settings['header_logo_position'] ?? 'left') === 'center' ? 'selected' : '' }}>Center</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="header_sticky" value="0">
                            <input type="checkbox" name="header_sticky" id="header_sticky" value="1" class="rounded"
                                   {{ ($settings['header_sticky'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="header_sticky" class="text-sm">Sticky header (stays on scroll)</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="header_transparent" value="0">
                            <input type="checkbox" name="header_transparent" id="header_transparent" value="1" class="rounded"
                                   {{ ($settings['header_transparent'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="header_transparent" class="text-sm">Transparent header</label>
                        </div>

                        <div>
                            <label for="header_bg_color" class="block text-sm font-medium text-gray-700">Header Background Color</label>
                            <input type="color" name="header_bg_color" id="header_bg_color"
                                value="{{ old('header_bg_color', $settings['header_bg_color'] ?? '#ffffff') }}"
                                class="mt-1 block w-20 h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="header_text_color" class="block text-sm font-medium text-gray-700">Header Text Color</label>
                            <input type="color" name="header_text_color" id="header_text_color"
                                value="{{ old('header_text_color', $settings['header_text_color'] ?? '#000000') }}"
                                class="mt-1 block w-20 h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="social_show_in_header" value="0">
                            <input type="checkbox" name="social_show_in_header" id="social_show_in_header" value="1" class="rounded"
                                   {{ ($settings['social_show_in_header'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="social_show_in_header" class="text-sm">Show social icons in header</label>
                        </div>

                        <!-- Social Media -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Social Media</h4>

                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <input type="url" name="facebook_url" id="facebook_url"
                                value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- PPDB -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">PPDB</h4>
                        <div>
                            <label for="ppdb_brochure" class="block text-sm font-medium text-gray-700">Upload Brosur (PDF)</label>
                            <input type="file" name="ppdb_brochure" id="ppdb_brochure" accept="application/pdf"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['ppdb_brochure']) && $settings['ppdb_brochure'])
                                <p class="text-xs text-gray-500 mt-1">Brosur saat ini: <a class="text-blue-600 hover:underline" target="_blank" href="{{ asset('storage/' . $settings['ppdb_brochure']) }}">Lihat/Unduh</a></p>
                            @endif
                        </div>
                        <div>
                            <label for="ppdb_brochure_url" class="block text-sm font-medium text-gray-700">Atau URL Brosur</label>
                            <input type="url" name="ppdb_brochure_url" id="ppdb_brochure_url" value="{{ old('ppdb_brochure_url', $settings['ppdb_brochure_url'] ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="https://.../brosur.pdf">
                            <p class="text-xs text-gray-500 mt-1">Jika diisi, tombol unduh akan menggunakan URL ini.</p>
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

                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                            <input type="url" name="twitter_url" id="twitter_url"
                                value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="tiktok_url" class="block text-sm font-medium text-gray-700">TikTok URL</label>
                            <input type="url" name="tiktok_url" id="tiktok_url"
                                value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" id="linkedin_url"
                                value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Agenda & Kegiatan -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Agenda & Kegiatan</h4>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="agenda_show_on_home" value="0">
                            <input type="checkbox" name="agenda_show_on_home" id="agenda_show_on_home" value="1" class="rounded"
                                   {{ ($settings['agenda_show_on_home'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label for="agenda_show_on_home" class="text-sm">Tampilkan Agenda di Beranda</label>
                        </div>

                        <div>
                            <label for="agenda_items_home" class="block text-sm font-medium text-gray-700">Jumlah Agenda di Beranda</label>
                            <input type="number" min="1" max="12" name="agenda_items_home" id="agenda_items_home"
                                   value="{{ old('agenda_items_home', $settings['agenda_items_home'] ?? 4) }}"
                                   class="mt-1 block w-28 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="agenda_section_title" class="block text-sm font-medium text-gray-700">Judul Bagian Agenda</label>
                            <input type="text" name="agenda_section_title" id="agenda_section_title"
                                   value="{{ old('agenda_section_title', $settings['agenda_section_title'] ?? 'Agenda Terdekat') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mt-2">
                            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                Kelola Agenda & Kegiatan →
                            </a>
                        </div>

                        <!-- Berita / News -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Berita</h4>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="news_show_on_home" value="0">
                            <input type="checkbox" name="news_show_on_home" id="news_show_on_home" value="1" class="rounded"
                                   {{ ($settings['news_show_on_home'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label for="news_show_on_home" class="text-sm">Tampilkan Berita di Beranda</label>
                        </div>

                        <!-- Pengumuman / Announcements -->
                        <h4 class="text-md font-medium text-gray-900 border-b pb-1 mt-6">Pengumuman</h4>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="announcements_show_on_home" value="0">
                            <input type="checkbox" name="announcements_show_on_home" id="announcements_show_on_home" value="1" class="rounded"
                                   {{ ($settings['announcements_show_on_home'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label for="announcements_show_on_home" class="text-sm">Tampilkan Pengumuman di Beranda</label>
                        </div>

                        <div>
                            <label for="announcements_items_home" class="block text-sm font-medium text-gray-700">Jumlah Pengumuman di Beranda</label>
                            <input type="number" min="1" max="12" name="announcements_items_home" id="announcements_items_home"
                                   value="{{ old('announcements_items_home', $settings['announcements_items_home'] ?? 3) }}"
                                   class="mt-1 block w-28 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="announcements_section_title" class="block text-sm font-medium text-gray-700">Judul Bagian Pengumuman</label>
                            <input type="text" name="announcements_section_title" id="announcements_section_title"
                                   value="{{ old('announcements_section_title', $settings['announcements_section_title'] ?? 'Pengumuman') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="announcements_category_slug" class="block text-sm font-medium text-gray-700">Slug Kategori Pengumuman</label>
                            <input type="text" name="announcements_category_slug" id="announcements_category_slug"
                                   value="{{ old('announcements_category_slug', $settings['announcements_category_slug'] ?? 'pengumuman') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Misal: pengumuman">
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-500">Pastikan ada kategori dengan slug ini pada Berita.</p>
                                <form method="POST" action="{{ route('admin.settings.ensure_announcements_category') }}">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ old('announcements_category_slug', $settings['announcements_category_slug'] ?? 'pengumuman') }}">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                        Buat/cek kategori
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div>
                            <label for="news_items_home" class="block text-sm font-medium text-gray-700">Jumlah Berita di Beranda</label>
                            <input type="number" min="1" max="12" name="news_items_home" id="news_items_home"
                                   value="{{ old('news_items_home', $settings['news_items_home'] ?? 3) }}"
                                   class="mt-1 block w-28 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="news_section_title" class="block text-sm font-medium text-gray-700">Judul Bagian Berita</label>
                            <input type="text" name="news_section_title" id="news_section_title"
                                   value="{{ old('news_section_title', $settings['news_section_title'] ?? 'Berita Terbaru') }}"
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
