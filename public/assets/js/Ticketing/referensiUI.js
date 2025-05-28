function setupRefTicketUI(refTicketFileInput, refTicketFileInfo) {
    if (refTicketFileInput && refTicketFileInfo) {
        refTicketFileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            refTicketFileInfo.innerHTML = '';
            if (file) {
                // Langsung panggil fungsi global
                const validationResult = validateFileGlobal(file);
                if (validationResult.valid) {
                    refTicketFileInfo.innerHTML =
                        `File terpilih: <strong>${file.name}</strong> (${(file.size / 1024).toFixed(1)} KB)`;
                } else {
                    refTicketFileInfo.innerHTML =
                        `<p class="text-danger mb-0">${validationResult.message}</p>`;
                    refTicketFileInput.value = '';
                }
            }
        });
    } else {
        if (!refTicketFileInput) console.error("setupRefTicketUI: Elemen input 'refTicketFile' TIDAK ditemukan!");
        if (!refTicketFileInfo) console.error("setupRefTicketUI: Elemen div 'refTicketFileInfo' TIDAK ditemukan!");
    }
}
