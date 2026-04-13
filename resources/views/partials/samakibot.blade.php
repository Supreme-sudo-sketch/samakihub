{{-- AquaBot Chat Widget — include at bottom of any authenticated page --}}
@auth
<style>
    :root{--sb-ocean:#0a3d4f;--sb-tide:#0d6e8a;--sb-foam:#e8f4f8;--sb-gold:#f0a500;--sb-coral:#e85d3a;--sb-fresh:#2eb87a;--sb-muted:#6b7d8a;--sb-border:#dde8ed}
    #samaki-fab{position:fixed;bottom:24px;right:24px;z-index:10000;width:56px;height:56px;border-radius:50%;background:var(--sb-ocean);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 24px rgba(10,61,79,.45);transition:transform .2s,background .2s}
    #samaki-fab:hover{background:var(--sb-tide);transform:scale(1.08)}
    .sb-fab-badge{position:absolute;top:-4px;right:-4px;background:var(--sb-coral);color:#fff;font-size:.65rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #fff}
    #samaki-panel{position:fixed;bottom:92px;right:24px;z-index:9999;width:360px;max-height:540px;background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(10,61,79,.22);display:flex;flex-direction:column;overflow:hidden;transform:translateY(16px) scale(.97);opacity:0;pointer-events:none;transition:transform .25s cubic-bezier(.34,1.56,.64,1),opacity .2s}
    #samaki-panel.open{transform:translateY(0) scale(1);opacity:1;pointer-events:all}
    @media(max-width:480px){
        #samaki-fab{bottom:16px;right:16px}
        #samaki-panel{left:0;right:0;bottom:0;width:100%;max-height:82vh;border-radius:20px 20px 0 0;transform:translateY(100%);transition:transform .3s cubic-bezier(.32,.72,0,1),opacity .2s}
        #samaki-panel.open{transform:translateY(0)}
    }
    .sb-header{background:linear-gradient(135deg,var(--sb-ocean),var(--sb-tide));padding:14px 18px;display:flex;align-items:center;gap:10px;flex-shrink:0}
    .sb-header-avatar{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem}
    .sb-header-info{flex:1}
    .sb-header-name{color:#fff;font-weight:700;font-size:.95rem;line-height:1.2}
    .sb-header-status{color:rgba(255,255,255,.7);font-size:.75rem;display:flex;align-items:center;gap:5px}
    .sb-online-dot{width:7px;height:7px;background:var(--sb-fresh);border-radius:50%;flex-shrink:0}
    .sb-close-btn{background:rgba(255,255,255,.15);border:none;color:#fff;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;transition:background .15s}
    .sb-close-btn:hover{background:rgba(255,255,255,.3)}
    .sb-messages{flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;min-height:0;scroll-behavior:smooth}
    .sb-messages::-webkit-scrollbar{width:4px}
    .sb-messages::-webkit-scrollbar-thumb{background:var(--sb-border);border-radius:4px}
    .sb-msg-wrap{display:flex;flex-direction:column}
    .sb-msg-wrap.user{align-items:flex-end}
    .sb-msg-wrap.bot{align-items:flex-start}
    .sb-bubble{max-width:82%;padding:10px 13px;border-radius:16px;font-size:.875rem;line-height:1.55;word-break:break-word}
    .sb-bubble.bot{background:var(--sb-foam);color:#1a1a2e;border-bottom-left-radius:4px}
    .sb-bubble.user{background:var(--sb-ocean);color:#fff;border-bottom-right-radius:4px}
    .sb-bubble-meta{font-size:.68rem;color:var(--sb-muted);margin-top:3px}
    .sb-typing{align-self:flex-start;background:var(--sb-foam);border-radius:16px;border-bottom-left-radius:4px;padding:12px 16px;display:none}
    .sb-typing.show{display:flex;gap:5px;align-items:center}
    .sb-typing span{width:7px;height:7px;background:var(--sb-muted);border-radius:50%;animation:sbBounce .9s infinite}
    .sb-typing span:nth-child(2){animation-delay:.15s}
    .sb-typing span:nth-child(3){animation-delay:.3s}
    @keyframes sbBounce{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-6px)}}
    .sb-suggestions{padding:0 14px 10px;display:flex;gap:6px;overflow-x:auto;flex-shrink:0}
    .sb-suggestions::-webkit-scrollbar{display:none}
    .sb-suggestion-chip{background:var(--sb-foam);border:1px solid var(--sb-border);color:var(--sb-tide);font-size:.78rem;font-weight:600;padding:6px 11px;border-radius:20px;cursor:pointer;white-space:nowrap;transition:all .15s;flex-shrink:0;font-family:inherit}
    .sb-suggestion-chip:hover{background:var(--sb-ocean);color:#fff;border-color:var(--sb-ocean)}
    .sb-error{background:#fde8e8;color:#a81a1a;font-size:.8rem;padding:8px 12px;border-radius:8px;margin:0 14px 8px;display:none}
    .sb-error.show{display:block}
    .sb-input-row{padding:12px 14px;border-top:1px solid var(--sb-border);display:flex;gap:8px;flex-shrink:0;background:#fff}
    .sb-input{flex:1;border:2px solid var(--sb-border);border-radius:10px;padding:9px 13px;font-family:inherit;font-size:.875rem;outline:none;resize:none;max-height:90px;line-height:1.4;transition:border-color .2s}
    .sb-input:focus{border-color:var(--sb-tide)}
    .sb-send-btn{width:38px;height:38px;border-radius:10px;background:var(--sb-ocean);border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:background .2s;flex-shrink:0;align-self:flex-end}
    .sb-send-btn:hover{background:var(--sb-tide)}
    .sb-send-btn:disabled{background:var(--sb-border);cursor:not-allowed}
</style>

<button id="samaki-fab" title="Chat with AquaBot" aria-label="Open AquaBot">
    <span id="sb-fab-icon">🤖</span>
    <span class="sb-fab-badge" id="sb-fab-badge" style="display:none">1</span>
</button>

<div id="samaki-panel" role="dialog" aria-label="AquaBot chat">
    <div class="sb-header">
        <div class="sb-header-avatar">🤖</div>
        <div class="sb-header-info">
            <div class="sb-header-name">AquaBot</div>
            <div class="sb-header-status">
                <span class="sb-online-dot"></span> Online · AquaHub Assistant
            </div>
        </div>
        <button class="sb-close-btn" id="sb-close-btn" aria-label="Close">✕</button>
    </div>

    <div class="sb-messages" id="sb-messages"></div>
    <div class="sb-error" id="sb-error"></div>

    <div class="sb-suggestions" id="sb-suggestions">
        <button class="sb-suggestion-chip" data-prompt="Find me fresh Tilapia">🐠 Fresh Tilapia</button>
        <button class="sb-suggestion-chip" data-prompt="How do I track my order?">📦 Track Order</button>
        <button class="sb-suggestion-chip" data-prompt="What fish is in season?">🌊 In Season</button>
        <button class="sb-suggestion-chip" data-prompt="How do I pay with M-Pesa?">💳 M-Pesa</button>
        <button class="sb-suggestion-chip" data-prompt="How do I sell fish on AquaHub?">🎣 Sell Fish</button>
    </div>

    <div class="sb-input-row">
        <textarea id="sb-input" class="sb-input" placeholder="Ask AquaBot anything…"
            rows="1" maxlength="1000" aria-label="Message AquaBot"></textarea>
        <button id="sb-send-btn" class="sb-send-btn" aria-label="Send">➤</button>
    </div>
</div>

<script>
(function () {
    const CSRF      = '{{ csrf_token() }}';
    const ENDPOINT  = '{{ url('/api/ai/chat') }}';
    const FIRSTNAME = @json(explode(' ', auth()->user()->name ?? 'there')[0]);

    const fab      = document.getElementById('samaki-fab');
    const fabIcon  = document.getElementById('sb-fab-icon');
    const fabBadge = document.getElementById('sb-fab-badge');
    const panel    = document.getElementById('samaki-panel');
    const closeBtn = document.getElementById('sb-close-btn');
    const msgsEl   = document.getElementById('sb-messages');
    const inputEl  = document.getElementById('sb-input');
    const sendBtn  = document.getElementById('sb-send-btn');
    const errorEl  = document.getElementById('sb-error');
    const suggsEl  = document.getElementById('sb-suggestions');

    let history  = [];
    let isOpen   = false;
    let sending  = false;
    let welcomed = false;

    function ts() {
        return new Date().toLocaleTimeString('en-KE', { hour: '2-digit', minute: '2-digit' });
    }

    function addBubble(role, text) {
        const wrap   = document.createElement('div');
        wrap.className = 'sb-msg-wrap ' + role;
        const bubble = document.createElement('div');
        bubble.className = 'sb-bubble ' + (role === 'user' ? 'user' : 'bot');
        bubble.textContent = text;
        const meta = document.createElement('div');
        meta.className = 'sb-bubble-meta';
        meta.textContent = (role === 'user' ? 'You' : 'AquaBot') + ' · ' + ts();
        wrap.appendChild(bubble);
        wrap.appendChild(meta);
        msgsEl.appendChild(wrap);
        msgsEl.scrollTop = msgsEl.scrollHeight;
    }

    function addTyping() {
        const t = document.createElement('div');
        t.className = 'sb-typing show'; t.id = 'sb-typing';
        t.innerHTML = '<span></span><span></span><span></span>';
        msgsEl.appendChild(t);
        msgsEl.scrollTop = msgsEl.scrollHeight;
    }

    function removeTyping() {
        const t = document.getElementById('sb-typing');
        if (t) t.remove();
    }

    function setSending(val) {
        sending = val;
        sendBtn.disabled = val;
        inputEl.disabled = val;
    }

    function open() {
        isOpen = true;
        panel.classList.add('open');
        fabIcon.textContent = '✕';
        fabBadge.style.display = 'none';
        if (!welcomed) {
            welcomed = true;
            addBubble('bot', '👋 Hi ' + FIRSTNAME + '! I\'m AquaBot. I can help you find fresh fish, answer order questions, or guide you through M-Pesa payments. What can I help you with?');
        }
        setTimeout(() => inputEl.focus(), 220);
    }

    function close() {
        isOpen = false;
        panel.classList.remove('open');
        fabIcon.textContent = '🤖';
    }

    fab.addEventListener('click', () => isOpen ? close() : open());
    closeBtn.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) close(); });

    async function send(text) {
        text = text.trim();
        if (!text || sending) return;

        setSending(true);
        errorEl.classList.remove('show');
        suggsEl.style.display = 'none';

        addBubble('user', text);
        inputEl.value = '';
        inputEl.style.height = 'auto';
        addTyping();

        try {
            const res = await fetch(ENDPOINT, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text, history }),
            });

            removeTyping();

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message || 'Server error (' + res.status + ')');
            }

            const data  = await res.json();
            const reply = data.reply || 'Sorry, I had trouble responding. Please try again.';

            addBubble('bot', reply);

            history.push({ role: 'user',      content: text  });
            history.push({ role: 'assistant',  content: reply });
            if (history.length > 20) history = history.slice(-20);

        } catch (err) {
            removeTyping();
            errorEl.textContent = '⚠️ ' + (err.message || 'Could not reach AquaBot.');
            errorEl.classList.add('show');
            setTimeout(() => errorEl.classList.remove('show'), 5000);
        } finally {
            setSending(false);
            inputEl.focus();
        }
    }

    inputEl.addEventListener('input', () => {
        inputEl.style.height = 'auto';
        inputEl.style.height = Math.min(inputEl.scrollHeight, 90) + 'px';
    });
    inputEl.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(inputEl.value); }
    });
    sendBtn.addEventListener('click', () => send(inputEl.value));
    suggsEl.querySelectorAll('.sb-suggestion-chip').forEach(chip => {
        chip.addEventListener('click', () => send(chip.dataset.prompt));
    });

    // Show attention badge after 4s if user hasn't opened it
    setTimeout(() => { if (!welcomed) fabBadge.style.display = 'flex'; }, 4000);
})();
</script>
@endauth

@guest
{{-- Guest version: FAB opens a teaser panel with a login CTA --}}
<style>
    #samaki-fab-guest{position:fixed;bottom:24px;right:24px;z-index:10000;width:56px;height:56px;border-radius:50%;background:#0a3d4f;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 24px rgba(10,61,79,.45);transition:transform .2s,background .2s}
    #samaki-fab-guest:hover{background:#0d6e8a;transform:scale(1.08)}
    .sg-badge{position:absolute;top:-4px;right:-4px;background:#e85d3a;color:#fff;font-size:.65rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #fff}
    #samaki-guest-panel{position:fixed;bottom:92px;right:24px;z-index:9999;width:320px;background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(10,61,79,.22);overflow:hidden;transform:translateY(16px) scale(.97);opacity:0;pointer-events:none;transition:transform .25s cubic-bezier(.34,1.56,.64,1),opacity .2s}
    #samaki-guest-panel.open{transform:translateY(0) scale(1);opacity:1;pointer-events:all}
    @media(max-width:480px){
        #samaki-fab-guest{bottom:16px;right:16px}
        #samaki-guest-panel{left:0;right:0;bottom:0;width:100%;border-radius:20px 20px 0 0;transform:translateY(100%);transition:transform .3s cubic-bezier(.32,.72,0,1),opacity .2s}
        #samaki-guest-panel.open{transform:translateY(0)}
    }
    .sg-header{background:linear-gradient(135deg,#0a3d4f,#0d6e8a);padding:14px 18px;display:flex;align-items:center;gap:10px}
    .sg-avatar{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.2rem}
    .sg-name{color:#fff;font-weight:700;font-size:.95rem}
    .sg-status{color:rgba(255,255,255,.7);font-size:.75rem;display:flex;align-items:center;gap:5px}
    .sg-dot{width:7px;height:7px;background:#2eb87a;border-radius:50%}
    .sg-close{background:rgba(255,255,255,.15);border:none;color:#fff;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;margin-left:auto}
    .sg-body{padding:22px 20px;text-align:center}
    .sg-body p{font-size:.88rem;color:#6b7d8a;line-height:1.55;margin-bottom:18px}
    .sg-chips{display:flex;gap:7px;flex-wrap:wrap;justify-content:center;margin-bottom:20px}
    .sg-chip{background:#e8f4f8;border:1px solid #dde8ed;color:#0d6e8a;font-size:.75rem;font-weight:600;padding:5px 10px;border-radius:20px}
    .sg-login-btn{display:block;background:#0a3d4f;color:#fff;font-weight:700;font-size:.9rem;padding:11px 20px;border-radius:10px;text-decoration:none;transition:background .2s;margin-bottom:8px}
    .sg-login-btn:hover{background:#0d6e8a}
    .sg-register-btn{display:block;background:#f0a500;color:#051e28;font-weight:700;font-size:.9rem;padding:11px 20px;border-radius:10px;text-decoration:none;transition:opacity .2s}
    .sg-register-btn:hover{opacity:.85}
</style>

<button id="samaki-fab-guest" title="Chat with AquaBot">
    <span id="sg-fab-icon">🤖</span>
    <span class="sg-badge" id="sg-badge" style="display:none">1</span>
</button>

<div id="samaki-guest-panel">
    <div class="sg-header">
        <div class="sg-avatar">🤖</div>
        <div>
            <div class="sg-name">AquaBot</div>
            <div class="sg-status"><span class="sg-dot"></span> Online · AquaHub Assistant</div>
        </div>
        <button class="sg-close" id="sg-close-btn">✕</button>
    </div>
    <div class="sg-body">
        <p>👋 Hi there! I'm AquaBot. I can help you find fresh fish, answer questions about prices, and guide you through ordering.</p>
        <div class="sg-chips">
            <span class="sg-chip">🐠 Fresh Tilapia</span>
            <span class="sg-chip">📦 Track Orders</span>
            <span class="sg-chip">💳 M-Pesa Pay</span>
            <span class="sg-chip">🌊 In Season</span>
        </div>
        <a href="{{ route('login') }}" class="sg-login-btn">Login to Chat with AquaBot</a>
        <a href="{{ route('register') }}" class="sg-register-btn">Create a Free Account →</a>
    </div>
</div>

<script>
(function(){
    const fab   = document.getElementById('samaki-fab-guest');
    const panel = document.getElementById('samaki-guest-panel');
    const icon  = document.getElementById('sg-fab-icon');
    const badge = document.getElementById('sg-badge');
    const close = document.getElementById('sg-close-btn');
    let open    = false;

    function toggle() {
        open = !open;
        panel.classList.toggle('open', open);
        icon.textContent = open ? '✕' : '🤖';
        if (open) badge.style.display = 'none';
    }

    fab.addEventListener('click', toggle);
    close.addEventListener('click', () => { open = true; toggle(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && open) toggle(); });

    setTimeout(() => { if (!open) badge.style.display = 'flex'; }, 4000);
})();
</script>
@endguest
