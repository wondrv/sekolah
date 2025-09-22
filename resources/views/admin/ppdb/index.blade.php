@extends('layouts.admin')

@section('title', 'PPDB Settings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">PPDB Settings</h1>
        <p class="mt-2 text-sm text-gray-600">Manage PPDB brochure and cost settings</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Brochure Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Brochure Settings</h3>
            </div>
            <form action="{{ route('admin.ppdb.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="brochure_enabled" value="1" {{ $settings['brochure_enabled'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Enable Brochure Section</span>
                        </label>
                    </div>

                    <div>
                        <label for="brochure_title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="brochure_title" id="brochure_title" value="{{ $settings['brochure_title'] }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="brochure_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="brochure_description" id="brochure_description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ $settings['brochure_description'] }}</textarea>
                    </div>

                    <div>
                        <label for="brochure_file" class="block text-sm font-medium text-gray-700">Brochure File (PDF)</label>
                        <input type="file" name="brochure_file" id="brochure_file" accept=".pdf" 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($settings['brochure_file'])
                            <p class="mt-1 text-sm text-gray-500">Current: {{ basename($settings['brochure_file']) }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="brochure_format" class="block text-sm font-medium text-gray-700">Format</label>
                            <input type="text" name="brochure_format" id="brochure_format" value="{{ $settings['brochure_format'] }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="brochure_size" class="block text-sm font-medium text-gray-700">Size Display</label>
                            <input type="text" name="brochure_size" id="brochure_size" value="{{ $settings['brochure_size'] }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Cost Table Settings</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="costs_enabled" value="1" {{ $settings['costs_enabled'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Enable Cost Table</span>
                            </label>
                        </div>

                        <div>
                            <label for="costs_title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="costs_title" id="costs_title" value="{{ $settings['costs_title'] }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="costs_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="costs_description" id="costs_description" rows="2" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ $settings['costs_description'] }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Cost Items Preview -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Cost Items ({{ $academic_year }})</h3>
                <a href="{{ route('admin.ppdb.costs') }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                    Manage Costs
                </a>
            </div>
            <div class="p-6">
                @if($costs->count() > 0)
                    <div class="space-y-2">
                        @foreach($costs->take(5) as $cost)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div>
                                    <div class="font-medium">{{ $cost->item_name }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $cost->category)) }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold">Rp {{ number_format($cost->amount, 0, ',', '.') }}</div>
                                    @if($cost->is_mandatory)
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Wajib</span>
                                    @else
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Opsional</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if($costs->count() > 5)
                            <div class="text-center pt-2">
                                <span class="text-sm text-gray-500">and {{ $costs->count() - 5 }} more items...</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No cost items found. <a href="{{ route('admin.ppdb.costs') }}" class="text-blue-600 hover:text-blue-800">Add some costs</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection