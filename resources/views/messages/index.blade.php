@extends('layouts.mainlayout')

@section('content')
<div class="container-fluid px-3 px-md-5 py-4">
    <div class="row g-0" style="min-height: 80vh;">
        <!-- Left: Chat List -->
        <div class="col-12 col-lg-4">
            <div class="chat-sidebar h-100" style="background: rgba(26, 26, 46, 0.95); border-radius: 20px 0 0 20px; border: 1px solid rgba(106, 56, 194, 0.3);">
                <!-- Header -->
                <div class="p-4 border-bottom" style="border-color: rgba(106, 56, 194, 0.3) !important;">
                    <h4 class="fw-bold mb-0" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        💬 Messages
                    </h4>
                </div>
                
                <!-- Chat List -->
                <div class="chat-list" style="max-height: calc(80vh - 80px); overflow-y: auto;">
                    @if(isset($chats) && $chats->count() > 0)
                        @foreach($chats as $chat)
                            @php
                                $otherUser = $chat->user_id === auth()->id() 
                                    ? ($chat->messages->first()?->user ?? null)
                                    : $chat->user;
                            @endphp
                            <a href="{{ route('messages.show', $chat->id) }}" 
                               class="chat-item d-flex align-items-center p-3 text-decoration-none transition-all {{ request()->route('chat') && request()->route('chat')->id == $chat->id ? 'active' : '' }}"
                               style="border-bottom: 1px solid rgba(106, 56, 194, 0.2);">
                                <!-- Avatar -->
                                <div class="avatar-circle me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(106, 56, 194, 0.4);">
                                    <span class="text-white fw-bold" style="font-size: 1.2rem;">{{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-truncate" style="color: var(--soft-lilac);">{{ $otherUser->name ?? 'Unknown' }}</span>
                                        <small style="color: rgba(200, 162, 200, 0.6); font-size: 0.75rem;">
                                            {{ $chat->messages->last()?->created_at?->diffForHumans(null, true, true) ?? '' }}
                                        </small>
                                    </div>
                                    <p class="mb-0 text-truncate" style="color: rgba(200, 162, 200, 0.5); font-size: 0.85rem;">
                                        {{ Str::limit($chat->messages->last()?->content ?? 'No messages yet', 40) }}
                                    </p>
                                </div>
                                
                                <!-- Unread Badge -->
                                @php
                                    $unreadCount = $chat->messages->where('user_id', '!=', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge rounded-pill ms-2" style="background: linear-gradient(135deg, #FF3CAC 0%, #FF6B9D 100%); font-size: 0.7rem;">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-5 px-4">
                            <div class="mb-4" style="opacity: 0.5;">
                                <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--soft-lilac);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h5 style="color: var(--soft-lilac);">No conversations yet</h5>
                            <p style="color: rgba(200, 162, 200, 0.5);">Start chatting with a vendor!</p>
                            <a href="{{ route('items.index') }}" class="btn btn-sm mt-2" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); color: white; border: none;">
                                Browse Items
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Placeholder (no selected chat in index) -->
        <div class="col-12 col-lg-8">
            <div class="chat-placeholder h-100 d-flex align-items-center justify-content-center" style="background: rgba(13, 13, 13, 0.95); border-radius: 0 20px 20px 0; border: 1px solid rgba(106, 56, 194, 0.3); border-left: none;">
                <div class="text-center px-4">
                    <div class="mb-4" style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, rgba(106, 56, 194, 0.2) 0%, rgba(255, 60, 172, 0.2) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg width="60" height="60" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--soft-lilac);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: var(--soft-lilac);">Select a conversation</h4>
                    <p style="color: rgba(200, 162, 200, 0.5);">Choose a chat from the list to start messaging</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-item {
        transition: all 0.2s ease;
    }
    .chat-item:hover {
        background: rgba(106, 56, 194, 0.15) !important;
    }
    .chat-item.active {
        background: rgba(106, 56, 194, 0.2) !important;
        border-left: 3px solid #FF3CAC !important;
    }
    
    /* Custom Scrollbar */
    .chat-list::-webkit-scrollbar {
        width: 6px;
    }
    .chat-list::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.2);
    }
    .chat-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);
        border-radius: 3px;
    }
    
    @media (max-width: 991.98px) {
        .chat-sidebar {
            border-radius: 20px 20px 0 0 !important;
        }
        .chat-placeholder {
            border-radius: 0 0 20px 20px !important;
            border-left: 1px solid rgba(106, 56, 194, 0.3) !important;
            border-top: none !important;
        }
    }
</style>
@endsection
