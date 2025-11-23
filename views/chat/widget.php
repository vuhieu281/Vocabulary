<?php
// Small floating chatbot widget 
?>
<style>
/* Chat widget styles */
.vc-chat-widget {position:fixed;right:20px;bottom:24px;z-index:9999;font-family:Arial,Helvetica,sans-serif}
.vc-chat-button{width:56px;height:56px;border-radius:50%;background:#0d6efd;color:#fff;border:none;box-shadow:0 6px 18px rgba(13,110,253,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer}
.vc-chat-panel{width:340px;height:420px;border-radius:12px;background:#fff;box-shadow:0 8px 30px rgba(15,23,42,0.15);overflow:hidden;display:flex;flex-direction:column;border:1px solid #eef7ff}
.vc-chat-header{background:#0d6efd;color:#fff;padding:10px 12px;font-weight:700;display:flex;align-items:center;justify-content:space-between}
.vc-chat-messages{flex:1;overflow:auto;padding:12px;background:linear-gradient(#fbfdff,#f7fbff)}
.vc-chat-input{display:flex;padding:10px;border-top:1px solid #f0f6ff}
.vc-chat-input input{flex:1;padding:8px 10px;border-radius:8px;border:1px solid #dfeeff}
.vc-chat-input button{margin-left:8px;padding:8px 12px;border-radius:8px;border:none;background:#0d6efd;color:#fff;font-weight:700}
.vc-msg-user{text-align:right;margin-bottom:10px}
.vc-msg-user .bubble{display:inline-block;background:#e8f0ff;color:#023a8a;padding:8px 12px;border-radius:12px;max-width:78%}
.vc-msg-bot{text-align:left;margin-bottom:10px}
.vc-msg-bot .bubble{display:inline-block;background:#f6f9ff;color:#06406b;padding:8px 12px;border-radius:12px;max-width:78%}
.vc-hidden{display:none}
</style>

<div class="vc-chat-widget" id="vc-chat-widget">
    <div id="vc-chat-panel" class="vc-chat-panel vc-hidden" role="dialog" aria-label="Chatbot">
        <div class="vc-chat-header">
            <div>Trợ lý Vocabulary</div>
            <div style="font-size:0.9rem;opacity:0.95">
                <button id="vc-chat-close" style="background:transparent;border:none;color:#fff;cursor:pointer;font-weight:700">×</button>
            </div>
        </div>
        <div id="vc-messages" class="vc-chat-messages">
            <div style="color:#666;font-size:0.9rem">Nhập câu hỏi về từ vựng tiếng Anh. Trả lời ngắn gọn, có ví dụ.</div>
        </div>
        <div class="vc-chat-input">
            <input id="vc-input" type="text" placeholder="Hỏi tôi (ví dụ: meaning of 'serendipity')">
            <button id="vc-send">Gửi</button>
        </div>
    </div>

    <button id="vc-open-btn" class="vc-chat-button" title="Chat với trợ lý">
        💬
    </button>
</div>

<script>
(function(){
    const widget = document.getElementById('vc-chat-widget');
    const panel = document.getElementById('vc-chat-panel');
    const openBtn = document.getElementById('vc-open-btn');
    const closeBtn = document.getElementById('vc-chat-close');
    const sendBtn = document.getElementById('vc-send');
    const input = document.getElementById('vc-input');
    const messagesEl = document.getElementById('vc-messages');

    function append(role, text){
        const wrap = document.createElement('div');
        wrap.className = role === 'user' ? 'vc-msg-user' : 'vc-msg-bot';
        const b = document.createElement('div'); b.className = 'bubble'; b.innerText = text;
        wrap.appendChild(b);
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function toggle(open){
        if (open) panel.classList.remove('vc-hidden'); else panel.classList.add('vc-hidden');
    }

    openBtn.addEventListener('click', ()=>{ toggle(true); loadHistory(); });
    closeBtn.addEventListener('click', ()=> toggle(false));

    async function loadHistory(){
        try{
            const res = await fetch('../api/chatbot.php?action=history&limit=20&page=1');
            const data = await res.json();
            if (data.success){
                messagesEl.innerHTML = '';
                data.data.forEach(m => append(m.role === 'assistant' ? 'bot' : 'user', m.message));
            }
        }catch(e){ console.warn('Load history error', e); }
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', (e)=>{ if(e.key==='Enter') send(); });

    async function send(){
        const text = input.value.trim(); if(!text) return;
        append('user', text); input.value=''; append('bot','Đang xử lý...');
        try{
            const res = await fetch('../api/chatbot.php', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({message:text})});
            const data = await res.json();

            const last = messagesEl.lastChild; if(last) messagesEl.removeChild(last);
            if (data.success) {
                append('bot', data.reply);
                setTimeout(loadHistory, 500);
            }
            else append('bot', 'Lỗi: ' + (data.message || 'Không có phản hồi'));
        }catch(e){
            const last = messagesEl.lastChild; if(last) messagesEl.removeChild(last);
            append('bot', 'Lỗi khi gửi yêu cầu');
        }
    }
})();
</script>
