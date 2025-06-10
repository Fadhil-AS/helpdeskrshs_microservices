console.log('fungsiModalEdit.js: File is being parsed.');

$(document).ready(function() {
    console.log('fungsiModalEdit.js: Document is ready, jQuery is available.');

    $(document).on('click', '.edit-complaint-btn', function() {
        var complaintId = $(this).data('id');
        var editModal = $('#editModal');
        var modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal'));

        console.log('Edit button clicked for ID:', complaintId);

        var fetchUrl = typeof detailUrlTemplate !== 'undefined' ? detailUrlTemplate.replace(':id', complaintId) : '';

        if (!fetchUrl) {
            console.error('detailUrlTemplate is not defined.');
            alert('Error: URL template for fetching detail is not defined.');
            return;
        }

        $.ajax({
            url: fetchUrl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data received for editing:', data);

                var updateUrl = typeof updateUrlTemplate !== 'undefined' ? updateUrlTemplate.replace(':id', data.ID_COMPLAINT) : '';
                $('#editComplaintForm').attr('action', updateUrl);

                editModal.find('.modal-body small.text-muted').text('ID: ' + (data.ID_COMPLAINT || '-'));
                $('#editStatus').val(data.STATUS || '');
                editModal.find('#editModalLabel').text('Edit Pengaduan');

                $('#editJudul').val(data.JUDUL_COMPLAINT || '');
                var tglComplaint = data.TGL_COMPLAINT ? new Date(data.TGL_COMPLAINT).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }) : '-';
                $('#editTanggalPengaduan').val(tglComplaint);

                $('input[name="gradingOptions"]').prop('checked', false);
                if (data.GRANDING) {
                    var gradingValue = data.GRANDING;
                    $(`input[name="gradingOptions"][value="${gradingValue}"]`).prop('checked', true);
                }

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
                var tglEvaluasi = data.TGL_EVALUASI ? new Date(data.TGL_EVALUASI).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                $('#editTanggalEvaluasi').val(tglEvaluasi);
                var tglSelesai = data.TGL_SELESAI ? new Date(data.TGL_SELESAI).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                $('#editTanggalSelesai').val(tglSelesai);

                $('#editIdPenyelesaian').val(data.ID_PENYELESAIAN || '');

                $('#editKlarifikasiUnitContent').val(data.KLARIFIKASI_UNIT || '');
                $('#editTindakLanjutHumasContent').val(data.TINDAK_LANJUT_HUMAS || '');

                var buktiContainer = $('#editBuktiKlarifikasiContainer');
                buktiContainer.html('');

                if (data.file_list && data.file_list.length > 0) {

                    data.file_list.forEach(function(filePath) {
                        if (!filePath || filePath.trim() === '') return;

                        var fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + filePath.trim();
                        var fileName = filePath.split(/[\\/]/).pop();

                        var buktiHtml = '<div class="file-klarifikasi-item" style="max-width: 150px;">';

                        if (/\.(jpeg|jpg|gif|png)$/i.test(fileName)) {
                            buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}">
                                            <img src="${fileUrl}" alt="Bukti Foto" class="img-fluid rounded mb-1" style="height: 100px; width: 100%; object-fit: cover;">
                                            <small class="d-block text-truncate">${fileName}</small>
                                          </a>`;
                        } else {
                            buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}">
                                            <i class="bi bi-file-earmark-text display-4 text-secondary mb-1"></i>
                                            <small class="d-block text-truncate">${fileName}</small>
                                          </a>`;
                        }
                        buktiHtml += '</div>';

                        buktiContainer.append(buktiHtml);
                    });

                } else {
                    buktiContainer.html('<p class="text-muted p-2">Tidak ada file bukti klarifikasi.</p>');
                }

                modalInstance.show();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error fetching edit data:', status, error, xhr.responseText);
                alert('Gagal memuat data pengaduan untuk diedit.');
            }
        });
    });

    $('#editModal').on('hidden.bs.modal', function() {
        $('#editComplaintForm')[0].reset();
        $('#editComplaintForm').attr('action', '');
        $('#editBuktiKlarifikasiContainer').html('<p class="text-muted">Tidak ada file bukti klarifikasi.</p>');

        if ($.fn.selectpicker) {
            $('#editModal .selectpicker').val('').selectpicker('refresh');
        }
    });
});
