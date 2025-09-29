@if(!empty($isPreviewMode) && !empty($previewUserTemplate))
@php $draft = !empty($isDraftPreview); @endphp
@php $shared = !empty($isSharedPreview); @endphp
<div class="fixed top-0 left-0 right-0 z-50 {{ $shared ? 'bg-indigo-600' : ($draft ? 'bg-amber-600' : 'bg-yellow-500') }} text-black text-sm shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
        <div>
            <strong>
                @if($shared)
                    Shared Preview
                @elseif($draft)
                    Draft Preview
                @else
                    Preview Mode
                @endif
            :</strong> {{ $previewUserTemplate->name }}
            <span class="ml-2 opacity-80">
                @if($shared && $draft)
                    (Public link viewing draft)
                @elseif($shared)
                    (Public link – not logged in)
                @elseif($draft)
                    (Melihat draft belum dipublish)
                @else
                    (Perubahan belum aktif)
                @endif
            </span>
        </div>
        @if(!$shared)
            <form method="POST" action="{{ route('admin.templates.preview-stop') }}" class="ml-4">
                @csrf
                <button type="submit" class="bg-black/70 hover:bg-black text-white px-3 py-1 rounded">Keluar Preview</button>
            </form>
        @endif
    </div>
</div>
<div style="height:42px"></div>
@endif
