@php
    $userReview = $item->reviews->where('user_id', auth()->id() ?? -1)->first();
@endphp

<!-- Write Review Form (Only for Users) -->
@auth
    @if(auth()->user()->role === 'user')
        <div class="mb-8 p-6 bg-midnight-black bg-opacity-50 rounded-lg border border-royal-purple border-opacity-30">
            <h3 class="text-lg font-semibold text-white mb-4">{{ $userReview ? 'Edit Your Review' : 'Write a Review' }}</h3>

            <form action="{{ $userReview ? route('reviews.update', $userReview->id) : route('reviews.store') }}" method="POST" id="reviewForm">
                @csrf
                @if($userReview)
                    @method('PATCH')
                @endif
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">Rating</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn w-10 h-10 transition-transform hover:scale-110" data-rating="{{ $i }}">
                                <svg class="w-full h-full {{ $userReview && $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ $userReview->rating ?? 0 }}" required>
                    @error('rating')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-white font-semibold mb-2">Your Review</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full px-4 py-3 bg-midnight-black border border-royal-purple border-opacity-30 rounded-lg text-white placeholder-soft-lilac focus:border-neon-pink focus:outline-none focus:ring-2 focus:ring-neon-pink focus:ring-opacity-20" placeholder="Share your experience with this item..." required>{{ $userReview->comment ?? old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
                    @if($userReview)
                        <button type="button" onclick="if(confirm('Are you sure you want to delete your review?')) document.getElementById('deleteReviewForm').submit();" class="btn bg-red-600 hover:bg-red-700 text-white">Delete Review</button>
                    @endif
                </div>
            </form>

            @if($userReview)
                <form id="deleteReviewForm" action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    @endif
@else
    <div class="mb-4 p-4 bg-midnight-black bg-opacity-30 rounded text-center">
        <p class="text-soft-lilac mb-2">Want to share your experience?</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login to Write a Review</a>
    </div>
@endauth

<!-- Reviews List -->
@if($item->reviews->count() > 0)
    <div class="space-y-6">
        @foreach($item->reviews as $review)
            <div class="border-b border-royal-purple border-opacity-30 pb-6 last:border-0">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-royal-purple bg-opacity-30 flex items-center justify-center text-neon-pink font-bold">
                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-semibold text-white">{{ $review->user->name ?? 'Anonymous' }}</span>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs text-soft-lilac">{{ $review->created_at->diffForHumans() }}</span>
                        </div>

                        <p class="text-soft-lilac leading-relaxed">{{ $review->comment ?? 'No review content.' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8 text-soft-lilac">
        <svg class="w-8 h-8 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
        </svg>
        <p>No reviews yet. Be the first to review this item!</p>
    </div>
@endif
