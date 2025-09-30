<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; background-color: #f3f4f6;">
    <!-- Force Flexbox Layout -->
    <div style="display: flex; min-height: 100vh; width: 100%;">

        <!-- SIDEBAR - FIXED LEFT -->
        <aside style="width: 256px; min-width: 256px; background-color: #36454F; box-shadow: 2px 0 4px rgba(0,0,0,0.1); display: flex; flex-direction: column;"
               x-data="{
                   content: {{ (request()->routeIs('admin.posts.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.events.*') || request()->routeIs('admin.galleries.*')) ? 'true' : 'false' }},
                   ppdb: {{ (request()->routeIs('ppdb.*')) ? 'true' : 'false' }},
                   school: {{ (request()->routeIs('admin.facilities.*') || request()->routeIs('admin.programs.*') || request()->routeIs('admin.achievements.*') || request()->routeIs('admin.testimonials.*')) ? 'true' : 'false' }},
                   templates: {{ (request()->routeIs('admin.templates.gallery.*') || request()->routeIs('admin.templates.my-templates*') || request()->routeIs('admin.templates.builder.*') || request()->routeIs('admin.templates.exports*')) ? 'true' : 'false' }},
                   advanced: {{ (request()->routeIs('admin.templates.index') || request()->routeIs('admin.templates.show') || request()->routeIs('admin.templates.edit') || request()->routeIs('admin.templates.create') || request()->routeIs('admin.template-assignments.*') || request()->routeIs('admin.theme.*') || request()->routeIs('admin.menus.*') || request()->routeIs('admin.settings.*')) ? 'true' : 'false' }},
                   communication: {{ (request()->routeIs('admin.messages.*')) ? 'true' : 'false' }}
               }">

            <!-- Sidebar Header -->
            <div style="padding: 1.5rem; border-bottom: 1px solid #4a5568; color: white;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.5rem; height: 2.5rem; background-color: #3b82f6; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 1.25rem;">🏫</span>
                    </div>
                    <div>
                        <h1 style="font-size: 1.125rem; font-weight: bold; margin: 0; color: white;">Admin Panel</h1>
                        <p style="font-size: 0.75rem; color: #a0aec0; margin: 0;">{{ auth()->user()?->name ?? 'Guest' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav style="flex: 1; padding: 1rem; overflow-y: auto;">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   style="display: flex; align-items: center; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; margin-bottom: 0.25rem; font-size: 0.9rem; {{ request()->routeIs('admin.dashboard') ? 'background-color: #3b82f6; color: white;' : 'color: #d1d5db;' }} transition: all 0.2s;">
                    <span style="margin-right: 0.5rem; font-size: 1rem;">📊</span>
                    Dashboard
                </a>

                <!-- Content Management -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="content = !content"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">📄</span>
                            Content Management
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="content ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="content" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('admin.posts.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.posts.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📝</span>
                            Berita & Artikel
                        </a>
                        <a href="{{ route('admin.pages.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.pages.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📄</span>
                            Halaman
                        </a>
                        <a href="{{ route('admin.events.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.events.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📅</span>
                            Agenda & Kalender
                        </a>
                        <a href="{{ route('admin.galleries.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.galleries.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🖼️</span>
                            Galeri
                        </a>
                    </div>
                </div>

                <!-- PPDB Management -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="ppdb = !ppdb"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">🎓</span>
                            PPDB Management
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="ppdb ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="ppdb" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('ppdb.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('ppdb.index') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📋</span>
                            Pengaturan PPDB
                        </a>
                        <a href="{{ route('ppdb.costs') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('ppdb.costs') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">💰</span>
                            Biaya Pendaftaran
                        </a>
                    </div>
                </div>

                <!-- School Features -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="school = !school"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">🏫</span>
                            School Features
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="school ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="school" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('admin.facilities.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.facilities.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🏫</span>
                            Fasilitas
                        </a>
                        <a href="{{ route('admin.programs.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.programs.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🎯</span>
                            Program Unggulan
                        </a>
                        <a href="{{ route('admin.achievements.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.achievements.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🏆</span>
                            Prestasi
                        </a>
                        <a href="{{ route('admin.testimonials.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.testimonials.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">💬</span>
                            Testimoni
                        </a>
                    </div>
                </div>

                <!-- Template System -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="templates = !templates"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">🎨</span>
                            Template System
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="templates ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="templates" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('admin.templates.gallery.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.templates.gallery.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🎨</span>
                            Template Gallery
                        </a>
                        <a href="{{ route('admin.templates.my-templates') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.templates.my-templates*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📝</span>
                            My Templates
                        </a>
                    </div>
                </div>

                <!-- HTML Validator -->
                <a href="{{ route('admin.html-validator.index') }}"
                   style="display: flex; align-items: center; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; margin-bottom: 0.25rem; font-size: 0.9rem; {{ request()->routeIs('admin.html-validator.*') ? 'background-color: #3b82f6; color: white;' : 'color: #d1d5db;' }}">
                    <span style="margin-right: 0.5rem; font-size: 1rem;">🔍</span>
                    HTML Validator
                </a>

                <!-- Advanced Features -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="advanced = !advanced"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">🛠️</span>
                            Advanced Features
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="advanced ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="advanced" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('admin.templates.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.templates.index') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🛠️</span>
                            Legacy Templates
                        </a>
                        <a href="{{ route('admin.template-assignments.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.template-assignments.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🔗</span>
                            Template Assignments
                        </a>
                        <a href="{{ route('admin.theme.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.theme.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🎭</span>
                            Theme Settings
                        </a>
                        <a href="{{ route('admin.menus.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.menus.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">🧭</span>
                            Menu Management
                        </a>
                        <a href="{{ route('admin.settings.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.settings.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">⚙️</span>
                            Settings
                        </a>
                    </div>
                </div>

                <!-- Communication -->
                <div style="margin-bottom: 0.25rem;">
                    <button @click="communication = !communication"
                            style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: none; border: none; color: #d1d5db; text-align: left; cursor: pointer; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 0.5rem; font-size: 1rem;">📧</span>
                            Communication
                        </div>
                        <span style="font-size: 0.75rem; transition: transform 0.2s; display: inline-block;" :style="communication ? 'transform: rotate(180deg)' : ''">▼</span>
                    </button>
                    <div x-show="communication" x-transition style="margin-left: 1rem; margin-top: 0.125rem;">
                        <a href="{{ route('admin.messages.index') }}" style="display: flex; align-items: center; padding: 0.375rem 0.5rem; border-radius: 0.375rem; text-decoration: none; color: #9ca3af; margin-bottom: 0.125rem; font-size: 0.85rem; {{ request()->routeIs('admin.messages.*') ? 'background-color: #3b82f6; color: white;' : '' }}">
                            <span style="margin-right: 0.375rem; font-size: 0.85rem;">📧</span>
                            Inbox Messages
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div style="padding: 1rem; border-top: 1px solid #4a5568; text-align: center;">
                <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">Admin Panel v1.0</p>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main style="flex: 1; display: flex; flex-direction: column; min-width: 0;">

            <!-- Top Header -->
            <header style="background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        @hasSection('header')
                            @yield('header')
                        @else
                            <h1 style="font-size: 1.5rem; font-weight: bold; color: #111827; margin: 0;">@yield('title', 'Admin Panel')</h1>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">@yield('subtitle', 'Manage your school\'s content and settings')</p>
                        @endif
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        @yield('header-actions')

                        <!-- Quick Template Switcher -->
                        @php
                            try {
                                $quickTemplates = \App\Models\UserTemplate::byUser()->orderByDesc('is_active')->orderBy('name')->get(['id','name','is_active']);
                            } catch (Exception $e) { $quickTemplates = collect(); }
                        @endphp
                        @if(isset($quickTemplates) && $quickTemplates->count() > 0)
                        <div x-data="{ open:false }" style="position: relative;">
                            <button @click="open=!open" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid {{ $quickTemplates->first(fn($t)=>$t->is_active) ? '#34d399' : '#d1d5db' }}; background-color: {{ $quickTemplates->first(fn($t)=>$t->is_active) ? '#ecfdf5' : '#f9fafb' }}; color: {{ $quickTemplates->first(fn($t)=>$t->is_active) ? '#047857' : '#374151' }}; cursor: pointer;">
                                🎨 <span>{{ optional($quickTemplates->first(fn($t)=>$t->is_active))->name ?? 'Pilih Template' }}</span>
                            </button>
                            <div x-show="open" @click.away="open=false" x-transition style="position: absolute; right: 0; margin-top: 0.5rem; width: 16rem; background-color: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 50; padding: 0.5rem;">
                                @foreach($quickTemplates as $qt)
                                    <div style="display: flex; align-items: center; justify-content: space-between; {{ $qt->is_active ? 'background-color: #ecfdf5; border: 1px solid #34d399; border-radius: 0.375rem; padding: 0.5rem;' : 'padding: 0.5rem; border-radius: 0.375rem;' }} margin-bottom: 0.25rem;">
                                        <div style="font-size: 0.75rem; font-weight: 500; max-width: 7.5rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; {{ $qt->is_active ? 'color: #047857;' : 'color: #374151;' }}" title="{{ $qt->name }}">
                                            {{ $qt->name }}
                                        </div>
                                        @if(!$qt->is_active)
                                            <form method="POST" action="{{ route('admin.templates.my-templates.activate', $qt->id) }}" style="display: inline;">
                                                @csrf
                                                <button style="font-size: 0.625rem; padding: 0.25rem 0.5rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">Aktifkan</button>
                                            </form>
                                        @else
                                            <span style="font-size: 0.625rem; padding: 0.125rem 0.5rem; border-radius: 0.25rem; background-color: #10b981; color: white;">Active</span>
                                        @endif
                                    </div>
                                @endforeach
                                <a href="{{ route('admin.templates.my-templates') }}" style="display: block; margin-top: 0.5rem; text-align: center; font-size: 0.6875rem; color: #3b82f6; text-decoration: none;">Kelola Template »</a>
                            </div>
                        </div>
                        @endif

                        <button onclick="window.open('{{ url('/') }}', '_blank')"
                                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background-color: #10b981; color: white; border: none; border-radius: 0.5rem; cursor: pointer;">
                            🌐 View Website
                        </button>
                        <form method="POST" action="{{ route('admin.cache.clear') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background-color: #f59e0b; color: white; border: none; border-radius: 0.5rem; cursor: pointer;">
                                🗑️ Clear Cache
                            </button>
                        </form>
                        <div style="font-size: 0.75rem; color: #6b7280;">
                            {{ \Carbon\Carbon::now()->format('M d, Y - H:i') }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div style="flex: 1; padding: 1.5rem; background-color: #f9fafb; overflow-y: auto;">
                @if (session('success'))
                    <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #d1fae5; border: 1px solid #34d399; color: #065f46; border-radius: 0.5rem; display: flex; align-items: center;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #fef2f2; border: 1px solid #f87171; color: #991b1b; border-radius: 0.5rem; display: flex; align-items: center;">
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #fef2f2; border: 1px solid #f87171; color: #991b1b; border-radius: 0.5rem;">
                        <div style="display: flex; align-items: flex-start;">
                            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p style="font-weight: 500; margin: 0 0 0.25rem 0;">Please fix the following errors:</p>
                                <ul style="margin: 0.25rem 0 0 1rem; list-style-type: disc; font-size: 0.875rem;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>
