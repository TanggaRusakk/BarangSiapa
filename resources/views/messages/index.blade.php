<x-dashboard-layout>
    <x-slot name="title">Messages</x-slot>

    <div class="container-fluid">
        <div class="row">
            <!-- Chat List Sidebar -->
            <div class="col-12 col-lg-4 border-end">
                <div class="p-3 border-bottom">
                    <h3 class="h5 mb-3">Messages</h3>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search conversations..." id="search-chats">
                        <span class="input-group-text">🔍</span>
                    </div>
                </div>

                <div class="chat-list" id="chat-list" style="max-height: calc(100vh - 220px); overflow-y: auto;">
                    @forelse($chats as $chat)
                        @php
                            $lastMessage = $chat->messages()->latest()->first();
                            $otherUser = $chat->user_id === auth()->id() 
                                ? ($lastMessage ? $lastMessage->user : null) 
                                : $chat->user;
                            $isActive = request()->route('chat') && request()->route('chat')->id === $chat->id;
                        @endphp
                        
                        <a href="{{ route('messages.show', $chat->id) }}" 
                           class="chat-item d-block p-3 border-bottom text-decoration-none {{ $isActive ? 'bg-light' : '' }} hover-bg-light">
                            <div class="d-flex align-items-start gap-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px;">
                                        @php
                                            $avatarSrc = null;
                                            if ($otherUser && $otherUser->image_path && file_exists(public_path('images/profiles/' . $otherUser->image_path))) {
                                                $avatarSrc = asset('images/profiles/' . $otherUser->image_path);
                                            } else {
                                                $avatarSrc = file_exists(public_path('images/profiles/profile_placeholder.jpg')) ? asset('images/profiles/profile_placeholder.jpg') : asset('images/profiles/profile_placeholder.png');
                                            }
                                        @endphp
                                        @if($avatarSrc)
                                            <img src="{{ $avatarSrc }}" alt="{{ $otherUser->name ?? 'User' }}" class="rounded-circle w-100 h-100 object-fit-cover">
                                        @else
                                            <span class="text-white fw-bold">{{ $otherUser ? strtoupper(substr($otherUser->name, 0, 1)) : '?' }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Chat Info -->
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $otherUser->name ?? 'Unknown User' }}</h6>
                                        <small class="text-muted">{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}</small>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate">
                                        {{ $lastMessage ? Str::limit($lastMessage->content, 50) : 'No messages yet' }}
                                    </p>
                                    @if($lastMessage && !$lastMessage->is_read && $lastMessage->user_id !== auth()->id())
                                        <span class="badge bg-primary rounded-pill mt-1">New</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <svg class="mb-3" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p>No conversations yet</p>
                            <small>Start messaging vendors to buy or rent items!</small>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Empty State -->
            <div class="col-12 col-lg-8 d-flex align-items-center justify-content-center" style="min-height: 70vh;">
                <div class="text-center text-muted">
                    <svg class="mb-3" width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h5>Select a conversation</h5>
                    <p class="small">Choose a conversation from the list to start messaging</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover { background-color: rgba(0,0,0,0.03) !important; }
        .chat-item { transition: background-color 0.2s; }
    </style>
</x-dashboard-layout>
