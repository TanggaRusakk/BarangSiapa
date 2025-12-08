<x-dashboard-layout>
    <x-slot name="title">Create Ad</x-slot>

    <div class="card max-w-2xl">
        <form method="POST" action="{{ route('vendor.ads.store') }}">
            @csrf
            <div class="mb-3">
                <label class="input-label">Title</label>
                <input type="text" name="ad_title" class="text-input" required>
            </div>
            <div class="mb-3">
                <label class="input-label">Image URL</label>
                <input type="text" name="ad_image" class="text-input">
            </div>
            <div class="mb-3">
                <label class="input-label">Destination URL</label>
                <input type="url" name="ad_url" class="text-input">
            </div>
            <div class="flex gap-2">
                <button class="btn btn-primary">Create Ad</button>
                <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</x-dashboard-layout>
