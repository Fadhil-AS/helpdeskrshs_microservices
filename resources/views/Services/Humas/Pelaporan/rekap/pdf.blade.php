<!DOCTYPE html>
<html>

<head>
    <title>Rekap Laporan Pengaduan</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Rekap Laporan Pengaduan</h2>
    @if ($data->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Tgl Masuk</th>
                    <th>Media</th>
                    <th>Status</th>
                    <th>Unit Kerja</th>
                    <th>Grading</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $laporan)
                    <tr>
                        <td>{{ $laporan->ID_COMPLAINT }}</td>
                        <td>{{ $laporan->JUDUL_COMPLAINT ?? 'Belum ada judul' }}</td>
                        <td>{{ \Carbon\Carbon::parse($laporan->TGL_COMPLAINT)->format('d-m-Y') }}</td>
                        <td>{{ $laporan->jenisMedia->JENIS_MEDIA }}</td>
                        <td>{{ $laporan->STATUS }}</td>
                        <td>
                            @if ($laporan->unit_kerja_list->isNotEmpty())
                                {{ $laporan->unit_kerja_list->pluck('NAMA_BAGIAN')->implode(', ') }}
                            @else
                                Belum dipilih unit kerja
                            @endif
                        </td>
                        <td>{{ $laporan->GRANDING ?? 'Belum dipilih Grading' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>Tidak Ada Data Ditemukan</h3>
            <p>Tidak ada data laporan yang cocok dengan kriteria filter yang Anda pilih.</p>
        </div>

    @endif
</body>

</html>
