<button id="chatbot-toggle" class="btn rounded-circle position-fixed bottom-0 end-0 shadow-lg" style=""
    aria-label="Toggle Chatbot">
    <i class="bi bi-robot fs-3"></i>
</button>

<div id="chatbot-window" class="card position-fixed bottom-0 end-0 m-3 shadow-lg d-none" style="">
    <div class="card-header text-white d-flex justify-content-between align-items-center"
        style="background-color: #00B9AD; border: none;">
        <h5 class="mb-2 fw-bold">RSHS Virtual Assistant</h5>
        <button id="chatbot-close" type="button" class="btn-close btn-close-white" aria-label="Close Chatbot"></button>
    </div>
    <div id="chatbot-messages" class="card-body overflow-auto chatbot-messages-padding" style="">
        <div class="chat-message bot-message">
            <p class="mb-0">Halo! Ada yang bisa saya bantu terkait layanan RSHS Bandung?</p>
            <small class="text-muted timestamp">Baru saja</small>
        </div>
    </div>
    <div class="card-footer bg-light border-top p-2">
        <div class="input-group">
            <input type="text" id="chatbot-input" class="form-control" placeholder="Ketik pesan Anda..."
                aria-label="Message Input">
            <button id="chatbot-send" class="btn" style="background-color: #00B9AD; color: white;">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

@include('Services.Chatbot.layouts.footerChatbot')
