document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessagesContainer = document.getElementById('chatbot-messages');

    let isChatbotOpen = false;

    const n8nWebhookUrl = 'http://localhost:5678/webhook/f2482085-8dad-4279-94e9-ec2e16ae3ef4/chat';

    function getCurrentTime() {
        return new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function addMessageToChat(text, sender, isHTML = false) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', sender === 'user' ? 'user-message' : 'bot-message');

        const messageParagraph = document.createElement('p');
        messageParagraph.classList.add('mb-0');

        if (isHTML) {
            messageParagraph.innerHTML = text;
        } else {
            messageParagraph.textContent = text;
        }

        const timestampSmall = document.createElement('small');
        timestampSmall.classList.add('text-muted', 'timestamp');
        timestampSmall.textContent = getCurrentTime();

        messageDiv.appendChild(messageParagraph);
        messageDiv.appendChild(timestampSmall);
        messageDiv.classList.add('new-message-animation');
        chatbotMessagesContainer.appendChild(messageDiv);
        chatbotMessagesContainer.scrollTop = chatbotMessagesContainer.scrollHeight;

        setTimeout(() => {
            messageDiv.classList.remove('new-message-animation');
        }, 300);
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.classList.add('chat-message', 'bot-message');
        typingDiv.innerHTML = `<p class="mb-0"><em>Virtual assistant sedang mengetik...</em></p>`;
        chatbotMessagesContainer.appendChild(typingDiv);
        chatbotMessagesContainer.scrollTop = chatbotMessagesContainer.scrollHeight;
    }

    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    chatbotToggle.addEventListener('click', () => {
        isChatbotOpen = !isChatbotOpen;
        if (isChatbotOpen) {
            chatbotWindow.classList.remove('d-none');
            chatbotWindow.style.opacity = '1';
            chatbotWindow.style.transform = 'translateY(0) scale(1)';
            chatbotToggle.innerHTML = '<i class="bi bi-x-lg fs-3"></i>';
            chatbotInput.focus();
        } else {
            chatbotWindow.style.opacity = '0';
            chatbotWindow.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => {
                chatbotWindow.classList.add('d-none');
            }, 300);
            chatbotToggle.innerHTML = '<i class="bi bi-robot fs-3"></i>';
        }
    });

    chatbotClose.addEventListener('click', () => {
        isChatbotOpen = false;
        chatbotWindow.style.opacity = '0';
        chatbotWindow.style.transform = 'translateY(20px) scale(0.95)';
        setTimeout(() => {
            chatbotWindow.classList.add('d-none');
        }, 300);
        chatbotToggle.innerHTML = '<i class="bi bi-robot fs-3"></i>';
    });

    async function handleSendMessage() {
        const messageText = chatbotInput.value.trim();
        if (messageText) {
            addMessageToChat(messageText, 'user');
            chatbotInput.value = '';
            showTypingIndicator();

            try {
                const response = await fetch(n8nWebhookUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        chatInput: messageText,
                        sessionId: 'user-session-123',
                        action: 'get_schedule'
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const responseData = await response.json();

                removeTypingIndicator();

                const botReply = responseData.text;

                if (botReply) {
                    const formattedReply = botReply.replace(/\n/g, '<br>');

                    addMessageToChat(formattedReply, 'bot', true);

                } else {
                    addMessageToChat("Maaf, terjadi kesalahan saat memproses jawaban dari bot.", "bot");
                    console.error("Format data respons dari n8n tidak terduga:", responseData);
                }

            } catch (error) {
                removeTypingIndicator();
                console.error('Gagal mengirim pesan ke chatbot:', error);
                addMessageToChat("Maaf, saya tidak dapat terhubung ke server saat ini. Silakan coba lagi nanti.", "bot");
            }
        }
    }

    chatbotSend.addEventListener('click', handleSendMessage);

    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSendMessage();
        }
    });
});
