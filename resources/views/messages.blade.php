<x-dashboard-layout>
    <x-slot name="title">Messages</x-slot>
    
    <h1 class="text-4xl font-bold text-gradient mb-4">💬 Messages</h1>
    
    <div class="chat-container">
        <!-- Chat List -->
        <div class="chat-list">
            <div class="p-4">
                <input type="text" class="form-input mb-3" placeholder="Search conversations...">
            </div>
            
            <div class="chat-item active">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                        JD
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold">TechStore</h4>
                        <p class="text-sm text-soft-lilac">About your order #1234...</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-soft-lilac">2m ago</p>
                        <span class="flex w-5 h-5 rounded-full bg-neon-pink text-xs items-center justify-center">3</span>
                    </div>
                </div>
            </div>
            
            <div class="chat-item">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-accent flex items-center justify-center font-bold">
                        FH
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold">FashionHub</h4>
                        <p class="text-sm text-soft-lilac">When will my item arrive?</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-soft-lilac">1h ago</p>
                    </div>
                </div>
            </div>
            
            <div class="chat-item">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full" style="background: linear-gradient(135deg, var(--cyan-blue), var(--royal-purple))">
                        <div class="w-full h-full flex items-center justify-center font-bold">
                            HE
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold">HomeEssentials</h4>
                        <p class="text-sm text-soft-lilac">Thank you for your purchase!</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-soft-lilac">3h ago</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="chat-window">
            <!-- Chat Header -->
            <div class="p-4 border-b border-soft-lilac border-opacity-20 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                    JD
                </div>
                <div class="flex-1">
                    <h3 class="font-bold">TechStore</h3>
                    <p class="text-sm text-soft-lilac">Online</p>
                </div>
                <button class="btn btn-secondary btn-sm">View Profile</button>
            </div>
            
            <!-- Messages -->
            <div class="chat-messages">
                <div class="message received">
                    <div class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                        JD
                    </div>
                    <div>
                        <div class="message-bubble">
                            <p>Hello! Thank you for ordering from TechStore. Your order #1234 is being processed.</p>
                        </div>
                        <p class="text-xs text-soft-lilac mt-1">10:30 AM</p>
                    </div>
                </div>
                
                <div class="message sent">
                    <div>
                        <div class="message-bubble">
                            <p>Great! When can I expect delivery?</p>
                        </div>
                        <p class="text-xs text-soft-lilac mt-1 text-right">10:32 AM</p>
                    </div>
                </div>
                
                <div class="message received">
                    <div class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                        JD
                    </div>
                    <div>
                        <div class="message-bubble">
                            <p>Your order will be delivered within 2-3 business days. We'll send you tracking information once it ships.</p>
                        </div>
                        <p class="text-xs text-soft-lilac mt-1">10:35 AM</p>
                    </div>
                </div>
                
                <div class="message sent">
                    <div>
                        <div class="message-bubble">
                            <p>Perfect! Thank you for the quick response 😊</p>
                        </div>
                        <p class="text-xs text-soft-lilac mt-1 text-right">10:36 AM</p>
                    </div>
                </div>
                
                <div class="message received">
                    <div class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center font-bold">
                        JD
                    </div>
                    <div>
                        <div class="message-bubble">
                            <p>You're welcome! Is there anything else I can help you with?</p>
                        </div>
                        <p class="text-xs text-soft-lilac mt-1">10:40 AM</p>
                    </div>
                </div>
            </div>
            
            <!-- Chat Input -->
            <div class="chat-input-container">
                <button class="p-2 hover:bg-purple-900 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                </button>
                <input type="text" class="chat-input" placeholder="Type your message...">
                <button class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
</x-dashboard-layout>
