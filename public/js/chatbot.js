/* ==============================================
   PAWBOT — CHATBOT CLIENT-SIDE LOGIC
   ============================================== */
(function () {
  'use strict';

  const toggle   = document.getElementById('pawbot-toggle');
  const panel    = document.getElementById('pawbot-panel');
  const closeBtn = document.getElementById('pawbot-close');
  const form     = document.getElementById('pawbot-form');
  const input    = document.getElementById('pawbot-input');
  const messages = document.getElementById('pawbot-messages');
  const badge    = document.getElementById('pawbot-badge');

  if (!toggle || !panel) return;

  const CHATBOT_URL = '/chatbot';
  const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content;

  let isOpen = false;

  /* ---- Toggle open / close ---- */
  function openChat() {
    isOpen = true;
    panel.classList.add('open');
    panel.setAttribute('aria-hidden', 'false');
    toggle.classList.add('active');
    badge.style.display = 'none';
    setTimeout(function () { input.focus(); }, 350);
  }

  function closeChat() {
    isOpen = false;
    panel.classList.remove('open');
    panel.setAttribute('aria-hidden', 'true');
    toggle.classList.remove('active');
  }

  toggle.addEventListener('click', function () {
    if (isOpen) { closeChat(); } else { openChat(); }
  });

  closeBtn.addEventListener('click', closeChat);

  /* Close on Escape key */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isOpen) closeChat();
  });

  /* ---- Helpers ---- */
  function scrollToBottom() {
    messages.scrollTop = messages.scrollHeight;
  }

  function addUserMessage(text) {
    var div = document.createElement('div');
    div.className = 'pawbot-msg pawbot-msg-user';
    div.innerHTML =
      '<div class="pawbot-msg-bubble">' + escapeHtml(text) + '</div>';
    messages.appendChild(div);
    scrollToBottom();
  }

  function addBotMessage(html) {
    var div = document.createElement('div');
    div.className = 'pawbot-msg pawbot-msg-bot';
    div.innerHTML =
      '<div class="pawbot-msg-avatar"><img src="/images/logo.png" alt="PawBot"></div>' +
      '<div class="pawbot-msg-content">' +
        '<div class="pawbot-msg-bubble">' + html + '</div>' +
      '</div>';
    messages.appendChild(div);
    scrollToBottom();
  }

  function showTyping() {
    var div = document.createElement('div');
    div.className = 'pawbot-typing';
    div.id = 'pawbot-typing-indicator';
    div.innerHTML =
      '<div class="pawbot-msg-avatar"><img src="/images/logo.png" alt="PawBot"></div>' +
      '<div class="pawbot-typing-dots"><span></span><span></span><span></span></div>';
    messages.appendChild(div);
    scrollToBottom();
  }

  function hideTyping() {
    var el = document.getElementById('pawbot-typing-indicator');
    if (el) el.remove();
  }

  function escapeHtml(text) {
    var d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
  }

  function formatReply(text) {
    /* Bold: **text** → <strong>text</strong> */
    text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    /* Italic: *text* → <em>text</em> */
    text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');
    /* Newlines → <br> */
    text = text.replace(/\n/g, '<br>');
    /* Bullet points */
    text = text.replace(/• /g, '• ');
    return text;
  }

  function buildPetCard(pet) {
    var imgSrc = pet.image_url || '/images/logo.png';
    return '<a href="' + escapeHtml(pet.url) + '" class="pawbot-pet-card" target="_blank">' +
      '<img src="' + escapeHtml(imgSrc) + '" alt="' + escapeHtml(pet.name) + '" class="pawbot-pet-card-img" onerror="this.src=\'/images/logo.png\'">' +
      '<div class="pawbot-pet-card-info">' +
        '<div class="pawbot-pet-card-name">' + escapeHtml(pet.name) + '</div>' +
        '<div class="pawbot-pet-card-breed">' + escapeHtml(pet.breed || '') + ' · ' + escapeHtml(pet.species || '') + ' · ' + escapeHtml(pet.size || '') + '</div>' +
        '<div class="pawbot-pet-card-desc">' + escapeHtml(pet.description || '') + '</div>' +
      '</div>' +
    '</a>';
  }

  /* ---- Send message ---- */
  var isSending = false;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var text = input.value.trim();
    if (!text || isSending) return;

    isSending = true;
    input.value = '';
    addUserMessage(text);
    showTyping();

    fetch(CHATBOT_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({ message: text }),
    })
    .then(function (res) {
      if (!res.ok) throw new Error('Server error');
      return res.json();
    })
    .then(function (data) {
      hideTyping();

      var replyHtml = formatReply(data.reply || '');

      if (data.pets && data.pets.length > 0) {
        replyHtml += '<div style="margin-top:0.5rem;">';
        for (var i = 0; i < data.pets.length; i++) {
          replyHtml += buildPetCard(data.pets[i]);
        }
        replyHtml += '</div>';
      }

      addBotMessage(replyHtml);
    })
    .catch(function () {
      hideTyping();
      addBotMessage('Oops! Something went wrong. Please try again. 🐾');
    })
    .finally(function () {
      isSending = false;
      input.focus();
    });
  });
})();
