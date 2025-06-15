document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessagesContainer = document.getElementById('chatbot-messages');

    let isChatbotOpen = false;

    const n8nWebhookUrl = 'http://localhost:5678/webhook/f2482085-8dad-4279-94e9-ec2e16ae3ef4/chat';


    // Function to format current time for timestamp
    function getCurrentTime() {
        return new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Function to add a message to the chat window
    function addMessageToChat(text, sender, isHTML = false) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', sender === 'user' ? 'user-message' : 'bot-message');

        const messageParagraph = document.createElement('p');
        messageParagraph.classList.add('mb-0');

        // Bagian ini membutuhkan 'isHTML' untuk didefinisikan
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

    // Toggle chatbot window
    chatbotToggle.addEventListener('click', () => {
        isChatbotOpen = !isChatbotOpen;
        if (isChatbotOpen) {
            chatbotWindow.classList.remove('d-none');
            chatbotWindow.style.opacity = '1';
            chatbotWindow.style.transform = 'translateY(0) scale(1)';
            chatbotToggle.innerHTML = '<i class="bi bi-x-lg fs-3"></i>'; // Change to close icon
            chatbotInput.focus();
        } else {
            chatbotWindow.style.opacity = '0';
            chatbotWindow.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => { // Wait for transition to finish before adding d-none
                chatbotWindow.classList.add('d-none');
            }, 300);
            chatbotToggle.innerHTML = '<i class="bi bi-robot fs-3"></i>'; // Change back to bot icon
        }
    });

    // Close chatbot window with 'X' button
    chatbotClose.addEventListener('click', () => {
        isChatbotOpen = false;
        chatbotWindow.style.opacity = '0';
        chatbotWindow.style.transform = 'translateY(20px) scale(0.95)';
        setTimeout(() => {
            chatbotWindow.classList.add('d-none');
        }, 300);
        chatbotToggle.innerHTML = '<i class="bi bi-robot fs-3"></i>';
    });

    // Handle sending a message
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
                    // n8n akan menerima ini sebagai { "json": { "chatInput": "..." } }
                    body: JSON.stringify({
                        chatInput: messageText,
                        sessionId: 'user-session-123', // Anda bisa membuat ini dinamis
                        action: 'get_schedule' // Sesuai dengan logika filter Anda di n8n
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // n8n biasanya mengembalikan array dari eksekusi, kita ambil item pertama
                const responseData = await response.json();

                // Hapus indikator mengetik sebelum menampilkan pesan bot
                removeTypingIndicator();

                // Dapatkan teks jawaban dari output LLM Chain Anda
                // Berdasarkan workflow Anda, outputnya ada di properti 'text'
                const botReply = responseData.text;

                if (botReply) {
                    const formattedReply = botReply.replace(/\n/g, '<br>');

                    // Kita perlu fungsi addMessageToChat yang bisa handle HTML
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

            // --- Placeholder for Bot Response ---
            // In a real application, you would send messageText to a backend
            // and then display the bot's response.
            // setTimeout(() => {
            //     // Simulate different bot responses based on input for demo
            //     if (messageText.toLowerCase().includes("jadwal dokter")) {
            //         addMessageToChat(
            //             "Untuk informasi jadwal dokter, silakan kunjungi halaman jadwal di website kami atau hubungi call center.",
            //             "bot");
            //     } else if (messageText.toLowerCase().includes("alamat")) {
            //         addMessageToChat(
            //             "RSHS Bandung beralamat di Jl. Pasteur No.38, Pasteur, Kec. Sukajadi, Kota Bandung, Jawa Barat 40161.",
            //             "bot");
            //     } else if (messageText.toLowerCase().includes("layanan")) {
            //         addMessageToChat(
            //             "Kami memiliki berbagai layanan unggulan. Bisa sebutkan layanan spesifik yang Anda cari?",
            //             "bot");
            //     } else if (messageText.toLowerCase().match(/halo|hai|hi/gi)) {
            //         addMessageToChat("Halo! Ada yang bisa saya bantu?", "bot");
            //     } else {
            //         addMessageToChat(
            //             "Terima kasih atas pesan Anda. Saat ini saya adalah bot sederhana. Untuk bantuan lebih lanjut, tim kami akan segera merespons jika ini adalah sesi live chat sungguhan.",
            //             "bot");
            //     }
            // }, 1000 + Math.random() * 1000); // Simulate network delay
            // --- End Placeholder ---
        }
    }

    chatbotSend.addEventListener('click', handleSendMessage);

    // Allow sending message with Enter key
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission if it's in a form
            handleSendMessage();
        }
    });

    // Optional: Add a few more example bot messages for a more complete look on load
    // setTimeout(() => {
    //     addMessageToChat("Anda dapat menanyakan tentang jadwal dokter, lokasi, atau layanan kami.", "bot");
    // }, 1500);
});
