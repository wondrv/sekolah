@props(['block'])

@switch($block->type)
    @case('hero')
        <x-blocks.hero :data="$block->data" />
        @break

    @case('card_grid')
        <x-blocks.card-grid :data="$block->data" />
        @break

    @case('rich_text')
        <x-blocks.rich-text :data="$block->data" />
        @break

    @case('stats')
        <x-blocks.stats :data="$block->data" />
        @break

    @case('cta_banner')
        <x-blocks.cta-banner :data="$block->data" />
        @break

    @case('gallery_teaser')
        <x-blocks.gallery-teaser :data="$block->data" />
        @break

    @case('events_teaser')
        <x-blocks.events-teaser :data="$block->data" />
        @break

    @case('posts_teaser')
        <x-blocks.posts-teaser :data="$block->data" />
        @break

    @case('announcements_teaser')
        <x-blocks.announcements-teaser :data="$block->data" />
        @break

    @default
        <div class="py-8 bg-gray-100 text-center">
            <p class="text-gray-500">Unknown block type: {{ $block->type }}</p>
        </div>
@endswitch
