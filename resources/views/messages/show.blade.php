<x-dashboard-layout>
    <x-slot name="title">Chat - {{ $otherUser->name ?? 'Messages' }}</x-slot>

    <div class="container-fluid p-0">
        <div class="row g-0" style="min-height: calc(100vh - 120px);">
            <!-- Chat List Sidebar (visible on desktop) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="chat-sidebar h-100" style="background: rgba(26, 26, 46, 0.95); border-right: 1px solid rgba(106, 56, 194, 0.3);">
                    <!-- Header -->
                    <div class="p-4" style="border-bottom: 1px solid rgba(106, 56, 194, 0.3);">
                        <h4 class="fw-bold mb-3" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            💬 Messages
                        </h4>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search conversations..." 
                                   style="background: rgba(45, 45, 65, 0.8); border: 1px solid rgba(106, 56, 194, 0.3); color: var(--soft-lilac); border-radius: 25px; padding: 10px 20px;">
                        </div>
                    </div>

                    <div class="chat-list" style="max-height: calc(100vh - 220px); overflow-y: auto;">
                        @foreach($allChats as $c)
                            @php
                                $lastMsg = $c->messages()->latest()->first();
                                // Determine the other participant
                                $chatUser = $c->user_id === auth()->id() ? $c->vendorUser : $c->user;
                            @endphp
                            <a href="{{ route('messages.show', $c->id) }}" 
                               class="chat-item d-flex align-items-center p-3 text-decoration-none {{ $c->id === $chat->id ? 'active' : '' }}"
                               style="border-bottom: 1px solid rgba(106, 56, 194, 0.2);">
                                <div class="avatar-circle me-3" style="width: 45px; height: 45px; min-width: 45px; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-white fw-bold">{{ $chatUser ? strtoupper(substr($chatUser->name, 0, 1)) : '?' }}</span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small text-truncate" style="color: var(--soft-lilac);">{{ $chatUser->name ?? 'Unknown' }}</span>
                                        <small style="color: rgba(200, 162, 200, 0.5); font-size: 0.7rem;">{{ $lastMsg ? $lastMsg->created_at->format('H:i') : '' }}</small>
                                    </div>
                                    <p class="mb-0 small text-truncate" style="color: rgba(200, 162, 200, 0.4);">{{ $lastMsg ? Str::limit($lastMsg->content, 30) : '' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Chat Window -->
            <div class="col-12 col-lg-8 d-flex flex-column" style="background: rgba(13, 13, 13, 0.95);">
                <!-- Chat Header -->
                <div class="p-3 d-flex align-items-center gap-3" style="background: rgba(26, 26, 46, 0.9); border-bottom: 1px solid rgba(106, 56, 194, 0.3);">
                    <a href="{{ route('messages.index') }}" class="btn btn-sm d-lg-none" style="background: rgba(106, 56, 194, 0.3); color: var(--soft-lilac); border: none;">←</a>
                    <div class="avatar-circle" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(106, 56, 194, 0.4);">
                        @if($otherUser && $otherUser->photo_url)
                            <img src="{{ $otherUser->photo_url }}" alt="{{ $otherUser->name ?? 'User' }}" class="rounded-circle w-100 h-100 object-fit-cover">
                        @else
                            <span class="text-white fw-bold">{{ $otherUser ? strtoupper(substr($otherUser->name, 0, 1)) : '?' }}</span>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="color: var(--soft-lilac);">{{ $otherUser->name ?? 'Unknown User' }}</h6>
                        <small style="color: rgba(200, 162, 200, 0.6);">
                            <span class="online-dot me-1" style="display: inline-block; width: 8px; height: 8px; background: #00D26A; border-radius: 50%; animation: pulse 2s infinite;"></span>
                            {{ $otherUser && $otherUser->vendor ? $otherUser->vendor->vendor_name : 'Online' }}
                        </small>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="flex-grow-1 p-4 overflow-auto" id="messages-container" style="background: radial-gradient(circle at 50% 50%, rgba(106, 56, 194, 0.05) 0%, transparent 50%);">
                    <div id="messages-list">
                        @forelse($messages as $message)
                            @php $isMine = $message->user_id === auth()->id(); @endphp
                            <div class="mb-3 d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="message-bubble p-3" style="max-width: 70%; border-radius: {{ $isMine ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }}; background: {{ $isMine ? 'linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%)' : 'rgba(45, 45, 65, 0.9)' }}; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                    <p class="mb-1 small" style="color: {{ $isMine ? 'white' : 'var(--soft-lilac)' }};">{{ $message->content }}</p>
                                    <small style="color: {{ $isMine ? 'rgba(255,255,255,0.7)' : 'rgba(200, 162, 200, 0.5)' }}; font-size: 0.65rem;">
                                        {{ $message->created_at->format('H:i') }}
                                        @if($isMine)
                                            <span class="ms-1">{{ $message->is_read ? '✓✓' : '✓' }}</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3" style="opacity: 0.5;">
                                    <svg width="60" height="60" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--soft-lilac);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <p style="color: rgba(200, 162, 200, 0.5);">No messages yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-3" style="background: rgba(26, 26, 46, 0.9); border-top: 1px solid rgba(106, 56, 194, 0.3);">
                    <form id="send-message-form" class="d-flex gap-2 align-items-center">
                        @csrf
                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                        <input type="text" 
                               name="content" 
                               id="message-input" 
                               class="form-control flex-grow-1" 
                               placeholder="Type a message..." 
                               required 
                               autocomplete="off"
                               style="background: rgba(45, 45, 65, 0.8); border: 1px solid rgba(106, 56, 194, 0.3); color: var(--soft-lilac); border-radius: 25px; padding: 12px 20px;">
                        <button type="submit" class="btn send-btn" id="send-btn" style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(106, 56, 194, 0.4); transition: all 0.2s;">
                            <svg width="22" height="22" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
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
            background: rgba(106, 56, 194, 0.25) !important;
            border-left: 3px solid #FF3CAC !important;
        }
        
        .message-bubble {
            animation: fadeInUp 0.3s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(106, 56, 194, 0.6) !important;
        }
        
        .send-btn:active {
            transform: scale(0.95);
        }
        
        #message-input:focus {
            outline: none;
            border-color: #6A38C2 !important;
            box-shadow: 0 0 0 3px rgba(106, 56, 194, 0.2);
        }
        
        #message-input::placeholder {
            color: rgba(200, 162, 200, 0.4);
        }
        
        /* Custom Scrollbar */
        .chat-list::-webkit-scrollbar,
        #messages-container::-webkit-scrollbar {
            width: 6px;
        }
        .chat-list::-webkit-scrollbar-track,
        #messages-container::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.2);
        }
        .chat-list::-webkit-scrollbar-thumb,
        #messages-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);
            border-radius: 3px;
        }
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
                            const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                            const borderRadius = isMine ? '18px 18px 4px 18px' : '18px 18px 18px 4px';
                            const bgStyle = isMine 
                                ? 'background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);' 
                                : 'background: rgba(45, 45, 65, 0.9);';
                            const textColor = isMine ? 'white' : 'var(--soft-lilac)';
                            const timeColor = isMine ? 'rgba(255,255,255,0.7)' : 'rgba(200, 162, 200, 0.5)';
                            const checkMark = isMine ? (msg.is_read ? ' ✓✓' : ' ✓') : '';
                            
                            return `
                                <div class="mb-3 d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'}">
                                    <div class="message-bubble p-3" style="max-width: 70%; border-radius: ${borderRadius}; ${bgStyle} box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                        <p class="mb-1 small" style="color: ${textColor};">${msg.content}</p>
                                        <small style="color: ${timeColor}; font-size: 0.65rem;">${time}${checkMark}</small>
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
