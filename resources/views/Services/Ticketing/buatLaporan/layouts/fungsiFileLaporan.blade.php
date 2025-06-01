<script src="{{ asset('assets/js/ticketing/buatLaporan/validation.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/buktiPendukungUI.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/formSubmitHandler.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Ambil Elemen DOM ---
        const buktiPendukungFileInput = document.getElementById('buktiPendukungFile');
        const buktiPendukungDropAreaLabel = document.getElementById('buktiPendukungDropZone');
        let uploadBoxContent = null;
        if (buktiPendukungDropAreaLabel) {
            uploadBoxContent = buktiPendukungDropAreaLabel.querySelector('.upload-box-content');
            if (!uploadBoxContent) {
                console.error("Elemen .upload-box-content TIDAK ditemukan di dalam #buktiPendukungDropZone!");
            }
        } else {
            console.error("Elemen #buktiPendukungDropZone TIDAK ditemukan!");
        }
        const buktiErrorContainer = document.getElementById('buktiPendukungFileErrors');

        // Array GLOBAL untuk menyimpan file bukti pendukung yang valid dari modul UI
        // Modul buktiPendukungUI.js akan memanipulasi array ini melalui referensi.
        let validBuktiPendukungFilesGlobal = [];

        const originalUploadBoxHTMLGlobal = `
            <div class="initial-prompt text-center">
                <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem;"></i>
                <p class="mt-2 mb-0 upload-box-text">Klik untuk upload <span class="fw-light">atau drag and drop</span></p>
                <small class="text-muted upload-box-hint">Format: JPG, PNG, PDF (Maks. 5MB).</small>
            </div>`;

        const formPengaduan = document.getElementById('formPengaduan');
        let submitButton = null;
        if (formPengaduan) {
            submitButton = formPengaduan.querySelector('button[type="submit"]');
            if (!submitButton) {
                console.error("Tombol submit TIDAK ditemukan di dalam #formPengaduan!");
            }
        } else {
            console.error("Form #formPengaduan TIDAK ditemukan!");
        }
        const formMessageDiv = document.getElementById('formMessage');
        if (!formMessageDiv) {
            console.error("Elemen #formMessage TIDAK ditemukan!");
        }

        // Variabel Global untuk route dan token (akan di-pass ke handler)
        const csrfTokenGlobal = '{{ csrf_token() }}';
        const uploadFileRouteGlobal = '{{ route('ticketing.upload-file') }}';
        const storeLaporanRouteGlobal = '{{ route('ticketing.store-laporan') }}';

        // --- Inisialisasi Modul UI Bukti Pendukung ---
        if (buktiPendukungFileInput && buktiPendukungDropAreaLabel && uploadBoxContent &&
            typeof initBuktiPendukungUI === 'function') {
            initBuktiPendukungUI(
                buktiPendukungFileInput,
                buktiPendukungDropAreaLabel,
                uploadBoxContent,
                buktiErrorContainer,
                originalUploadBoxHTMLGlobal,
                validBuktiPendukungFilesGlobal // Pass array global sebagai referensi
            );
        } else {
            let missing = [];
            if (!buktiPendukungFileInput) missing.push("#buktiPendukungFile");
            if (!buktiPendukungDropAreaLabel) missing.push("#buktiPendukungDropZone");
            if (!uploadBoxContent) missing.push(".upload-box-content in #buktiPendukungDropZone");
            if (typeof initBuktiPendukungUI !== 'function') missing.push("fungsi initBuktiPendukungUI()");
            console.warn(
                `Satu atau lebih elemen/fungsi UI untuk Bukti Pendukung tidak ditemukan: ${missing.join(', ')}. Inisialisasi initBuktiPendukungUI dilewati.`
            );
        }

        // --- Fungsi Getter untuk File Bukti Pendukung dari Modul UI ---
        // Ini dipanggil oleh formSubmitHandler untuk mendapatkan file yang akan diunggah
        function getValidBuktiPendukungFilesFromMain() {
            // validBuktiPendukungFilesGlobal diisi/dimanipulasi oleh modul buktiPendukungUI.js
            // melalui referensi array yang di-pass saat initBuktiPendukungUI.
            return Array.isArray(validBuktiPendukungFilesGlobal) ? validBuktiPendukungFilesGlobal : [];
        }

        // --- Fungsi untuk Reset UI Setelah Sukses Submit ---
        // Ini dipanggil oleh formSubmitHandler setelah laporan berhasil disimpan
        function resetAllUIAfterSuccess() {
            if (formPengaduan) formPengaduan.reset();

            // Mengosongkan array validBuktiPendukungFilesGlobal
            // Karena modul UI memanipulasi array ini via referensi, ini akan mengosongkannya juga di sana.
            if (Array.isArray(validBuktiPendukungFilesGlobal)) {
                validBuktiPendukungFilesGlobal.length = 0;
            } else {
                validBuktiPendukungFilesGlobal = []; // Jika karena suatu hal bukan array, reset jadi array
            }


            // Panggil renderBuktiPendukungUI dari modul UI untuk mereset tampilan file
            if (typeof renderBuktiPendukungUI === 'function') {
                renderBuktiPendukungUI();
            } else {
                console.warn("Fungsi renderBuktiPendukungUI tidak ditemukan untuk mereset UI bukti pendukung.");
            }

            if (buktiErrorContainer) buktiErrorContainer.innerHTML = '';
            // Form message akan diurus oleh formSubmitHandler (dihilangkan setelah timeout)
        }

        // --- Inisialisasi Form Submit Handler ---
        if (formPengaduan && submitButton && formMessageDiv && typeof setupFormSubmitHandler === 'function') {
            setupFormSubmitHandler(
                formPengaduan,
                submitButton,
                formMessageDiv,
                getValidBuktiPendukungFilesFromMain, // Fungsi getter untuk file
                csrfTokenGlobal,
                uploadFileRouteGlobal,
                storeLaporanRouteGlobal,
                resetAllUIAfterSuccess // Fungsi callback untuk reset UI
            );
        } else {
            let missing = [];
            if (!formPengaduan) missing.push("#formPengaduan");
            if (!submitButton) missing.push("tombol submit di #formPengaduan");
            if (!formMessageDiv) missing.push("#formMessage");
            if (typeof setupFormSubmitHandler !== 'function') missing.push("fungsi setupFormSubmitHandler()");
            console.error(
                `Satu atau lebih elemen/fungsi krusial untuk form submit tidak ditemukan: ${missing.join(', ')}. Setup FormSubmitHandler dilewati.`
            );
        }
    });
</script>
