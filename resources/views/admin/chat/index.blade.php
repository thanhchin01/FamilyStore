@extends('admin.layout')

@section('title', 'Trung tâm tin nhắn')

@section('content')
<div class="chat-admin-wrapper glass-panel shadow-lg rounded-4 overflow-hidden mt-4" style="height: 80vh; display: flex;">
    <!-- Conversations List -->
    <div class="chat-sidebar border-end" style="width: 350px; background: #f8fafc;">
        <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">Hội thoại</h5>
            <span class="badge bg-primary-light rounded-pill">{{ count($conversations) }}</span>
        </div>
        <div class="conversation-list overflow-auto" style="height: calc(100% - 60px);">
            @foreach($conversations as $conv)
            <div class="conv-item p-3 border-bottom cursor-pointer {{ $loop->first ? 'active' : '' }}" 
                 onclick="loadConversation({{ $conv->id }}, this)">
                <div class="d-flex align-items-center">
                    <div class="avatar-circle me-3 bg-secondary text-white">
                        {{ strtoupper(substr($conv->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-truncate">{{ $conv->user->name }}</h6>
                            <small class="text-muted">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}</small>
                        </div>
                        <p class="mb-0 small text-muted text-truncate">
                            {{ $conv->messages->first()->message ?? 'Chưa có tin nhắn' }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Active Chat Area -->
    <div class="chat-main flex-grow-1 d-flex flex-column bg-white">
        <div id="chatHeader" class="p-3 border-bottom d-flex align-items-center">
            <h6 class="fw-bold mb-0">Chọn một hội thoại để bắt đầu</h6>
        </div>
        
        <div id="chatBody" class="flex-grow-1 p-4 overflow-auto d-flex flex-column gap-3" style="background: #f1f5f9;">
            <!-- Messages load here -->
            <div class="text-center text-muted mt-5">
                <i class="fas fa-comments fa-3x mb-3 opacity-25"></i>
                <p>Nội dung tin nhắn sẽ hiển thị tại đây.</p>
            </div>
        </div>

        <div id="chatInputArea" class="p-3 border-top d-none">
            <form id="replyForm" onsubmit="event.preventDefault(); sendReply();">
                <div class="input-group">
                    <input type="text" id="replyMessage" class="form-control border-0 bg-light shadow-none py-2 px-3" 
                           placeholder="Nhập nội dung trả lời..." autocomplete="off">
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.conv-item { transition: all 0.2s ease; }
.conv-item:hover { background: #eef2ff; }
.conv-item.active { background: #e0f2f1; border-left: 4px solid #009688; }
.avatar-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

.msg-bubble { max-width: 75%; padding: 12px 18px; border-radius: 15px; font-size: 14px; position: relative; }
.msg-bubble.admin { align-self: flex-end; background: #009688; color: white; border-bottom-right-radius: 2px; }
.msg-bubble.client { align-self: flex-start; background: white; border-bottom-left-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

.msg-time { font-size: 10px; opacity: 0.7; margin-top: 5px; display: block; }
</style>

@push('scripts')
<script>
let currentConversationId = null;

function loadConversation(id, element) {
    currentConversationId = id;
    
    // UI active state
    document.querySelectorAll('.conv-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');
    
    document.getElementById('chatInputArea').classList.remove('d-none');
    
    const userName = element.querySelector('h6').innerText;
    document.getElementById('chatHeader').innerHTML = `
        <div class="avatar-circle me-2 bg-primary-light text-white" style="width: 35px; height: 35px; font-size: 14px;">
            ${userName.charAt(0).toUpperCase()}
        </div>
        <h6 class="fw-bold mb-0">${userName}</h6>
    `;

    fetchMessages(id);
}

function fetchMessages(id) {
    fetch(`/admin/chat/messages/${id}`)
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                renderMessages(res.data);
            }
        });
}

function renderMessages(messages) {
    const chatBody = document.getElementById('chatBody');
    chatBody.innerHTML = '';
    
    if (messages.length === 0) {
        chatBody.innerHTML = '<div class="text-center text-muted my-3 small">Chưa có tin nhắn nào.</div>';
        return;
    }

    messages.forEach(msg => {
        const isAdmin = msg.sender.role === 'admin';
        const div = document.createElement('div');
        div.className = `msg-bubble ${isAdmin ? 'admin' : 'client'}`;
        div.innerHTML = `
            ${msg.message}
            <span class="msg-time ${isAdmin ? 'text-white-50' : 'text-muted'}">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
        `;
        chatBody.appendChild(div);
    });
    
    chatBody.scrollTop = chatBody.scrollHeight;
}

function sendReply() {
    const input = document.getElementById('replyMessage');
    const msg = input.value.trim();
    if (!msg || !currentConversationId) return;

    input.value = '';
    
    fetch(`/admin/chat/reply/${currentConversationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            fetchMessages(currentConversationId);
        }
    });
}

// Auto refresh active chat
setInterval(() => {
    if (currentConversationId) {
        fetchMessages(currentConversationId);
    }
}, 5000);
</script>
@endpush
@endsection
