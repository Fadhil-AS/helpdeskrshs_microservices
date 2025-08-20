<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Humas/Pelaporan/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Humas/Pelaporan/modalEdit.css') }}">
</head>

<body>
    @include('Services.Humas.partials.navbar')
    <div class="container rounded container-tabel my-5 pt-2">
        <!-- Header Box -->
        <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
            <h5 class="mb-1">Sistem Informasi Pengaduan RSHS Bandung</h5>
            <p class="mb-0">Manajemen Data Chatbot</p>
        </div>

        <!-- Filter & Action -->
        <div class="bg-white p-3 rounded-bottom shadow-sm">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
                <div class="d-flex flex-wrap gap-2 grup-tombol">
                    <form method="POST" action="{{ route('humas.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <label class="form-label fw-bold" for="file_input">Tambahkan File Chatbot</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                    id="file_input" name="file" accept=".xlsx" required>
                                <button type="submit" class="btn btn-tambah-pengaduan text-white">
                                    <i class="bi bi-plus-circle"></i> Upload
                                </button>
                            </div>
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">File chatbot berupa excel atau .xlsx</small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="border-bottom">
                        <tr class="text-nowrap">
                            <th style="width: 20%">No</th>
                            <th style="width: 60%">Nama File</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($files->count() > 0)
                            @foreach ($files as $index => $file)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td><i class="bi bi-file-earmark-excel me-2"></i>{{ $file->nama_file }}</td>
                                    <td>
                                        <form action="{{ route('humas.delete.file', $file->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada file yang diunggah.
                                </td>
                            </tr>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
