<!DOCTYPE html>
<html>

<head>
    <title>Detail Laporan Pengaduan - {{ $data[0]->ID_COMPLAINT }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .section-title {
            background-color: #00B9AD;
            color: white;
            padding: 8px 12px;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .content-box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 15px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .file-list {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 15px;
        }

        .file-list li {
            padding: 0px;
            border-bottom: 1px solid #eee;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            color: white;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-info {
            background-color: #17a2b8;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .image-table td:last-child {
            padding-right: 0;
        }

        .image-gallery::after {
            content: "";
            display: table;
            clear: both;
        }

        .image-preview {
            float: left;
            width: 75px;
            height: 75px;
            object-fit: cover;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    @php
        $laporan = $data[0];
    @endphp

    <div class="container">
        <div class="header">
            <h2>Detail Laporan Pengaduan</h2>
            <p>Sistem Informasi Pengaduan RSHS Bandung</p>
        </div>

        <table class="info-table">
            <tr>
                <td>ID Pengaduan</td>
                <td><strong>{{ $laporan->ID_COMPLAINT }}</strong></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    @php
                        $statusClass = 'bg-secondary';
                        if ($laporan->STATUS === 'Open') {
                            $statusClass = 'bg-success';
                        } elseif ($laporan->STATUS === 'On Progress') {
                            $statusClass = 'bg-info';
                        } elseif ($laporan->STATUS === 'Menunggu Konfirmasi') {
                            $statusClass = 'bg-warning';
                        } elseif (in_array($laporan->STATUS, ['Close', 'Banding'])) {
                            $statusClass = 'bg-danger';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $laporan->STATUS }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Informasi Pengaduan</div>
        <table class="info-table">
            <tr>
                <td>Judul Pengaduan</td>
                <td>{{ $laporan->JUDUL_COMPLAINT ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengaduan</td>
                <td>{{ Carbon\Carbon::parse($laporan->TGL_COMPLAINT)->translatedFormat('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <td>Nama Pelapor</td>
                <td>{{ $laporan->NAME ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>{{ $laporan->NO_TLPN ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Medrec</td>
                <td>{{ $laporan->NO_MEDREC ?? '-' }}</td>
            </tr>
            <tr>
                <td>Media Pengaduan</td>
                <td>{{ $laporan->jenisMedia->JENIS_MEDIA ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Laporan</td>
                <td>{{ $laporan->jenisLaporan->JENIS_LAPORAN ?? '-' }}</td>
            </tr>
            <tr>
                <td>Klasifikasi</td>
                <td>{{ $laporan->klasifikasiPengaduan->KLASIFIKASI_PENGADUAN ?? '-' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja Tujuan</td>
                <td>{{ $laporan->unit_kerja_list->isNotEmpty() ? $laporan->unit_kerja_list->pluck('NAMA_BAGIAN')->implode(', ') : 'Belum dipilih' }}
                </td>
            </tr>
            <tr>
                <td>Petugas Pelapor</td>
                <td>{{ $laporan->PETUGAS_PELAPOR ?? '-' }}</td>
            </tr>
        </table>

        <strong>Deskripsi Pengaduan</strong>
        <div class="content-box">{{ $laporan->ISI_COMPLAINT ?? '-' }}</div>

        <strong>Rangkuman Permasalahan</strong>
        <div class="content-box">{{ $laporan->PERMASALAHAN ?? '-' }}</div>

        <strong>File Pengaduan</strong>
        <div class="content-box">
            @if ($laporan->pengaduan_files && count(array_filter($laporan->pengaduan_files)) > 0)
                <table class="image-table">
                    <tr>
                        <div class="image-gallery">
                            @foreach ($laporan->pengaduan_files as $file)
                                @php
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ public_path('storage/' . $file) }}" class="image-preview"
                                        alt="{{ basename($file) }}">
                                @else
                                    <ul class="file-list">
                                        <li>{{ basename($file) }}</li>
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </tr>
                </table>
            @else
                Tidak ada file.
            @endif
        </div>

        <div class="section-title">Evaluasi & Penyelesaian</div>
        <table class="info-table">
            <tr>
                <td>Grading</td>
                <td>{{ $laporan->GRANDING ?? 'Belum Dinilai' }}</td>
            </tr>
            <tr>
                <td>Tanggal Evaluasi</td>
                <td>{{ $laporan->TGL_EVALUASI ? Carbon\Carbon::parse($laporan->TGL_EVALUASI)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td>Tanggal Tindak Lanjut</td>
                <td>{{ $laporan->TGL_TINDAK_LANJUT_HUMAS ? Carbon\Carbon::parse($laporan->TGL_TINDAK_LANJUT_HUMAS)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td>Tanggal Selesai</td>
                <td>{{ $laporan->TGL_SELESAI ? Carbon\Carbon::parse($laporan->TGL_SELESAI)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td>Penyelesaian</td>
                <td>{{ $laporan->penyelesaianPengaduan->PENYELESAIAN_PENGADUAN ?? '-' }}</td>
            </tr>
        </table>

        <strong>Klarifikasi Unit</strong>
        <div class="content-box">
            @if ($laporan->klarifikasi_list_processed && count($laporan->klarifikasi_list_processed) > 0)
                @foreach ($laporan->klarifikasi_list_processed as $item)
                    <p>
                        <strong>[{{ $item['nama_bagian'] ?? 'Unit' }}]-{{ isset($item['tanggal']) ? Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') : '' }}</strong>
                        Oleh: {{ $item['petugas'] ?? '-' }}
                        Isi Klarifikasi: {{ $item['klarifikasi'] ?? '-' }}
                    </p>
                    @if (!$loop->last)
                        <hr>
                    @endif
                @endforeach
            @else
                Belum ada klarifikasi.
            @endif
        </div>

        <strong>File Bukti Klarifikasi</strong>
        <div class="content-box">
            @if ($laporan->klarifikasi_files_processed && count(array_filter($laporan->klarifikasi_files_processed)) > 0)
                <div class="image-gallery">
                    @foreach ($laporan->klarifikasi_files_processed as $file)
                        @php
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        @if ($isImage)
                            <img src="{{ public_path('storage/' . $file) }}" class="image-preview"
                                alt="{{ basename($file) }}">
                        @else
                            <ul class="file-list">
                                <li>{{ basename($file) }}</li>
                            </ul>
                        @endif
                    @endforeach
                </div>
            @else
                Tidak ada file.
            @endif
        </div>

        <strong>Tindak Lanjut Humas</strong>
        <div class="content-box">{{ $laporan->TINDAK_LANJUT_HUMAS ?? '-' }}</div>

        <strong>File Tindak Lanjut Humas</strong>
        <div class="content-box">
            @if ($laporan->tindak_lanjut_files && count(array_filter($laporan->tindak_lanjut_files)) > 0)
                <div class="image-gallery">
                    @foreach ($laporan->tindak_lanjut_files as $file)
                        @php
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        @if ($isImage)
                            <img src="{{ public_path('storage/' . $file) }}" class="image-preview"
                                alt="{{ basename($file) }}">
                        @else
                            <ul class="file-list">
                                <li>{{ basename($file) }}</li>
                            </ul>
                        @endif
                    @endforeach
                </div>
            @else
                Tidak ada file.
            @endif
        </div>

    </div>
</body>

</html>
