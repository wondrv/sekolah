@extends('layouts.admin')

@section('title', 'Theme Settings')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 py-5 sm:p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Theme Settings
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Customize your website's appearance and branding
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                <button type="button" onclick="exportTheme()"
                        class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Theme
                </button>
                <button type="button" onclick="openImportModal()"
                        class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    Import Theme
                </button>
                <button type="button" onclick="resetTheme()"
                        class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset to Default
                </button>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.theme.update') }}" method="POST" class="mt-8 space-y-8">
    @csrf
    @method('PUT')

    <!-- Colors Section -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Colors</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Primary Color -->
                <div>
                    <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="primary_color" name="colors[primary]" 
                               value="{{ $settings['colors']['primary'] ?? '#3B82F6' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['primary'] ?? '#3B82F6' }}"
                               onchange="document.getElementById('primary_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Secondary Color -->
                <div>
                    <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="secondary_color" name="colors[secondary]" 
                               value="{{ $settings['colors']['secondary'] ?? '#10B981' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['secondary'] ?? '#10B981' }}"
                               onchange="document.getElementById('secondary_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Accent Color -->
                <div>
                    <label for="accent_color" class="block text-sm font-medium text-gray-700">Accent Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="accent_color" name="colors[accent]" 
                               value="{{ $settings['colors']['accent'] ?? '#F59E0B' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['accent'] ?? '#F59E0B' }}"
                               onchange="document.getElementById('accent_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Background Color -->
                <div>
                    <label for="background_color" class="block text-sm font-medium text-gray-700">Background Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="background_color" name="colors[background]" 
                               value="{{ $settings['colors']['background'] ?? '#FFFFFF' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['background'] ?? '#FFFFFF' }}"
                               onchange="document.getElementById('background_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Text Color -->
                <div>
                    <label for="text_color" class="block text-sm font-medium text-gray-700">Text Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="text_color" name="colors[text]" 
                               value="{{ $settings['colors']['text'] ?? '#111827' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['text'] ?? '#111827' }}"
                               onchange="document.getElementById('text_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Text Muted Color -->
                <div>
                    <label for="text_muted_color" class="block text-sm font-medium text-gray-700">Text Muted Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="text_muted_color" name="colors[text_muted]" 
                               value="{{ $settings['colors']['text_muted'] ?? '#6B7280' }}"
                               class="h-10 w-16 rounded-md border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['colors']['text_muted'] ?? '#6B7280' }}"
                               onchange="document.getElementById('text_muted_color').value = this.value"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Typography Section -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Typography</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Font Family -->
                <div>
                    <label for="font_family" class="block text-sm font-medium text-gray-700">Font Family</label>
                    <select id="font_family" name="typography[font_family]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Inter" {{ ($settings['typography']['font_family'] ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Roboto" {{ ($settings['typography']['font_family'] ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        <option value="Open Sans" {{ ($settings['typography']['font_family'] ?? '') === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        <option value="Lato" {{ ($settings['typography']['font_family'] ?? '') === 'Lato' ? 'selected' : '' }}>Lato</option>
                        <option value="Montserrat" {{ ($settings['typography']['font_family'] ?? '') === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                        <option value="Poppins" {{ ($settings['typography']['font_family'] ?? '') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                        <option value="Nunito" {{ ($settings['typography']['font_family'] ?? '') === 'Nunito' ? 'selected' : '' }}>Nunito</option>
                    </select>
                </div>

                <!-- Heading Font -->
                <div>
                    <label for="heading_font" class="block text-sm font-medium text-gray-700">Heading Font Family</label>
                    <select id="heading_font" name="typography[heading_font]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="" {{ ($settings['typography']['heading_font'] ?? '') === '' ? 'selected' : '' }}>Same as body font</option>
                        <option value="Inter" {{ ($settings['typography']['heading_font'] ?? '') === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Roboto" {{ ($settings['typography']['heading_font'] ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        <option value="Open Sans" {{ ($settings['typography']['heading_font'] ?? '') === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        <option value="Playfair Display" {{ ($settings['typography']['heading_font'] ?? '') === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                        <option value="Merriweather" {{ ($settings['typography']['heading_font'] ?? '') === 'Merriweather' ? 'selected' : '' }}>Merriweather</option>
                    </select>
                </div>

                <!-- Base Font Size -->
                <div>
                    <label for="font_size_base" class="block text-sm font-medium text-gray-700">Base Font Size</label>
                    <select id="font_size_base" name="typography[font_size_base]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="14px" {{ ($settings['typography']['font_size_base'] ?? '16px') === '14px' ? 'selected' : '' }}>14px</option>
                        <option value="16px" {{ ($settings['typography']['font_size_base'] ?? '16px') === '16px' ? 'selected' : '' }}>16px</option>
                        <option value="18px" {{ ($settings['typography']['font_size_base'] ?? '16px') === '18px' ? 'selected' : '' }}>18px</option>
                        <option value="20px" {{ ($settings['typography']['font_size_base'] ?? '16px') === '20px' ? 'selected' : '' }}>20px</option>
                    </select>
                </div>

                <!-- Line Height -->
                <div>
                    <label for="line_height" class="block text-sm font-medium text-gray-700">Line Height</label>
                    <select id="line_height" name="typography[line_height]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1.4" {{ ($settings['typography']['line_height'] ?? '1.6') === '1.4' ? 'selected' : '' }}>1.4</option>
                        <option value="1.5" {{ ($settings['typography']['line_height'] ?? '1.6') === '1.5' ? 'selected' : '' }}>1.5</option>
                        <option value="1.6" {{ ($settings['typography']['line_height'] ?? '1.6') === '1.6' ? 'selected' : '' }}>1.6</option>
                        <option value="1.7" {{ ($settings['typography']['line_height'] ?? '1.6') === '1.7' ? 'selected' : '' }}>1.7</option>
                        <option value="1.8" {{ ($settings['typography']['line_height'] ?? '1.6') === '1.8' ? 'selected' : '' }}>1.8</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacing Section -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Spacing & Layout</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Container Max Width -->
                <div>
                    <label for="container_max_width" class="block text-sm font-medium text-gray-700">Container Max Width</label>
                    <select id="container_max_width" name="spacing[container_max_width]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1200px" {{ ($settings['spacing']['container_max_width'] ?? '1200px') === '1200px' ? 'selected' : '' }}>1200px</option>
                        <option value="1140px" {{ ($settings['spacing']['container_max_width'] ?? '1200px') === '1140px' ? 'selected' : '' }}>1140px</option>
                        <option value="1320px" {{ ($settings['spacing']['container_max_width'] ?? '1200px') === '1320px' ? 'selected' : '' }}>1320px</option>
                        <option value="1400px" {{ ($settings['spacing']['container_max_width'] ?? '1200px') === '1400px' ? 'selected' : '' }}>1400px</option>
                        <option value="100%" {{ ($settings['spacing']['container_max_width'] ?? '1200px') === '100%' ? 'selected' : '' }}>Full Width</option>
                    </select>
                </div>

                <!-- Section Padding -->
                <div>
                    <label for="section_padding" class="block text-sm font-medium text-gray-700">Section Padding</label>
                    <select id="section_padding" name="spacing[section_padding]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="40px" {{ ($settings['spacing']['section_padding'] ?? '60px') === '40px' ? 'selected' : '' }}>40px</option>
                        <option value="60px" {{ ($settings['spacing']['section_padding'] ?? '60px') === '60px' ? 'selected' : '' }}>60px</option>
                        <option value="80px" {{ ($settings['spacing']['section_padding'] ?? '60px') === '80px' ? 'selected' : '' }}>80px</option>
                        <option value="100px" {{ ($settings['spacing']['section_padding'] ?? '60px') === '100px' ? 'selected' : '' }}>100px</option>
                    </select>
                </div>

                <!-- Border Radius -->
                <div>
                    <label for="border_radius" class="block text-sm font-medium text-gray-700">Border Radius</label>
                    <select id="border_radius" name="spacing[border_radius]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="0px" {{ ($settings['spacing']['border_radius'] ?? '8px') === '0px' ? 'selected' : '' }}>None (0px)</option>
                        <option value="4px" {{ ($settings['spacing']['border_radius'] ?? '8px') === '4px' ? 'selected' : '' }}>Small (4px)</option>
                        <option value="8px" {{ ($settings['spacing']['border_radius'] ?? '8px') === '8px' ? 'selected' : '' }}>Medium (8px)</option>
                        <option value="12px" {{ ($settings['spacing']['border_radius'] ?? '8px') === '12px' ? 'selected' : '' }}>Large (12px)</option>
                        <option value="16px" {{ ($settings['spacing']['border_radius'] ?? '8px') === '16px' ? 'selected' : '' }}>Extra Large (16px)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Section -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Navigation</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Navigation Style -->
                <div>
                    <label for="nav_style" class="block text-sm font-medium text-gray-700">Navigation Style</label>
                    <select id="nav_style" name="navigation[style]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="horizontal" {{ ($settings['navigation']['style'] ?? 'horizontal') === 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                        <option value="centered" {{ ($settings['navigation']['style'] ?? 'horizontal') === 'centered' ? 'selected' : '' }}>Centered</option>
                        <option value="justified" {{ ($settings['navigation']['style'] ?? 'horizontal') === 'justified' ? 'selected' : '' }}>Justified</option>
                    </select>
                </div>

                <!-- Navigation Position -->
                <div>
                    <label for="nav_position" class="block text-sm font-medium text-gray-700">Navigation Position</label>
                    <select id="nav_position" name="navigation[position]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="static" {{ ($settings['navigation']['position'] ?? 'static') === 'static' ? 'selected' : '' }}>Static</option>
                        <option value="sticky" {{ ($settings['navigation']['position'] ?? 'static') === 'sticky' ? 'selected' : '' }}>Sticky</option>
                        <option value="fixed" {{ ($settings['navigation']['position'] ?? 'static') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
        <button type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Save Theme Settings
        </button>
    </div>
</form>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeImportModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form action="{{ route('admin.theme.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Import Theme</h3>
                    <div class="mt-4">
                        <label for="theme_file" class="block text-sm font-medium text-gray-700">Theme File (JSON)</label>
                        <input type="file" id="theme_file" name="theme_file" accept=".json" required
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeImportModal()"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                        Import Theme
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

function exportTheme() {
    window.location.href = '{{ route("admin.theme.export") }}';
}

function resetTheme() {
    if (confirm('Are you sure you want to reset all theme settings to default values? This action cannot be undone.')) {
        fetch('{{ route("admin.theme.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Something went wrong'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while resetting the theme');
        });
    }
}

// Color picker sync
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[type="color"]');
    
    colorInputs.forEach(colorInput => {
        const textInput = colorInput.nextElementSibling;
        
        colorInput.addEventListener('change', function() {
            textInput.value = this.value;
        });
        
        textInput.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.value = this.value;
            }
        });
    });
});

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImportModal();
    }
});
</script>
@endpush