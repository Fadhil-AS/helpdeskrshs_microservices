console.log('fungsiModalEdit.js: File is being parsed.');

$(document).ready(function () {
    console.log('fungsiModalEdit.js: Document is ready, jQuery is available.');

    function setDependentFieldsReadOnly(isLocked) {
        $('.readonly-on-helpdesk').each(function () {
            const el = $(this);
            el.is('input, textarea') ? el.prop('readonly', isLocked) : el.prop('disabled', isLocked);
            el.toggleClass('form-control-readonly', isLocked);
        });
    }

    function applyFieldLockLogic(initialMediaText = '') {
        const mediaDropdown = $('#editIdJenisMedia');
        const selectedMediaText = mediaDropdown.find('option:selected').text().trim();
        setDependentFieldsReadOnly(selectedMediaText === 'Website Helpdesk');

        mediaDropdown.prop('disabled', false).removeClass('form-control-readonly');
        mediaDropdown.find('option').prop('disabled', false);

        if (initialMediaText === 'Website Helpdesk') {
            mediaDropdown.prop('disabled', true).addClass('form-control-readonly');
        } else if (initialMediaText === 'SMS Hotline') {
            mediaDropdown.find('option').each(function () {
                if ($(this).text().trim() === 'Website Helpdesk') {
                    $(this).prop('disabled', true);
                }
            });
        }
    }

    function renderExistingFiles(containerSelector, fileList, allowDelete = true) {
        const container = $(containerSelector);
        container.html('');

        const fileWrapper = $('<div class="file-preview-grid d-flex flex-wrap align-items-start mb-3"></div>');
        container.append(fileWrapper);

        if (!fileList || fileList.length === 0) {
            container.prepend('<p class="text-muted m-0">Tidak ada file.</p>');
        } else {
            fileList.forEach(filePath => {
                if (!filePath || filePath.trim() === '') return;
                const fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + filePath.trim();
                const fileName = filePath.split(/[\\/]/).pop();
                const isImage = /\.(jpe?g|png|gif|bmp|webp)$/i.test(fileName);

                const fileItem = $(`
                <div class="file-klarifikasi-item d-inline-block text-center me-2 mb-2">
                    <a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}">
                        ${isImage
                        ? `<img src="${fileUrl}" alt="${fileName}" class="file-thumbnail-img">`
                        : `<i class="bi bi-file-earmark-text display-4 text-secondary"></i>`
                    }
                    </a>
                    <small class="d-block text-truncate mt-1">${fileName}</small>
                    ${allowDelete
                        ? `<button type="button" class="btn btn-sm btn-outline-danger mt-1 w-100 btn-remove-existing-file" data-file-path="${filePath}">Hapus</button>`
                        : ''
                    }
                </div>
            `);

                fileWrapper.append(fileItem);
            });
        }

        if (allowDelete) {
            const previewContainer = $('<div id="newFilePreviewContainer" class="d-flex flex-wrap"></div>');
            fileWrapper.append(previewContainer);

            const uploadUI = $(`
            <div class="upload-box w-100" id="editPengaduanDropZone">
                <label for="FILE_PENGADUAN_input" class="w-100 text-center p-3 border rounded" style="cursor: pointer; background: #f1f3f5;">
                    <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                    <p class="m-0 mt-2">Klik atau drag & drop untuk menambah file baru</p>
                </label>
                <input type="file" id="FILE_PENGADUAN_input" name="new_files[]" class="d-none" multiple>
            </div>
        `);

            container.append(uploadUI);
        }
    }


    function setupNewFileUploadUI() {
        const fileInput = document.getElementById('FILE_PENGADUAN_input');
        const previewContainer = document.getElementById('newFilePreviewContainer');
        if (!fileInput || !previewContainer) return;

        fileInput.addEventListener('change', function () {
            previewContainer.innerHTML = '';
            const files = Array.from(fileInput.files);
            if (files.length === 0) return;

            files.forEach((file, index) => {
                const isImage = /\.(jpe?g|png|gif|bmp|webp)$/i.test(file.name);
                const reader = new FileReader();

                reader.onload = function (e) {
                    const preview = document.createElement('div');
                    preview.className = 'file-klarifikasi-item d-inline-block text-center me-2 mb-2';

                    preview.innerHTML = `
                    <a href="#" target="_blank" rel="noopener noreferrer" title="${file.name}">
                        ${isImage
                            ? `<img src="${e.target.result}" alt="${file.name}" class="file-thumbnail-img">`
                            : `<i class="bi bi-file-earmark-text display-4 text-secondary"></i>`
                        }
                    </a>
                    <small class="d-block text-truncate mt-1">${file.name}</small>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-1 w-100 btn-remove-new-file" data-index="${index}">Hapus</button>
                `;

                    previewContainer.appendChild(preview);
                };

                reader.readAsDataURL(file);
            });

            previewContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-new-file')) {
                    fileInput.value = '';
                    previewContainer.innerHTML = '';
                }
            }, { once: true });
        });
    }

    $(document).on('click', '.edit-complaint-btn', function () {
        const complaintId = $(this).data('id');
        const modal = $('#editModal');
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modal[0]);
        const fetchUrl = typeof detailUrlTemplate !== 'undefined' ? detailUrlTemplate.replace(':id', complaintId) : '';
        if (!fetchUrl) return;

        $.ajax({
            url: fetchUrl,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const updateUrl = typeof updateUrlTemplate !== 'undefined' ? updateUrlTemplate.replace(':id', data.ID_COMPLAINT) : '';
                $('#editComplaintForm').attr('action', updateUrl);
                modal.find('.modal-body small.text-muted').text('ID: ' + (data.ID_COMPLAINT || '-'));
                $('#editStatus').val(data.STATUS || '');
                $('#editModalLabel').text('Edit Pengaduan');
                $('#editJudul').val(data.JUDUL_COMPLAINT || '');

                function fixDate(val) {
                    return val ? new Date(val).toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'long', year: 'numeric'
                    }) : '-';
                }

                $('#editTanggalPengaduan').val(fixDate(data.TGL_COMPLAINT));
                $('#editTanggalEvaluasi').val(fixDate(data.TGL_EVALUASI));
                $('#editTanggalSelesai').val(fixDate(data.TGL_SELESAI));

                $('input[name="gradingOptions"]').prop('checked', false);
                if (data.GRANDING) $(`input[name="gradingOptions"][value="${data.GRANDING}"]`).prop('checked', true);

                $('#editNamaPelapor').val(data.NAME || '');
                $('#editNoMedrec').val(data.NO_MEDREC || '');
                $('#editNoTelp').val(data.NO_TLPN || '');
                $('#editPetugasPelapor').val(data.PETUGAS_PELAPOR || '');
                $('#editIsiComplaint').val(data.ISI_COMPLAINT || '');
                $('#editPermasalahan').val(data.PERMASALAHAN || '');
                $('#editIdBagian').val(data.ID_BAGIAN || '');
                $('#editIdJenisLaporan').val(data.ID_JENIS_LAPORAN || '');
                $('#editIdJenisMedia').val(data.ID_JENIS_MEDIA || '');
                $('#editIdKlasifikasi').val(data.ID_KLASIFIKASI || '');
                $('#editPetugasEvaluasi').val(data.PETUGAS_EVALUASI || '');
                $('#editIdPenyelesaian').val(data.ID_PENYELESAIAN || '');
                $('#editKlarifikasiUnitContent').val(data.EVALUASI_COMPLAINT || '');
                $('#editTindakLanjutHumasContent').val(data.TINDAK_LANJUT_HUMAS || '');

                const badge = $('#editStatusBadge');
                const st = data.STATUS || '-';
                badge.text(st).removeClass().addClass('badge');
                badge.addClass({
                    'Open': 'bg-success',
                    'On Progress': 'bg-info',
                    'Menunggu Konfirmasi': 'bg-warning',
                    'Close': 'bg-danger text-light',
                    'Banding': 'bg-danger text-light'
                }[st] || 'bg-secondary');

                const mediaPengaduan = data.jenis_media?.JENIS_MEDIA.trim() || '';
                const allowDelete = mediaPengaduan !== 'Website Helpdesk';

                renderExistingFiles('#editPengaduanContainer', data.pengaduan_files, allowDelete);
                renderExistingFiles('#editBuktiKlarifikasiContainer', data.klarifikasi_files, allowDelete);

                applyFieldLockLogic(mediaPengaduan);

                if (allowDelete) {
                    setupNewFileUploadUI();
                }

                if (['Open', 'On Progress'].includes(data.STATUS)) {
                    $('#editStatus').css({ 'pointer-events': 'none', 'background-color': '#e9ecef' });
                } else {
                    $('#editStatus').css({ 'pointer-events': 'auto', 'background-color': '' });
                }

                modalInstance.show();
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Gagal memuat data pengaduan untuk diedit.');
            }
        });
    });

    $('#editIdJenisMedia').on('change', function () {
        const selectedMediaText = $(this).find('option:selected').text().trim();
        const isHelpdesk = selectedMediaText === 'Website Helpdesk';
        setDependentFieldsReadOnly(isHelpdesk);
        $('#editPengaduanDropZone').toggle(!isHelpdesk);
    });

    $('#editModal').on('click', '.btn-remove-existing-file', function () {
        const filePath = $(this).data('file-path');
        const fileItem = $(this).closest('.file-klarifikasi-item');
        const deletedInput = $('#deleted_files_input');

        let current = deletedInput.val() ? deletedInput.val().split(',') : [];
        if (!current.includes(filePath)) current.push(filePath);
        deletedInput.val(current.join(','));
        fileItem.fadeOut(300, () => fileItem.remove());
    });

    $('#editModal').on('hidden.bs.modal', function () {
        $('#editComplaintForm')[0].reset();
        $('#editComplaintForm').attr('action', '');
        $('#editBuktiKlarifikasiContainer, #editPengaduanContainer').html('<p class="text-muted">Tidak ada file.</p>');
        $('#deleted_files_input').val('');
        $('#editStatus').css({ 'pointer-events': 'auto', 'background-color': '' });

        setDependentFieldsReadOnly(false);
        const md = $('#editIdJenisMedia');
        md.prop('disabled', false).removeClass('form-control-readonly');
        md.find('option').prop('disabled', false);
    });
});
