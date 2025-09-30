{{-- Deprecated variant: prefer using rich_text (underscore) component mapping. Retained for backward compatibility with imported templates using "rich-text" type. --}}
@props(['block', 'content', 'settings', 'style_settings'])

@php
    // Normalize incoming arrays
    $base = is_array($content ?? null) ? $content : [];
    $settingsArr = is_array($settings ?? null) ? $settings : [];
    $styleArr = is_array($style_settings ?? null) ? $style_settings : [];
    $data = array_merge($base, $settingsArr, $styleArr);

    // Accept 'html' as synonym for 'content'
    if (!isset($data['content']) && isset($data['html'])) {
        $data['content'] = $data['html'];
    }

    // Fallback: sometimes nested like content => ['html' => ...]
    if (!isset($data['content']) && isset($base['content']['html'])) {
        $data['content'] = $base['content']['html'];
    }

    $rawHtml = $data['content'] ?? '';

    // Detect full-section / raw layout HTML (Bootstrap style or identified by certain tags)
    $isFullSection = false;
    if (is_string($rawHtml)) {
        $needles = ['<nav', 'id="about"', 'id="news"', 'id="program"', '<footer', 'class="container'];
        foreach ($needles as $n) {
            if (stripos($rawHtml, $n) !== false) { $isFullSection = true; break; }
        }
    }

    $alignment = $data['text_align'] ?? 'text-left';
    $wrapperBg = $data['background_color'] ?? 'bg-white';
    $maxWidth = $data['max_width'] ?? '4xl';
@endphp

@if(!empty($rawHtml))
    @if($isFullSection)
        {{-- Render raw HTML without Tailwind wrapper to avoid nesting issues (navbar, multi-container, etc.) --}}
        <div class="rich-text-raw-section">
            {!! $rawHtml !!}
        </div>
    @else
        <section class="rich-text-block py-16 {{ $wrapperBg }}">
          <div class="container mx-auto px-4">
            <div class="max-w-{{ $maxWidth }} mx-auto">
              @if(isset($data['title']))
                <h2 class="text-3xl md:text-4xl font-bold mb-8 {{ $alignment }}">
                  {{ $data['title'] }}
                </h2>
              @endif

              <div class="prose prose-lg max-w-none {{ $alignment }}">
                {!! $rawHtml !!}
              </div>
            </div>
          </div>
        </section>
    @endif
@else
    <div class="p-4 text-gray-500 text-sm border border-dashed border-gray-300 rounded">
        (Rich Text) No content found. Keys: {{ implode(', ', array_keys($data)) }}
    </div>
@endif
