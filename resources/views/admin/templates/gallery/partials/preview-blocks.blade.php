@php /* Reusable block rendering for gallery live preview (with sample + real fallback arrays) */ @endphp
@forelse($sectionBlocks as $block)
    @php
        $type = $block['type'] ?? null;
        $data = $block['data'] ?? ($block['content'] ?? []);
        if($type==='card-grid' && empty($data['items'])){
            $data['items'] = [
                ['title'=>'Contoh 1','description'=>'Deskripsi contoh'],
                ['title'=>'Contoh 2','description'=>'Deskripsi contoh'],
                ['title'=>'Contoh 3','description'=>'Deskripsi contoh'],
            ];
        }
        if($type==='stats' && empty($data['items'])){
            $data['items'] = [
                ['label'=>'Sample','value'=>100],
                ['label'=>'Demo','value'=>200],
            ];
        }
        $componentPath = 'components.blocks.' . str_replace('_','-',$type);
        $showSamplePosts = $useSample && isset($data['_sample_posts']);
        $showSampleEvents = $useSample && isset($data['_sample_events']);
        $showSampleGalleries = $useSample && isset($data['_sample_galleries']);
        $showRealPosts = !$useSample && isset($data['_real_posts']);
        $showRealEvents = !$useSample && isset($data['_real_events']);
        $showRealGalleries = !$useSample && isset($data['_real_galleries']);
    @endphp
    <div class="relative group border rounded-md p-4 hover:shadow">
        <span class="absolute -top-2 -left-2 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded shadow">{{ $type ?? 'block' }}</span>
        @if($showRealPosts)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Berita Terbaru' }} <span class="ml-2 text-[10px] bg-green-600 text-white px-2 py-0.5 rounded">REAL</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_real_posts'] as $p)
                        <div class="border rounded p-3 bg-white">
                            <p class="text-xs text-gray-500 mb-1">{{ $p['date'] }}</p>
                            <p class="font-medium mb-1">{{ $p['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $p['excerpt'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($showRealEvents)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Agenda Mendatang' }} <span class="ml-2 text-[10px] bg-green-600 text-white px-2 py-0.5 rounded">REAL</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_real_events'] as $e)
                        <div class="border rounded p-3 bg-white">
                            <p class="text-xs text-gray-500 mb-1">{{ $e['date'] }}</p>
                            <p class="font-medium mb-1">{{ $e['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $e['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($showRealGalleries)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Galeri Kegiatan' }} <span class="ml-2 text-[10px] bg-green-600 text-white px-2 py-0.5 rounded">REAL</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_real_galleries'] as $g)
                        <div class="border rounded p-3 bg-white">
                            <p class="font-medium mb-1">{{ $g['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $g['count'] }} foto</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($showSamplePosts)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Berita Terbaru' }} <span class="ml-2 text-[10px] bg-yellow-500 text-white px-2 py-0.5 rounded">SAMPLE</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_sample_posts'] as $p)
                        <div class="border rounded p-3 bg-white">
                            <p class="text-xs text-gray-500 mb-1">{{ $p['date'] }}</p>
                            <p class="font-medium mb-1">{{ $p['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $p['excerpt'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($showSampleEvents)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Agenda Mendatang' }} <span class="ml-2 text-[10px] bg-yellow-500 text-white px-2 py-0.5 rounded">SAMPLE</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_sample_events'] as $e)
                        <div class="border rounded p-3 bg-white">
                            <p class="text-xs text-gray-500 mb-1">{{ $e['date'] }}</p>
                            <p class="font-medium mb-1">{{ $e['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $e['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($showSampleGalleries)
            <div class="bg-gray-50 p-4 rounded">
                <h4 class="font-semibold mb-4 text-lg">{{ $data['title'] ?? 'Galeri Kegiatan' }} <span class="ml-2 text-[10px] bg-yellow-500 text-white px-2 py-0.5 rounded">SAMPLE</span></h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($data['_sample_galleries'] as $g)
                        <div class="border rounded p-3 bg-white">
                            <p class="font-medium mb-1">{{ $g['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $g['count'] }} foto</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($type && view()->exists($componentPath))
            @php $fakeBlockModel = (object)['type'=>$type,'content'=>$data,'settings'=>[], 'style_settings'=>[]]; @endphp
            @include($componentPath, ['block'=>$fakeBlockModel,'content'=>$data,'settings'=>[], 'style_settings'=>[]])
        @else
            <div class="text-xs text-gray-500">(Preview renderer belum mendukung tipe: {{ $type ?? 'unknown' }})</div>
        @endif
    </div>
@empty
    <p class="text-xs text-gray-400">(Section tanpa block)</p>
@endforelse
