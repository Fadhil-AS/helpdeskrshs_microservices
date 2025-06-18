<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        .custom-upload-btn {
            background-color: #60C0D0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .custom-upload-btn:hover {
            background-color: #4EAEBF;
            /* warna lebih gelap sedikit dari #60C0D0 */
            color: white;
        }
    </style>
</head>

<body>
    <div class="container rounded container-tabel my-5 pt-2">
        <!-- Header Box -->
        <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
            <h3 class="mb-1">Sistem Informasi Pengaduan RSHS Bandung</h3>
            <h5 class="mb-0">Manajemen Data Chatbot</h5>
        </div>

        <!-- Filter & Action -->
        <div class="bg-white p-3 rounded-bottom shadow-sm">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
                <div class="d-flex flex-wrap gap-2 grup-tombol">
                    <form method="POST" action="/upload" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="file_input">Tambahkan File (.xlsx)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                    id="file_input" name="file" accept=".xlsx" required>
                                <button type="submit" class="btn custom-upload-btn">
                                    <i class="bi bi-plus-circle"></i> Upload
                                </button>

                            </div>
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="border-bottom">
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($files->count())
                            @foreach ($files as $index => $file)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>{{ $file->nama_file }}</td>
                                    <td>
                                        <form action="{{ route('delete.file', $file->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <p>Belum ada file yang diunggah.</p>
                        @endif
                    </tbody>
                </table>
            </div>



        </div>

        @if (session('status'))
            <p>{{ session('status') }}</p>
        @endif
    </div>

    @include('Services.Chatbot.mainChatbot')
</body>

</html>
