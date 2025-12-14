<x-dashboard-layout>
    <x-slot name="title">Messages</x-slot>
    
    <h1 class="h3 text-gradient mb-4">💬 Messages</h1>

    <div class="row">
        <!-- Chat List -->
        <div class="col-md-4 mb-3">
            <div class="card bg-midnight-black border-0">
                <div class="card-body p-3">
                    <input type="text" class="form-control mb-3" placeholder="Search conversations...">

                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active d-flex align-items-center">
                            <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-weight:700">JD</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">TechStore</div>
                                <div class="small text-soft-lilac">About your order #1234...</div>
                            </div>
                            <div class="text-end ms-2">
                                <div class="small text-soft-lilac">2m ago</div>
                                <div class="badge bg-neon-pink text-dark">3</div>
                            </div>
                        </a>

                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="rounded-circle bg-gradient-accent text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-weight:700">FH</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">FashionHub</div>
                                <div class="small text-soft-lilac">When will my item arrive?</div>
                            </div>
                            <div class="text-end ms-2">
                                <div class="small text-soft-lilac">1h ago</div>
                            </div>
                        </a>

                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-weight:700;background:linear-gradient(135deg,var(--cyan-blue),var(--royal-purple))">HE</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">HomeEssentials</div>
                                <div class="small text-soft-lilac">Thank you for your purchase!</div>
                            </div>
                            <div class="text-end ms-2">
                                <div class="small text-soft-lilac">3h ago</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="col-md-8 mb-3">
            <div class="card bg-midnight-black border-0 h-100">
                <div class="card-body p-0 d-flex flex-column" style="min-height:500px">
                    <!-- Chat Header -->
                    <div class="p-3 border-bottom" style="border-color:rgba(212,194,255,0.12)">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-weight:700">JD</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">TechStore</div>
                                <div class="small text-soft-lilac">Online</div>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm">View Profile</button>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="p-3 flex-grow-1 overflow-auto">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-weight:700">JD</div>
                            </div>
                            <div>
                                <div class="message-bubble bg-soft-lilac text-dark p-2 rounded">Hello! Thank you for ordering from TechStore. Your order #1234 is being processed.</div>
                                <div class="small text-soft-lilac mt-1">10:30 AM</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3 justify-content-end">
                            <div>
                                <div class="message-bubble bg-primary text-white p-2 rounded">Great! When can I expect delivery?</div>
                                <div class="small text-soft-lilac mt-1 text-end">10:32 AM</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-weight:700">JD</div>
                            </div>
                            <div>
                                <div class="message-bubble bg-soft-lilac text-dark p-2 rounded">Your order will be delivered within 2-3 business days. We'll send you tracking information once it ships.</div>
                                <div class="small text-soft-lilac mt-1">10:35 AM</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3 justify-content-end">
                            <div>
                                <div class="message-bubble bg-primary text-white p-2 rounded">Perfect! Thank you for the quick response 😊</div>
                                <div class="small text-soft-lilac mt-1 text-end">10:36 AM</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-weight:700">JD</div>
                            </div>
                            <div>
                                <div class="message-bubble bg-soft-lilac text-dark p-2 rounded">You're welcome! Is there anything else I can help you with?</div>
                                <div class="small text-soft-lilac mt-1">10:40 AM</div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-3 border-top" style="border-color:rgba(212,194,255,0.06)">
                        <div class="d-flex gap-2 align-items-center">
                            <button class="btn btn-ghost p-2 rounded"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg></button>
                            <input type="text" class="form-control flex-grow-1" placeholder="Type your message...">
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-dashboard-layout>
