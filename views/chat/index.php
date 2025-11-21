<?php
// views/chat/index.php
// session already started in controller; do not call session_start() here to avoid notices
?>
<div class="container" style="max-width:900px;margin:28px auto;">
    <h2 style="color:#0d6efd;margin-bottom:12px;">Chatbot - Trợ lý học tiếng Anh</h2>

    <div id="chat-box" style="background:#fff;border-radius:12px;padding:14px;height:480px;overflow:auto;border:1px solid #eef7ff;">
        <div id="messages"></div>
    </div>

    <div style="display:flex;gap:8px;margin-top:12px;">
        <input id="chat-input" type="text" placeholder="Nhập câu hỏi hoặc từ cần giải thích..." style="flex:1;padding:12px;border-radius:8px;border:1px solid #bfd7ff;">
        <button id="send-btn" style="background:#0d6efd;color:#fff;padding:10px 16px;border-radius:8px;border:none;font-weight:700;">Gửi</button>
    </div>

    <div style="margin-top:10px;color:#666;font-size:0.9rem;">Ghi chú: Chat history được lưu cho từng tài khoản.</div>

</div>

<script>
const messagesEl = document.getElementById('messages');
const inputEl = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-btn');

function appendMessage(role, text) {
    const wrap = document.createElement('div');
    wrap.style.marginBottom = '12px';
    if (role === 'user') {
        wrap.innerHTML = `<div style="text-align:right;"><div style="display:inline-block;background:#e8f0ff;color:#023a8a;padding:8px 12px;border-radius:12px;max-width:80%;">${escapeHtml(text)}</div></div>`;
    } else {
        wrap.innerHTML = `<div style="text-align:left;"><div style="display:inline-block;background:#f6f9ff;color:#06406b;padding:8px 12px;border-radius:12px;max-width:80%;">${escapeHtml(text)}</div></div>`;
    }
    messagesEl.appendChild(wrap);
    // scroll
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
}

function escapeHtml(s){
    if (!s) return '';
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

async function sendMessage() {
    const text = inputEl.value.trim();
    if (!text) return;
    appendMessage('user', text);
    inputEl.value = '';

    appendMessage('assistant', 'Đang xử lý...');

    try {
        const res = await fetch('../api/chatbot.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({message: text})
        });
        const data = await res.json();
        // remove the 'Đang xử lý...' placeholder
        const last = messagesEl.lastChild;
        if (last) messagesEl.removeChild(last);

        if (data.success) {
            appendMessage('assistant', data.reply);
        } else {
            appendMessage('assistant', 'Lỗi: ' + (data.message || 'Không có phản hồi'));
        }
    } catch (e) {
        appendMessage('assistant', 'Lỗi khi gửi yêu cầu: ' + e.message);
    }
}

sendBtn.addEventListener('click', sendMessage);
inputEl.addEventListener('keydown', function(e){ if (e.key === 'Enter') sendMessage(); });

// load recent history on open
async function loadHistory(){
    try {
        const res = await fetch('../api/chatbot.php?action=history&limit=50&page=1');
        const data = await res.json();
        if (data.success) {
            messagesEl.innerHTML = '';
            data.data.forEach(m => appendMessage(m.role === 'assistant' ? 'assistant' : 'user', m.message));
        }
    } catch (e) {
        console.error(e);
    }
}

loadHistory();
</script>
