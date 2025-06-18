<div class="container rounded container-tabel my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Dashboard Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Penanganan pengaduan dan klarifikasi unit kerja</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <form action="{{ route('unitKerja.dashboard') }}" method="GET" class="mb-3">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
                <div class="d-flex flex-wrap gap-2 grup-tombol">
                    <select class="selectpicker" data-style="btn-reset" onchange="this.form.submit()" name="status">
                        <option value="">Semua Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="On Progress" {{ request('status') == 'On Progress' ? 'selected' : '' }}>On
                            Progress</option>
                        <option value="Menunggu Konfirmasi"
                            {{ request('status') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi
                        </option>
                        <option value="Close" {{ request('status') == 'Close' ? 'selected' : '' }}>Close</option>
                        <option value="Banding" {{ request('status') == 'Banding' ? 'selected' : '' }}>Banding</option>
                    </select>
                    <a href="{{ route('unitKerja.dashboard') }}" class="btn btn-reset">
                        <i class="bi bi-filter"></i>Reset
                    </a>
                </div>
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Cari Pengaduan..." value="{{ request('search') }}">
                </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="border-bottom">
                <tr class="text-nowrap">
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Media</th>
                    <th>Klarifikasi</th>
                    <th>Status</th>
                    <th>Grading</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataComplaint as $complaint)
                    <tr>
                        <td><strong>{{ $complaint->ID_COMPLAINT }}</strong></td>
                        <td>{{ $complaint->JUDUL_COMPLAINT ?? 'Belum ada judul' }}</td>
                        <td>{{ $complaint->jenisMedia?->JENIS_MEDIA ?? '-' }}</td>
                        @if (!empty($complaint->EVALUASI_COMPLAINT))
                            <td><span class="badge bg-info">Sudah</span></td>
                        @else
                            <td><span class="badge bg-danger text-light">Belum</span></td>
                        @endif
                        <td>
                            @if ($complaint->STATUS == 'Open')
                                <span class="badge bg-success">Open</span>
                            @elseif ($complaint->STATUS == 'On Progress')
                                <span class="badge bg-warning">On Progress</span>
                            @elseif ($complaint->STATUS == 'Menunggu Konfirmasi')
                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                            @elseif ($complaint->STATUS == 'Close')
                                <span class="badge bg-danger">Close</span>
                            @elseif ($complaint->STATUS == 'Banding')
                                <span class="badge bg-danger">Banding</span>
                            @else
                                <span class="badge bg-secondary">{{ $complaint->STATUS ?? '-' }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($complaint->GRANDING == 'Merah')
                                <span class="badge bg-danger text-light">Merah</span>
                            @elseif ($complaint->GRANDING == 'Kuning')
                                <span class="badge bg-warning text-light">Kuning</span>
                            @elseif ($complaint->GRANDING == 'Hijau')
                                <span class="badge bg-success text-light">Hijau</span>
                            @else
                                <span
                                    class="badge bg-light text-dark">{{ $complaint->GRANDING ?? 'Belum dinilai' }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal"
                                data-id="{{ $complaint->ID_COMPLAINT }}" title="Lihat Detail">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                                data-id="{{ $complaint->ID_COMPLAINT }}" title="Isi Klarifikasi">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="mb-0">Data pengaduan tidak ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3 page-tabel">
        {{-- <nav aria-label="Page navigation example">
            <ul class="pagination mb-0">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav> --}}
        {{ $dataComplaint->links() }}
    </div>
</div>
</div>
