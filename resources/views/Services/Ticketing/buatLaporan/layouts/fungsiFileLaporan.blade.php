<script src="{{ asset('assets/js/ticketing/buatLaporan/validation.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/validationNoTelpon.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/buktiPendukungUI.js') }}"></script>
<script src="{{ asset('assets/js/ticketing/buatLaporan/formSubmitHandler.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const csrfTokenGlobal = '{{ csrf_token() }}';
        const uploadFileRouteGlobal = '{{ route('ticketing.upload-file') }}';
        const storeLaporanRouteGlobal = '{{ route('ticketing.store-laporan') }}';

        if (buktiPendukungFileInput && buktiPendukungDropAreaLabel && uploadBoxContent &&
            typeof initBuktiPendukungUI === 'function') {
            initBuktiPendukungUI(
                buktiPendukungFileInput,
                buktiPendukungDropAreaLabel,
                uploadBoxContent,
                buktiErrorContainer,
                originalUploadBoxHTMLGlobal,
                validBuktiPendukungFilesGlobal
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

        function getValidBuktiPendukungFilesFromMain() {
            return Array.isArray(validBuktiPendukungFilesGlobal) ? validBuktiPendukungFilesGlobal : [];
        }

        function resetAllUIAfterSuccess() {
            if (formPengaduan) formPengaduan.reset();

            if (Array.isArray(validBuktiPendukungFilesGlobal)) {
                validBuktiPendukungFilesGlobal.length = 0;
            } else {
                validBuktiPendukungFilesGlobal = [];
            }

            if (typeof renderBuktiPendukungUI === 'function') {
                renderBuktiPendukungUI();
            } else {
                console.warn("Fungsi renderBuktiPendukungUI tidak ditemukan untuk mereset UI bukti pendukung.");
            }

            if (buktiErrorContainer) buktiErrorContainer.innerHTML = '';
        }

        if (formPengaduan && submitButton && formMessageDiv && typeof setupFormSubmitHandler === 'function') {
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
