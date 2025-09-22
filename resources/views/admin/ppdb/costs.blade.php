@extends('layouts.admin')

@section('title', 'PPDB Cost Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">PPDB Cost Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage cost items for academic year {{ $academic_year }}</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Back to Settings
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add New Cost Form -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add New Cost</h3>
                </div>
                <form action="{{ route('admin.ppdb.costs.store') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="item_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" name="item_name" id="item_name" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="2" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                            <input type="number" name="amount" id="amount" required min="0" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Select Category</option>
                                <option value="pendaftaran">Pendaftaran</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="perlengkapan">Perlengkapan</option>
                                <option value="layanan">Layanan</option>
                                @foreach($categories as $cat)
                                    @if(!in_array($cat, ['pendaftaran', 'pendidikan', 'perlengkapan', 'layanan']))
                                        <option value="{{ $cat }}">{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="0" min="0" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_mandatory" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Mandatory</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>

                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year" value="{{ $academic_year }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Add Cost Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cost Items List -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Cost Items</h3>
                </div>
                <div class="overflow-x-auto">
                    @if($costs->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($costs as $cost)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $cost->item_name }}</div>
                                        @if($cost->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($cost->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $cost->category) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($cost->amount, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col space-y-1">
                                            @if($cost->is_mandatory)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Wajib</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Opsional</span>
                                            @endif
                                            @if($cost->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900" onclick="editCost({{ $cost->id }}, '{{ $cost->item_name }}', '{{ $cost->description }}', {{ $cost->amount }}, '{{ $cost->category }}', {{ $cost->sort_order }}, {{ $cost->is_mandatory ? 'true' : 'false' }}, {{ $cost->is_active ? 'true' : 'false' }}, '{{ $cost->academic_year }}')">Edit</button>
                                            <form action="{{ route('admin.ppdb.costs.destroy', $cost) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this cost item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-center text-gray-500">
                            No cost items found. Add some items using the form on the left.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal (Simple inline editing placeholder) -->
<script>
function editCost(id, name, description, amount, category, sortOrder, isMandatory, isActive, academicYear) {
    // For now, just prefill the form with existing data
    // In a real implementation, you'd use a modal or dedicated edit page
    document.getElementById('item_name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('amount').value = amount;
    document.getElementById('category').value = category;
    document.getElementById('sort_order').value = sortOrder;
    document.getElementById('is_mandatory').checked = isMandatory;
    document.getElementById('is_active').checked = isActive;
    document.getElementById('academic_year').value = academicYear;
    
    // Change form action to update
    const form = document.querySelector('form');
    form.action = '{{ route("admin.ppdb.costs.store") }}'.replace('costs', 'costs/' + id);
    form.innerHTML += '<input type="hidden" name="_method" value="PUT">';
    
    // Scroll to form
    form.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection