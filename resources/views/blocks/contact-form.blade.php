<div class="py-12 bg-white" id="{{ $blockId }}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($settings['title']) && $settings['title'])
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">{{ $settings['title'] }}</h2>
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Default fields if no custom fields defined -->
            @if(empty($settings['fields']))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Your full name">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="your.email@example.com">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject
                    </label>
                    <input type="text" 
                           id="subject" 
                           name="subject"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Message subject">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message" 
                              name="message" 
                              rows="6" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Your message here..."></textarea>
                </div>
            @else
                <!-- Custom fields -->
                @foreach($settings['fields'] as $field)
                    <div class="{{ $field['width'] ?? 'full' === 'half' ? 'md:w-1/2' : 'w-full' }}">
                        <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field['label'] }}
                            @if($field['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($field['type'] === 'textarea')
                            <textarea id="{{ $field['name'] }}" 
                                      name="{{ $field['name'] }}"
                                      rows="{{ $field['rows'] ?? 4 }}"
                                      {{ $field['required'] ?? false ? 'required' : '' }}
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="{{ $field['placeholder'] ?? '' }}"></textarea>
                        @elseif($field['type'] === 'select')
                            <select id="{{ $field['name'] }}" 
                                    name="{{ $field['name'] }}"
                                    {{ $field['required'] ?? false ? 'required' : '' }}
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select an option</option>
                                @foreach($field['options'] ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field['type'] ?? 'text' }}" 
                                   id="{{ $field['name'] }}" 
                                   name="{{ $field['name'] }}"
                                   {{ $field['required'] ?? false ? 'required' : '' }}
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ $field['placeholder'] ?? '' }}">
                        @endif
                    </div>
                @endforeach
            @endif

            <div class="text-center">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ $settings['submit_text'] ?? 'Send Message' }}
                </button>
            </div>
        </form>

        @if(session('success'))
            <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>