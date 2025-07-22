<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Floating button */
        .chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0dbbcb;
            /* C20 M0 Y100 K0 */
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        /* Chat window */
        .chatbot-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 360px;
            height: 500px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background-color: #24C2B2;
            /* C74 M0 Y21 K0 */
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
        }

        .chat-container {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f4f4f4;
        }

        .chat-message {
            max-width: 70%;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 15px;
            clear: both;
            word-wrap: break-word;
        }

        .user-message {
            background-color: #0dbbcb;
            /* C75 M0 Y45 K0 */
            float: right;
            text-align: right;
            color: white;
        }

        .bot-message {
            background-color: #e0e0e0;
            /* abu-abu */
            float: left;
        }

        .chat-form-container {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ccc;
        }

        #message-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }

        .send-button {
            padding: 10px 16px;
            background-color: #0dbbcb;
            /* C20 M0 Y100 K0 */
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        .send-button:hover {
            background-color: #0c929e;
        }
    </style>
</head>

<body>
    <!-- Floating toggle button -->
    <button class="chatbot-toggle" onclick="toggleChat()">💬</button>

    <!-- Chatbot window -->
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chat-header">Asisten Bot</div>
        <div class="chat-container" id="chat-container">
            <!-- Chat messages appear here -->
        </div>
        <div class="chat-form-container">
            <form id="chat-form" style="display: flex; width: 100%;">
                <input type="text" id="message-input" placeholder="Tulis pesan..." autocomplete="off" required />
                <button type="submit" class="send-button">Kirim</button>
            </form>
        </div>
    </div>


    <script>
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
                    body: JSON.stringify({
                        message
                    })
                });

                const data = await res.json();
                removeLoading();
                addMessage(data.reply, 'bot');
            } catch (error) {
                removeLoading();
                addMessage('Terjadi kesalahan saat menghubungi chatbot.', 'bot');
            }
        });
    </script>
</body>

</html>
