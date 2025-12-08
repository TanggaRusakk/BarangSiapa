<x-dashboard-layout>
    <x-slot name="title">Create Advertisement</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.ads.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Ad Title</label>
                <input type="text" name="ad_title" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Vendor</label>
                <select name="vendor_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Start Date</label>
                <input type="date" name="ad_start_date" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <input type="date" name="ad_end_date" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Image Path</label>
                <input type="text" name="ad_image_path" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Description</label>
                <textarea name="ad_description" rows="4" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac"></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.ads') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>