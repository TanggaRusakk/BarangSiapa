<x-dashboard-layout>
    <x-slot name="title">Admin — Messages</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Messages</h3>
        <div class="space-y-3">
            @foreach($messages as $msg)
                <div class="p-3 bg-purple-900 bg-opacity-10 rounded">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-bold">{{ optional($msg->user)->name }}</div>
                            <div class="text-sm text-soft-lilac">Chat #{{ $msg->chat_id }}</div>
                        </div>
                        <div class="text-sm text-soft-lilac">{{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                    <p class="text-soft-lilac">{{ $msg->message_text ?? '—' }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>
</x-dashboard-layout>