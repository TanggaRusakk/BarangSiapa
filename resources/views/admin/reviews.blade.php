<x-dashboard-layout>
    <x-slot name="title">Admin — Reviews</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Reviews</h3>
        <div class="space-y-3">
            @foreach($reviews as $review)
                <div class="p-4 bg-purple-900 bg-opacity-10 rounded">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-bold">{{ optional($review->user)->name }}</div>
                            <div class="text-sm text-soft-lilac">{{ optional($review->item)->item_name }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-secondary btn-sm" onclick="return confirm('Delete this review?')">Delete</button>
                        </form>
                    </div>
                    <p class="text-soft-lilac">{{ $review->review_text ?? '—' }}</p>
                    <div class="text-sm text-soft-lilac mt-2">{{ $review->created_at->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</x-dashboard-layout>