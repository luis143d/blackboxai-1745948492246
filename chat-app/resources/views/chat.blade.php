@extends('layouts.app')

@section('title', 'Chat Room')

@section('header', 'Real-time Chat')

@section('content')
<div id="chat-container" class="bg-white rounded shadow p-4 max-w-3xl mx-auto flex flex-col h-[600px]">
    <div id="messages" class="flex-grow overflow-y-auto mb-4 space-y-2 p-2 border border-gray-300 rounded">
        <!-- Messages will appear here -->
    </div>
    <form id="chat-form" class="flex space-x-2" onsubmit="sendMessage(event)">
        <input id="message-input" type="text" placeholder="Type your message..." class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Send</button>
    </form>
</div>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Enable pusher logging - remove in production
    Pusher.logToConsole = true;

    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    const channel = pusher.subscribe('private-chat');

    channel.bind('MessageSent', function(data) {
        addMessage(data.user, data.message);
    });

    function addMessage(user, message) {
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.classList.add('p-2', 'rounded', 'bg-gray-100');
        messageElement.textContent = user + ': ' + message;
        messages.appendChild(messageElement);
        messages.scrollTop = messages.scrollHeight;
    }

    function sendMessage(event) {
        event.preventDefault();
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        if (message === '') return;

        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        }).then(response => {
            if (response.ok) {
                input.value = '';
            }
        });
    }
</script>
@endsection
