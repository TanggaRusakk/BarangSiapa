@php
    $reviews = $item->reviews ?? collect();
    $averageRating = $reviews->avg('rating') ?? 0;
    $reviewCount = $reviews->count();
@endphp

<!-- Review Summary -->
@if($reviewCount > 0)
    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background: rgba(106,56,194,0.05);">
        <div class="text-center">
            <div class="h2 fw-bold mb-0" style="color: #6A38C2;">{{ number_format($averageRating, 1) }}</div>
            <div class="d-flex gap-1 justify-content-center">
                @for($i = 1; $i <= 5; $i++)
                    <span style="color: {{ $i <= round($averageRating) ? '#FFD700' : '#ddd' }};">★</span>
                @endfor
            </div>
        </div>
        <div class="text-secondary">
            Based on {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
        </div>
    </div>
@endif

<!-- Review Form -->
@auth
    @php
        $userHasReviewed = $reviews->where('user_id', auth()->id())->isNotEmpty();
    @endphp
    
    @if(!$userHasReviewed)
        <div class="card mb-4" style="background: rgba(106,56,194,0.05); border: 1px solid rgba(106,56,194,0.2);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Write a Review</h6>
                <form action="{{ route('reviews.store', $item->id) }}" method="POST">
                    @csrf
                    
                    <!-- Star Rating -->
                    <div class="mb-3">
                        <label class="form-label">Your Rating</label>
                        <div class="d-flex gap-1" id="starRating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="btn btn-link p-0 star-btn" data-rating="{{ $i }}" style="font-size: 24px; color: #ddd; text-decoration: none;">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0" required>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-3">
                        <label for="comment" class="form-label">Your Review</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Share your experience with this product..." required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn" style="background: #6A38C2; color: white;">Submit Review</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-4">
            <svg class="d-inline-block me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            You have already reviewed this product.
        </div>
    @endif
@else
    <div class="text-center py-4 mb-4" style="background: rgba(106,56,194,0.05); border-radius: 8px;">
        <p class="text-secondary mb-2">Want to share your experience?</p>
        <a href="{{ route('login') }}" class="btn" style="background: #6A38C2; color: white;">Login to Write a Review</a>
    </div>
@endauth

<!-- Reviews List -->
@if($reviewCount > 0)
    <div class="d-flex flex-column gap-4">
        @foreach($reviews as $review)
            <div class="pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); color: white; font-weight: bold;">
                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                <div class="d-flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= ($review->rating ?? 0) ? '#FFD700' : '#ddd' }}; font-size: 14px;">★</span>
                                    @endfor
                                </div>
                            </div>
                            <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                        </div>

                        <p class="text-secondary mb-0">{{ $review->comment ?? 'No review content.' }}</p>

                        @auth
                            @if($review->user_id === auth()->id())
                                <div class="mt-2 d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editReview{{ $review->id }}">Edit</button>
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editReview{{ $review->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Review</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Rating</label>
                                                        <select name="rating" class="form-select" required>
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Comment</label>
                                                        <textarea name="comment" rows="3" class="form-control" required>{{ $review->comment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn" style="background: #6A38C2; color: white;">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <svg class="mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity: 0.5;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <p class="text-secondary mb-0">No reviews yet. Be the first to share your experience!</p>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('ratingInput');
    
    if (!starButtons.length || !ratingInput) return;

    starButtons.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            
            // Update star colors
            starButtons.forEach((star, i) => {
                star.style.color = i < rating ? '#FFD700' : '#ddd';
            });
        });

        btn.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            starButtons.forEach((star, i) => {
                star.style.color = i < rating ? '#FFD700' : '#ddd';
            });
        });

        btn.addEventListener('mouseout', function() {
            const currentRating = ratingInput.value;
            starButtons.forEach((star, i) => {
                star.style.color = i < currentRating ? '#FFD700' : '#ddd';
            });
        });
    });
});
</script>
@endpush
