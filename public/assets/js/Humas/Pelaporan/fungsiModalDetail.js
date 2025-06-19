console.log('fungsiModalDetail.js: File is being parsed.');
$(document).ready(function () {
    console.log('fungsiModalDetail.js: Document is ready, jQuery is available.');
    $('.view-detail-btn').on('click', function () {
        var complaintId = $(this).data('id');
        // var detailModal = $('#detailModal');
        var modalInstance = new bootstrap.Modal(document.getElementById('detailModal'));

        $.ajax({
            url: typeof detailUrlTemplate !== 'undefined' ? detailUrlTemplate.replace(':id', complaintId) : '',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log('Data diterima dari server:', data);

                $('#detailIdComplaint').text('ID: ' + (data.ID_COMPLAINT || '-'));
                $('#detailStatus').removeClass().addClass('badge').text(data.STATUS || 'N/A');
                if (data.STATUS === 'Open')
                    $('#detailStatus').addClass('bg-success');
                else if (data.STATUS === 'On Progress')
                    $('#detailStatus').addClass('bg-info');
                else if (data.STATUS === 'Menunggu Konfirmasi')
                    $('#detailStatus').addClass('bg-info');
                else if (data.STATUS === 'Close')
                    $('#detailStatus').addClass('bg-danger text-light');
                else if (data.STATUS === 'Banding')
                    $('#detailStatus').addClass('bg-danger text-light');
                else
                    $('#detailStatus').addClass('bg-secondary');
                $('#detailJudul').text(data.JUDUL_COMPLAINT || '-');

                var tglComplaint = data.TGL_COMPLAINT ? new Date(data.TGL_COMPLAINT).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric'}) : '-';

                $('#detailTanggalPengaduan').text(tglComplaint);
                $('#detailNoTelp').text(data.NO_TLPN || '-');
                $('#detailKlarifikasiStatus').removeClass().addClass('badge').text(data.EVALUASI_COMPLAINT || 'Belum');
                var klarifikasiStatusBadge = $('#detailKlarifikasiStatus');
                klarifikasiStatusBadge.removeClass().addClass('badge');

                if (data.EVALUASI_COMPLAINT && data.EVALUASI_COMPLAINT.trim() !== '') {
                    klarifikasiStatusBadge.text('Sudah').addClass('bg-info');
                } else {
                    klarifikasiStatusBadge.text('Belum').addClass('bg-danger text-light');
                }
                $('#detailNamaPelapor').text(data.NAME || '-');
                $('#detailGrading').removeClass().addClass('badge').text(data.GRANDING || 'Belum dipilih Grading');
                if (data.GRANDING === 'Merah')
                    $('#detailGrading').addClass('bg-danger text-light');
                else if (data.GRANDING === 'Kuning')
                    $('#detailGrading').addClass('bg-warning text-light');
                else if (data.GRANDING === 'Hijau')
                    $('#detailGrading').addClass('bg-success text-light');
                else
                    $('#detailGrading').addClass('bg-danger text-light');
                $('#detailNoMedrec').text(data.NO_MEDREC || '-');
                $('#detailUnitKerja').text(data.unit_kerja ? data.unit_kerja.NAMA_BAGIAN : '-');
                $('#detailMediaPengaduan').text(data.jenis_media ? data.jenis_media.JENIS_MEDIA : '-');
                $('#detailJenisLaporan').text(data.jenis_laporan ? data.jenis_laporan.JENIS_LAPORAN : '-');
                $('#detailKlasifikasiPengaduan').text(data.klasifikasi_pengaduan ? data.klasifikasi_pengaduan.KLASIFIKASI_PENGADUAN : '-');
                $('#detailPetugasPelapor').text(data.PETUGAS_PELAPOR || '-');
                $('#detailDeskripsiPengaduanContent').text(data.ISI_COMPLAINT || '-');
                $('#detailRangkumanPermasalahanContent').text(data.PERMASALAHAN || '-');

                $('#detailPetugasEvaluasi').text(data.PETUGAS_EVALUASI || '-');
                var tglEvaluasi = data.TGL_EVALUASI ? new Date(data.TGL_EVALUASI).toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                    $('#detailTanggalEvaluasi').text(tglEvaluasi);
                    $('#detailPenyelesaianPengaduan').text(data.penyelesaian_pengaduan ? data.penyelesaian_pengaduan.NAMA_PENYELESAIAN : '-');
                    var tglSelesai = data.TGL_SELESAI ? new Date(data.TGL_SELESAI).toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'long', year: 'numeric'
                    }) : '-';

                    $('#detailTanggalSelesai').text(tglSelesai);
                    $('#detailKlarifikasiUnitContent').val(data.EVALUASI_COMPLAINT || '');
                    var buktiKlarifikasiHtml = '<p class="text-muted">Tidak ada file bukti klarifikasi.</p>';
                    if (data.FILE_PENGADUAN) {
                        console.log('storageBaseUrl:', storageBaseUrl);
                        console.log('data.FILE_PENGADUAN:', data.FILE_PENGADUAN);
                        var fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + data.FILE_PENGADUAN;
                        var fileName = data.FILE_PENGADUAN.split(/[\\/]/).pop();
                        console.log('Constructed fileUrl:', fileUrl);
                        buktiKlarifikasiHtml = '<div class="file-klarifikasi-item" style="max-width: 150px;">';
                        if (/\.(jpeg|jpg|gif|png)$/i.test(fileName)) {
                            buktiKlarifikasiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}">
                                                        <img src="${fileUrl}" alt="Bukti Foto" class="img-fluid rounded mb-1">
                                                        <small class="d-block text-truncate">${fileName}</small>
                                                     </a>`;
                        } else if (/\.pdf$/i.test(fileName)) {
                            buktiKlarifikasiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}">
                                                        <i class="bi bi-file-earmark-pdf display-4 text-danger mb-1"></i>
                                                        <small class="d-block text-truncate">${fileName}</small>
                                                     </a>`;
                        } else {
                            buktiKlarifikasiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}">
                                                        <i class="bi bi-file-earmark-text display-4 text-secondary mb-1"></i>
                                                        <small class="d-block text-truncate">${fileName}</small>
                                                     </a>`;
                        }
                        buktiKlarifikasiHtml += '</div>';
                    }
                    $('#buktiKlarifikasiContainer').html(buktiKlarifikasiHtml);
                    $('#detailTindakLanjutHumasContent').val(data.TINDAK_LANJUT_HUMAS || '');

                    var buktiContainer = $('#buktiKlarifikasiContainer');
                    buktiContainer.html('');

                    let displayedFileCount = 0;

                    if (data.FILE_PENGADUAN && data.FILE_PENGADUAN.trim() !== '') {
                        const filePaths = data.FILE_PENGADUAN.split(';');

                        filePaths.forEach(function(filePath) {
                            const trimmedPath = filePath.trim();
                            if (trimmedPath === '') return;

                            let finalPath = trimmedPath;
                            if (!trimmedPath.includes('/')) {
                                finalPath = 'bukti_klarifikasi/' + trimmedPath;
                            }

                            if (finalPath.startsWith('bukti_klarifikasi/')) {

                                displayedFileCount++;

                                var fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + finalPath;
                                var fileName = trimmedPath.split(/[\\/]/).pop();

                                var buktiHtml = '<div class="file-klarifikasi-item" style="max-width: 150px;">';

                                if (/\.(jpeg|jpg|gif|png)$/i.test(fileName)) {
                                    buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}">
                                                    <img src="${fileUrl}" alt="Bukti Foto" class="img-fluid rounded mb-1" style="height: 100px; width: 100%; object-fit: cover;">
                                                    <small class="d-block text-truncate">${fileName}</small>
                                                </a>`;
                                } else if (/\.pdf$/i.test(fileName)) {
                                    buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}">
                                                    <i class="bi bi-file-earmark-pdf display-4 text-danger mb-1"></i>
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
                            }
                        });

                    } if (displayedFileCount === 0) {
                        buktiContainer.html('<p class="text-muted p-2">Tidak ada file bukti klarifikasi.</p>');
                    }
                    modalInstance.show();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error, xhr.responseText);
                    console.error("Error fetching complaint details: ", xhr.responseText);
                    var errorMsg = 'Gagal memuat detail pengaduan.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        $('#detailModal').on('hidden.bs.modal', function () {});
});

