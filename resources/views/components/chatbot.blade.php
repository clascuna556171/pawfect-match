{{-- PawBot — Floating Chatbot Widget --}}
<div id="pawbot-widget">
    {{-- Floating Toggle Button --}}
    <button id="pawbot-toggle" aria-label="Open PawBot assistant">
        <img src="{{ asset('images/logo.png') }}" alt="PawBot" class="pawbot-logo">
        <span class="pawbot-pulse"></span>
        <span class="pawbot-badge" id="pawbot-badge" style="display:none;">1</span>
    </button>

    {{-- Chat Panel --}}
    <div id="pawbot-panel" class="pawbot-panel" aria-hidden="true">
        <div class="pawbot-header">
            <div class="pawbot-header-info">
                <img src="{{ asset('images/logo.png') }}" alt="PawBot" class="pawbot-header-logo">
                <div>
                    <span class="pawbot-header-name">PawBot</span>
                    <span class="pawbot-header-status"><span class="pawbot-online-dot"></span> Online</span>
                </div>
            </div>
            <button id="pawbot-close" class="pawbot-close" aria-label="Close chat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="pawbot-messages" class="pawbot-messages">
            <div class="pawbot-msg pawbot-msg-bot">
                <div class="pawbot-msg-avatar">
                    <img src="{{ asset('images/logo.png') }}" alt="PawBot">
                </div>
                <div class="pawbot-msg-bubble">
                    Hey there! 🐾 I'm <strong>PawBot</strong>, your pet adoption assistant.<br><br>
                    Try asking me things like:<br>
                    • "Show me small dogs"<br>
                    • "Any cats good with kids?"<br>
                    • "I want a calm pet"<br>
                    • "Is there a Golden Retriever?"
                </div>
            </div>
        </div>
        <form id="pawbot-form" class="pawbot-input-area" autocomplete="off">
            @csrf
            <input type="text" id="pawbot-input" class="pawbot-input" placeholder="Ask me about pets..." maxlength="500" required>
            <button type="submit" id="pawbot-send" class="pawbot-send" aria-label="Send message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </form>
    </div>
</div>
