<script src="{{ asset('assets/js/ticketing/buatLaporan/validation.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/buktiPendukungUI.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/formSubmitHandler.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buktiPendukungFileInput = document.getElementById('buktiPendukungFile');
        const buktiPendukungDropAreaLabel = document.getElementById(
            'buktiPendukungDropZone'); // Target Drop Zone
        let uploadBoxContent = null;
        if (buktiPendukungDropAreaLabel) {
            uploadBoxContent = buktiPendukungDropAreaLabel.querySelector('.upload-box-content');
            if (!uploadBoxContent) {
                console.error(
                    "fungsiFileLaporan.blade.php: .upload-box-content TIDAK ditemukan di dalam #buktiPendukungDropZone!"
                );
            }
        } else {
            console.error("fungsiFileLaporan.blade.php: #buktiPendukungDropZone TIDAK ditemukan!");
        }
        const buktiErrorContainer = document.getElementById('buktiPendukungFileErrors');

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
        }
        const formMessageDiv = document.getElementById('formMessage');
        const csrfTokenGlobal = '{{ csrf_token() }}';
        const uploadFileRouteGlobal = '{{ route('ticketing.upload-file') }}';
        const storeLaporanRouteGlobal = '{{ route('ticketing.store-laporan') }}';

        // --- Inisialisasi Modul UI ---
        if (buktiPendukungFileInput && buktiPendukungDropAreaLabel && uploadBoxContent) {
            initBuktiPendukungUI(
                buktiPendukungFileInput,
                buktiPendukungDropAreaLabel,
                uploadBoxContent,
                buktiErrorContainer,
                originalUploadBoxHTMLGlobal,
                validBuktiPendukungFilesGlobal
            );
        } else {
            console.warn(
                "Satu atau lebih elemen UI untuk Bukti Pendukung tidak ditemukan. Inisialisasi initBuktiPendukungUI dilewati."
            );
        }

        // --- Fungsi Getter untuk File Bukti Pendukung ---
        function getValidBuktiPendukungFilesFromMain() {
            // _validBuktiPendukungFilesInternal di fileBuktiPendukungUI.js adalah referensi ke validBuktiPendukungFilesGlobal
            return Array.isArray(validBuktiPendukungFilesGlobal) ? validBuktiPendukungFilesGlobal : [];
        }

        // --- Fungsi untuk Reset UI Setelah Sukses ---
        function resetAllUIAfterSuccess() {
            if (formPengaduan) formPengaduan.reset();

            // Mengosongkan array dengan tetap menjaga referensi jika ada bagian lain yang masih menggunakannya
            // Atau jika _validBuktiPendukungFilesInternal di modul lain benar-benar menunjuk ke sini:
            validBuktiPendukungFilesGlobal.length = 0;

            if (typeof renderBuktiPendukungUI === 'function') {
                renderBuktiPendukungUI();
            } else {
                console.warn("Fungsi renderBuktiPendukungUI tidak ditemukan untuk mereset UI bukti pendukung.");
            }

            if (buktiErrorContainer) buktiErrorContainer.innerHTML = '';
        }

        // --- Inisialisasi Form Submit Handler ---
        if (formPengaduan && submitButton) {
            setupFormSubmitHandler(
                formPengaduan,
                submitButton,
                formMessageDiv,
                getValidBuktiPendukungFilesFromMain,
                csrfTokenGlobal,
                uploadFileRouteGlobal,
                storeLaporanRouteGlobal,
                resetAllUIAfterSuccess
            );
        } else {
            console.error(
                "Form 'formPengaduan' atau tombol submit tidak ditemukan. Setup FormSubmitHandler dilewati."
            );
        }
    });
</script>
