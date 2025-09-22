<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">
                        {{ setting('site_name', 'School CMS') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if($mainMenu = menu('main'))
                        @foreach($mainMenu->items()->orderBy('order')->get() as $item)
                            <a href="{{ $item->url }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                                      {{ request()->is(trim($item->url, '/')) ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                {{ $item->title }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="sm:hidden">
                <button type="button" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                        id="mobile-menu-button">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            @if($mainMenu = menu('main'))
                @foreach($mainMenu->items()->orderBy('order')->get() as $item)
                    <a href="{{ $item->url }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                              {{ request()->is(trim($item->url, '/')) ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $item->title }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</nav>

<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});
</script>