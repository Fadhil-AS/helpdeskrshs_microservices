console.log('fungsiModalDetail.js: File is being parsed.');

$(document).ready(function () {
    console.log('fungsiModalDetail.js: Document is ready, jQuery is available.');

    var detailModalElement = document.getElementById('detailModal');
    if (!detailModalElement) {
        console.error('Elemen modal #detailModal tidak ditemukan di halaman.');
        return;
    }
    var modalInstance = bootstrap.Modal.getOrCreateInstance(detailModalElement);

    $(document).on('click', '.view-detail-btn', function () {
        var complaintId = $(this).data('id');

        if (typeof detailUrlTemplate === 'undefined') {
            console.error('Error: variabel detailUrlTemplate tidak ditemukan.');
            alert('Terjadi kesalahan konfigurasi halaman.');
            return;
        }

        var url = detailUrlTemplate.replace(':id', complaintId);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log('Data diterima dari server:', data);

                $('#detailIdComplaint').text('ID: ' + (data.ID_COMPLAINT || '-'));
                $('#detailStatus').removeClass().addClass('badge').text(data.STATUS || 'N/A');
                if (data.STATUS === 'Open') $('#detailStatus').addClass('bg-success');
                else if (data.STATUS === 'On Progress') $('#detailStatus').addClass('bg-info');
                else if (data.STATUS === 'Menunggu Konfirmasi') $('#detailStatus').addClass('bg-warning');
                else if (data.STATUS === 'Close' || data.STATUS === 'Banding') $('#detailStatus').addClass('bg-danger text-light');
                else $('#detailStatus').addClass('bg-secondary');

                $('#detailJudul').text(data.JUDUL_COMPLAINT || '-');
                var tglComplaint = data.TGL_COMPLAINT ? new Date(data.TGL_COMPLAINT).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                $('#detailTanggalPengaduan').text(tglComplaint);
                $('#detailNoTelp').text(data.NO_TLPN || '-');

                var klarifikasiStatusBadge = $('#detailKlarifikasiStatus');
                klarifikasiStatusBadge.removeClass().addClass('badge');
                if (data.EVALUASI_COMPLAINT && data.EVALUASI_COMPLAINT.trim() !== '') {
                    klarifikasiStatusBadge.text('Sudah').addClass('bg-info');
                } else {
                    klarifikasiStatusBadge.text('Belum').addClass('bg-danger text-light');
                }

                $('#detailNamaPelapor').text(data.NAME || '-');
                $('#detailGrading').removeClass().addClass('badge').text(data.GRANDING || 'Belum dipilih Grading');
                if (data.GRANDING === 'Merah') $('#detailGrading').addClass('bg-danger text-light');
                else if (data.GRANDING === 'Kuning') $('#detailGrading').addClass('bg-warning text-light');
                else if (data.GRANDING === 'Hijau') $('#detailGrading').addClass('bg-success text-light');
                else $('#detailGrading').addClass('bg-secondary text-light');

                $('#detailNoMedrec').text(data.NO_MEDREC || '-');
                $('#detailUnitKerja').text(data.unit_kerja ? data.unit_kerja.NAMA_BAGIAN : '-');
                $('#detailMediaPengaduan').text(data.jenis_media ? data.jenis_media.JENIS_MEDIA : '-');
                $('#detailJenisLaporan').text(data.jenis_laporan ? data.jenis_laporan.JENIS_LAPORAN : '-');
                $('#detailKlasifikasiPengaduan').text(data.klasifikasi_pengaduan ? data.klasifikasi_pengaduan.KLASIFIKASI_PENGADUAN : '-');
                $('#detailPetugasPelapor').text(data.PETUGAS_PELAPOR || 'Admin Humas');
                $('#detailDeskripsiPengaduanContent').text(data.ISI_COMPLAINT || '-');
                $('#detailRangkumanPermasalahanContent').text(data.PERMASALAHAN || '-');
                $('#detailPetugasEvaluasi').text(data.PETUGAS_EVALUASI || '-');
                var tglEvaluasi = data.TGL_EVALUASI ? new Date(data.TGL_EVALUASI).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                $('#detailTanggalEvaluasi').text(tglEvaluasi);
                $('#detailPenyelesaianPengaduan').text(data.penyelesaian_pengaduan ? data.penyelesaian_pengaduan.NAMA_PENYELESAIAN : '-');
                var tglSelesai = data.TGL_SELESAI ? new Date(data.TGL_SELESAI).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
                $('#detailTanggalSelesai').text(tglSelesai);
                $('#detailKlarifikasiUnitContent').val(data.KLARIFIKASI_UNIT_TEXT || '');
                $('#detailTindakLanjutHumasContent').val(data.TINDAK_LANJUT_HUMAS || '');

                var buktiContainer = $('#buktiKlarifikasiContainer');
                buktiContainer.html('');
                if (data.klarifikasi_files && data.klarifikasi_files.length > 0) {
                    data.klarifikasi_files.forEach(function(filePath) {
                        if (!filePath || filePath.trim() === '') return;
                        var fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + filePath.trim();
                        var fileName = filePath.split(/[\\/]/).pop();
                        var buktiHtml = '<div class="file-klarifikasi-item" style="max-width: 150px;">';
                        if (/\.(jpeg|jpg|gif|png)$/i.test(fileName)) {
                            buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}"><img src="${fileUrl}" alt="Bukti Foto" class="img-fluid rounded mb-1" style="height: 100px; width: 100%; object-fit: cover;"><small class="d-block text-truncate">${fileName}</small></a>`;
                        } else {
                            buktiHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}"><i class="bi bi-file-earmark-text display-4 text-secondary mb-1"></i><small class="d-block text-truncate">${fileName}</small></a>`;
                        }
                        buktiHtml += '</div>';
                        buktiContainer.append(buktiHtml);
                    });
                } else {
                    buktiContainer.html('<p class="text-muted p-2">Tidak ada file bukti klarifikasi.</p>');
                }

                var tindakLanjutContainer = $('#fileTindakLanjutContainer');
                tindakLanjutContainer.html('');
                if (data.tindak_lanjut_files && data.tindak_lanjut_files.length > 0) {
                    data.tindak_lanjut_files.forEach(function(filePath) {
                        if (!filePath || filePath.trim() === '') return;
                        var fileUrl = (typeof storageBaseUrl !== 'undefined' ? storageBaseUrl : '/storage') + '/' + filePath.trim();
                        var fileName = filePath.split(/[\\/]/).pop();
                        var fileHtml = '<div class="file-klarifikasi-item" style="max-width: 150px;">';
                        if (/\.(jpeg|jpg|gif|png)$/i.test(fileName)) {
                            fileHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" title="${fileName}"><img src="${fileUrl}" alt="File Tindak Lanjut" class="img-fluid rounded mb-1" style="height: 100px; width: 100%; object-fit: cover;"><small class="d-block text-truncate">${fileName}</small></a>`;
                        } else {
                            fileHtml += `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark d-flex flex-column align-items-center" title="${fileName}"><i class="bi bi-file-earmark-text display-4 text-secondary mb-1"></i><small class="d-block text-truncate">${fileName}</small></a>`;
                        }
                        fileHtml += '</div>';
                        tindakLanjutContainer.append(fileHtml);
                    });
                } else {
                    tindakLanjutContainer.html('<p class="text-muted m-0">Tidak ada file tindak lanjut.</p>');
                }

                modalInstance.show();

            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error, xhr.responseText);
                var errorMsg = 'Gagal memuat detail pengaduan.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                alert(errorMsg);
            }
        });
    });

    // Event ini akan berjalan setiap kali modal selesai ditutup.
    $('#detailModal').on('hidden.bs.modal', function () {
        console.log('Detail modal telah ditutup.');
    });
});
