<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- School Info -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">{{ setting('site_name', 'School CMS') }}</h3>
                <p class="text-gray-300 mb-4">{{ setting('site_description', 'A comprehensive school management system') }}</p>
                
                @if(setting('school_address'))
                    <div class="text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ setting('school_address') }}
                    </div>
                @endif
                
                @if(setting('school_phone'))
                    <div class="text-gray-300 mb-2">
                        <i class="fas fa-phone mr-2"></i>
                        {{ setting('school_phone') }}
                    </div>
                @endif
                
                @if(setting('school_email'))
                    <div class="text-gray-300">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ setting('school_email') }}
                    </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                    <li><a href="{{ route('pages.show', 'about') }}" class="text-gray-300 hover:text-white">About</a></li>
                    <li><a href="{{ route('posts.index') }}" class="text-gray-300 hover:text-white">News</a></li>
                    <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white">Events</a></li>
                    <li><a href="{{ route('galleries.index') }}" class="text-gray-300 hover:text-white">Gallery</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    
                    @if(setting('social_twitter'))
                        <a href="{{ setting('social_twitter') }}" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    
                    @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" class="text-gray-300 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    
                    @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" class="text-gray-300 hover:text-white">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
            <p>&copy; {{ date('Y') }} {{ setting('site_name', 'School CMS') }}. All rights reserved.</p>
        </div>
    </div>
</footer>