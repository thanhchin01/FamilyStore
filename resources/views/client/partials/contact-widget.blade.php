<div class="contact-widgets shadow-sm">
    <!-- Hotline Item -->
    <div class="contact-item hotline" onclick="window.location.href='tel:0382861579'">
        <div class="contact-label">Hotline: 038.286.1579</div>
        <div class="contact-icon pulsing">
            <i class="fas fa-phone"></i>
        </div>
    </div>

    <!-- Facebook Item -->
    <div class="contact-item facebook" onclick="toggleChatPopup()">
        <div class="contact-label">Facebook</div>
        <div class="contact-icon">
            <i class="fab fa-facebook-messenger"></i>
        </div>
    </div>

    <!-- Zalo Item -->
    <div class="contact-item zalo" onclick="window.open('https://zalo.me/0382861579', '_blank')">
        <div class="contact-label">Zalo</div>
        <div class="contact-icon">
            <img src="https://img.icons8.com/color/48/zalo.png" alt="Zalo" width="30">
        </div>
    </div>
</div>

<!-- Chat Popup Window -->
<div class="chat-popup" id="chatPopup">
    <div class="chat-header">
        <div class="d-flex align-items-center">
            <div class="chat-avatar me-2">
                <img src="{{ asset('luxury_home_appliances_banner_1776356257888.png') }}" alt="Admin">
            </div>
            <div>
                <h6 class="mb-0 fw-bold">K-Q Support</h6>
                <small class="status-online"><i class="fas fa-circle ms-1"></i> Đang trực tuyến</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="toggleChatPopup()"></button>
    </div>
    <div class="chat-body" id="chatBodyClient">
        <div class="chat-msg system mb-2">
            Xin chào! Khoa Quyên Store có thể giúp gì cho bạn?
        </div>
    </div>
    <div class="chat-footer p-2 border-top bg-white">
        @auth
        <form id="clientChatForm" onsubmit="event.preventDefault(); sendClientMessage();">
            <div class="input-group input-group-sm">
                <input type="text" id="clientMessage" class="form-control border-light shadow-none" placeholder="Nhập tin nhắn...">
                <button type="submit" class="btn btn-primary-custom px-3">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
        @else
        <div class="text-center py-2">
            <small class="text-muted">Vui lòng <a href="#" data-bs-toggle="modal" data-bs-target="#authModal" class="text-primary-color fw-bold">Đăng nhập</a> để chat.</small>
        </div>
        @endauth
    </div>
</div>

<style>
.contact-widgets {
    position: fixed;
    bottom: 30px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 15px;
    pointer-events: none;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    transition: all 0.3s ease;
}

.contact-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    z-index: 2;
    background: white;
}

.hotline .contact-icon { background: #7aba2c; }
.facebook .contact-icon { background: #00a6ff; }
.zalo .contact-icon { background: #0068ff; }

.contact-label {
    background: white;
    padding: 10px 25px 10px 15px;
    border-radius: 50px;
    margin-right: -25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    font-weight: 600;
    font-size: 15px;
    color: white;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    white-space: nowrap;
}

.hotline .contact-label { background: #7aba2c; }
.facebook .contact-label { background: #00a6ff; }
.zalo .contact-label { background: #0068ff; }

.contact-item:hover .contact-label {
    opacity: 1;
    transform: translateX(-5px);
}

.pulsing {
    animation: pulse-ring 1.5s infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(122, 186, 44, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(122, 186, 44, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(122, 186, 44, 0); }
}

/* Chat Popup */
.chat-popup {
    position: fixed;
    bottom: 100px;
    right: 20px;
    width: 300px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    z-index: 10000;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.chat-header {
    background: #009688;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
}

.status-online {
    font-size: 11px;
    opacity: 0.9;
}

.chat-body {
    height: 200px;
    padding: 15px;
    background: #f8f9fa;
    overflow-y: auto;
}

/* New Chat Message Styles */
.chat-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.chat-msg {
    max-width: 85%;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 13px;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.chat-msg.client {
    align-self: flex-end;
    background: #009688;
    color: white;
    border-bottom-right-radius: 2px;
}
.chat-msg.admin {
    align-self: flex-start;
    background: white;
    color: #333;
    border-bottom-left-radius: 2px;
}
.chat-msg.system {
    align-self: center;
    background: #f1f5f9;
    color: #64748b;
    font-size: 11px;
    text-align: center;
    box-shadow: none;
}

@media (max-width: 576px) {
    .contact-widgets { bottom: 20px; right: 10px; gap: 10px; }
    .contact-icon { width: 50px; height: 50px; font-size: 20px; }
    .zalo .contact-icon img { width: 25px; }
    .chat-popup { width: 280px; bottom: 85px; right: 10px; }
}
</style>

<script>
let chatInterval = null;

function toggleChatPopup() {
    const popup = document.getElementById('chatPopup');
    if (popup.style.display === 'flex') {
        popup.style.display = 'none';
        clearInterval(chatInterval);
    } else {
        popup.style.display = 'flex';
        fetchClientMessages();
        chatInterval = setInterval(fetchClientMessages, 5000);
    }
}

function fetchClientMessages() {
    @auth
    fetch('{{ route('client.chat.messages') }}')
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                renderClientMessages(res.data);
            }
        });
    @endauth
}

function renderClientMessages(messages) {
    const chatBody = document.getElementById('chatBodyClient');
    if(!chatBody) return;
    
    const systemMsg = chatBody.querySelector('.system');
    chatBody.innerHTML = '';
    if (systemMsg) chatBody.appendChild(systemMsg);

    messages.forEach(msg => {
        const isAdmin = msg.sender && msg.sender.role === 'admin';
        const div = document.createElement('div');
        div.className = `chat-msg ${isAdmin ? 'admin' : 'client'}`;
        div.innerText = msg.message;
        chatBody.appendChild(div);
    });
    
    chatBody.scrollTop = chatBody.scrollHeight;
}

function sendClientMessage() {
    const input = document.getElementById('clientMessage');
    const msg = input.value.trim();
    if (!msg) return;

    input.value = '';
    
    fetch('{{ route('client.chat.send') }}', {
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
            fetchClientMessages();
        }
    })
    .catch(err => console.error('Error sending message:', err));
}
</script>
