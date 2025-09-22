<div class="py-12" id="{{ $blockId }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($settings['content']) && $settings['content'])
            <div class="prose prose-lg max-w-none">
                {!! $settings['content'] !!}
            </div>
        @else
            <div class="text-gray-500 italic text-center">
                No content available
            </div>
        @endif
    </div>
</div>