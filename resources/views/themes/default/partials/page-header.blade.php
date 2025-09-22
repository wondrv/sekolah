@if(isset($pageTitle) || isset($pageBreadcrumb))
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            @if(isset($pageTitle))
                <h1 class="text-4xl font-bold mb-4">{{ $pageTitle }}</h1>
            @endif
            
            @if(isset($pageDescription))
                <p class="text-xl text-blue-100 mb-6">{{ $pageDescription }}</p>
            @endif
            
            @if(isset($pageBreadcrumb) && count($pageBreadcrumb) > 0)
                <nav class="text-sm">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-blue-100 hover:text-white">
                                <i class="fas fa-home mr-1"></i>
                                Home
                            </a>
                        </li>
                        @foreach($pageBreadcrumb as $crumb)
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-blue-300 mx-2"></i>
                                    @if(isset($crumb['url']))
                                        <a href="{{ $crumb['url'] }}" class="text-blue-100 hover:text-white">
                                            {{ $crumb['title'] }}
                                        </a>
                                    @else
                                        <span class="text-white">{{ $crumb['title'] }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
    </div>
</div>
@endif