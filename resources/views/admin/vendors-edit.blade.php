<x-dashboard-layout>
    <x-slot name="title">Edit Vendor</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.vendors.update', $vendor->id) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Vendor Name</label>
                <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">User (Vendor Owner)</label>
                <select name="user_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $vendor->user_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Location</label>
                <input type="text" name="location" value="{{ $vendor->location }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Description</label>
                <textarea name="vendor_description" rows="4" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">{{ $vendor->vendor_description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Logo Path</label>
                <input type="text" name="logo_path" value="{{ $vendor->logo_path }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.vendors') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>