<x-dashboard-layout>
    <x-slot name="title">Chat - {{ $otherUser->name ?? 'Messages' }}</x-slot>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Chat List Sidebar (visible on desktop) -->
            <div class="col-lg-4 border-end d-none d-lg-block">
                <div class="p-3 border-bottom bg-light">
                    <h3 class="h5 mb-3">Messages</h3>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search conversations...">
                        <span class="input-group-text">🔍</span>
                    </div>
                </div>

                <div class="chat-list" style="max-height: calc(100vh - 220px); overflow-y: auto;">
                    @foreach($allChats as $c)
                        @php
                            $lastMsg = $c->messages()->latest()->first();
                            $user = $c->user_id === auth()->id() ? ($lastMsg ? $lastMsg->user : null) : $c->user;
                        @endphp
                        <a href="{{ route('messages.show', $c->id) }}" 
                           class="chat-item d-block p-3 border-bottom text-decoration-none {{ $c->id === $chat->id ? 'bg-light' : '' }} hover-bg-light">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <span class="text-white fw-bold small">{{ $user ? strtoupper(substr($user->name, 0, 1)) : '?' }}</span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold small">{{ $user->name ?? 'Unknown' }}</span>
                                        <small class="text-muted">{{ $lastMsg ? $lastMsg->created_at->format('H:i') : '' }}</small>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate">{{ $lastMsg ? Str::limit($lastMsg->content, 35) : '' }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Chat Window -->
            <div class="col-12 col-lg-8 d-flex flex-column" style="height: calc(100vh - 120px);">
                <!-- Chat Header -->
                <div class="p-3 border-bottom bg-white d-flex align-items-center gap-3 sticky-top">
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary d-lg-none">←</a>
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px;">
                        @if($otherUser && $otherUser->photo_url)
                            <img src="{{ $otherUser->photo_url }}" alt="{{ $otherUser->name ?? 'User' }}" class="rounded-circle w-100 h-100 object-fit-cover">
                        @else
                            <span class="text-white fw-bold">{{ $otherUser ? strtoupper(substr($otherUser->name, 0, 1)) : '?' }}</span>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $otherUser->name ?? 'Unknown User' }}</h6>
                        <small class="text-muted">{{ $otherUser && $otherUser->vendor ? $otherUser->vendor->vendor_name : 'Customer' }}</small>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="flex-grow-1 p-3 overflow-auto" id="messages-container" style="background: #f8f9fa;">
                    <div id="messages-list">
                        @forelse($messages as $message)
                            <div class="mb-3 d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="message-bubble {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}" 
                                     style="max-width: 70%;">
                                    <div class="p-2 rounded {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}">
                                        <p class="mb-1 small">{{ $message->content }}</p>
                                        <small class="opacity-75" style="font-size: 0.7rem;">{{ $message->created_at->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <p>No messages yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-3 border-top bg-white">
                    <form id="send-message-form" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                        <input type="text" 
                               name="content" 
                               id="message-input" 
                               class="form-control" 
                               placeholder="Type a message..." 
                               required 
                               autocomplete="off">
                        <button type="submit" class="btn btn-primary" id="send-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover { background-color: rgba(0,0,0,0.03) !important; }
        .message-bubble { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        (function() {
            const messagesContainer = document.getElementById('messages-container');
            const messagesList = document.getElementById('messages-list');
            const sendForm = document.getElementById('send-message-form');
            const messageInput = document.getElementById('message-input');
            const chatId = {{ $chat->id }};

            // Scroll to bottom on load
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            scrollToBottom();

            // Send message via AJAX
            sendForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const content = messageInput.value.trim();
                if (!content) return;

                try {
                    const response = await fetch('{{ route("messages.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ chat_id: chatId, content: content })
                    });

                    const data = await response.json();
                    if (data.success) {
                        messageInput.value = '';
                        loadMessages();
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                }
            });

            // Poll for new messages every 2 seconds
            async function loadMessages() {
                try {
                    const response = await fetch(`{{ route('messages.fetch', $chat->id) }}`);
                    const data = await response.json();
                    
                    if (data.messages) {
                        messagesList.innerHTML = data.messages.map(msg => {
                            const isMine = msg.user_id === {{ auth()->id() }};
                            const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                            return `
                                <div class="mb-3 d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'}">
                                    <div class="message-bubble ${isMine ? 'sent' : 'received'}" style="max-width: 70%;">
                                        <div class="p-2 rounded ${isMine ? 'bg-primary text-white' : 'bg-white border'}">
                                            <p class="mb-1 small">${msg.content}</p>
                                            <small class="opacity-75" style="font-size: 0.7rem;">${time}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        scrollToBottom();
                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // Poll every 2 seconds
            setInterval(loadMessages, 2000);

            // Focus input
            messageInput.focus();
        })();
    </script>
</x-dashboard-layout>
