function toggleChat() {
    const windowEl = document.getElementById('chatbotWindow');
    windowEl.style.display = windowEl.style.display === 'flex' ? 'none' : 'flex';
}

const chatContainer = document.getElementById('chat-container');
let loadingEl = null;

function addMessage(message, type) {
    const div = document.createElement('div');
    div.className = 'chat-message ' + (type === 'user' ? 'user-message' : 'bot-message');
    div.innerText = message;
    chatContainer.appendChild(div);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showLoading() {
    loadingEl = document.createElement('div');
    loadingEl.className = 'chat-message bot-message';
    loadingEl.innerText = 'Mengetik...';
    chatContainer.appendChild(loadingEl);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function removeLoading() {
    if (loadingEl) {
        loadingEl.remove();
        loadingEl = null;
    }
}

document.getElementById('chat-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    if (!message) return;

    addMessage(message, 'user');
    input.value = '';

    showLoading();

    try {
        const res = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message })
        });

        const data = await res.json();
        removeLoading();
        addMessage(data.reply, 'bot');
    } catch (error) {
        removeLoading();
        addMessage('Terjadi kesalahan saat menghubungi chatbot.', 'bot');
    }
});
