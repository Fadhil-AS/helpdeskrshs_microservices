<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatbotWindow = document.getElementById('chatbot-window');
        const chatbotClose = document.getElementById('chatbot-close');
        const chatbotSend = document.getElementById('chatbot-send');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotMessagesContainer = document.getElementById('chatbot-messages');

        let isChatbotOpen = false;

        // Function to format current time for timestamp
        function getCurrentTime() {
            return new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Function to add a message to the chat window
        function addMessageToChat(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', sender === 'user' ? 'user-message' : 'bot-message');

            const messageParagraph = document.createElement('p');
            messageParagraph.classList.add('mb-0');
            messageParagraph.textContent = text;

            const timestampSmall = document.createElement('small');
            timestampSmall.classList.add('text-muted', 'timestamp');
            timestampSmall.textContent = getCurrentTime();

            messageDiv.appendChild(messageParagraph);
            messageDiv.appendChild(timestampSmall);

            // Add animation class
            messageDiv.classList.add('new-message-animation');

            chatbotMessagesContainer.appendChild(messageDiv);
            chatbotMessagesContainer.scrollTop = chatbotMessagesContainer.scrollHeight; // Auto-scroll to bottom

            // Remove animation class after it plays to allow re-triggering
            setTimeout(() => {
                messageDiv.classList.remove('new-message-animation');
            }, 300);
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
        function handleSendMessage() {
            const messageText = chatbotInput.value.trim();
            if (messageText) {
                addMessageToChat(messageText, 'user');
                chatbotInput.value = ''; // Clear input

                // --- Placeholder for Bot Response ---
                // In a real application, you would send messageText to a backend
                // and then display the bot's response.
                setTimeout(() => {
                    // Simulate different bot responses based on input for demo
                    if (messageText.toLowerCase().includes("jadwal dokter")) {
                        addMessageToChat(
                            "Untuk informasi jadwal dokter, silakan kunjungi halaman jadwal di website kami atau hubungi call center.",
                            "bot");
                    } else if (messageText.toLowerCase().includes("alamat")) {
                        addMessageToChat(
                            "RSHS Bandung beralamat di Jl. Pasteur No.38, Pasteur, Kec. Sukajadi, Kota Bandung, Jawa Barat 40161.",
                            "bot");
                    } else if (messageText.toLowerCase().includes("layanan")) {
                        addMessageToChat(
                            "Kami memiliki berbagai layanan unggulan. Bisa sebutkan layanan spesifik yang Anda cari?",
                            "bot");
                    } else if (messageText.toLowerCase().match(/halo|hai|hi/gi)) {
                        addMessageToChat("Halo! Ada yang bisa saya bantu?", "bot");
                    } else {
                        addMessageToChat(
                            "Terima kasih atas pesan Anda. Saat ini saya adalah bot sederhana. Untuk bantuan lebih lanjut, tim kami akan segera merespons jika ini adalah sesi live chat sungguhan.",
                            "bot");
                    }
                }, 1000 + Math.random() * 1000); // Simulate network delay
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
</script>
