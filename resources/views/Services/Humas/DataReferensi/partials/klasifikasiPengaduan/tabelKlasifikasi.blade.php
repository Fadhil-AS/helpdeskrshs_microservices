<div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
    <h5 class="mb-1">Klasifikasi Pengaduan</h5>
    <p class="mb-0">Kelola daftar klasifikasi pengaduan yang digunakan dalam sistem</p>
</div>
<div class="bg-white p-3 rounded-bottom shadow-sm">
    <form method="POST" action="{{ route('humas.klasifikasi-pengaduan.store') }}">
        @csrf
        <div class="d-flex flex-column flex-md-row gap-2 align-items-start mb-3" style="max-width: 100%;">
            <input type="text" class="form-control" placeholder="Masukkan Klasifikasi Pengaduan Baru"
                style="max-width: 400px;" name="KLASIFIKASI_PENGADUAN" required>
            <button class="btn btn-tambah-pengaduan text-white" type="submit">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </button>
            <div class="invalid-feedback d-none"></div>
        </div>
    </form>
    <div class="table-responsive" id="tabel-klasifikasi-container">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID Klasifikasi</th>
                    <th>Klasifikasi Pengaduan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($klasifikasiPengaduan as $item)
                    <tr data-id="{{ $item->ID_KLASIFIKASI }}">
                        <td><strong>{{ $item->ID_KLASIFIKASI }}</strong></td>
                        <td class="text-uppercase klasifikasi-cell">
                            <span class="editable-text">{{ $item->KLASIFIKASI_PENGADUAN }}</span>
                            <input type="text" class="form-control form-control-sm editable-input d-none"
                                value="{{ $item->KLASIFIKASI_PENGADUAN }}">
                        </td>
                        <td>
                            @if ($item->STATUS == '1')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="view-mode-actions">
                                <a href="#" class="btn-inline-edit me-2" title="Edit"><i
                                        class="bi bi-pencil-square"></i></a>
                                <a href="#" class="btn-inline-delete text-danger" title="Hapus"
                                    data-url="{{ route('humas.klasifikasi-pengaduan.destroy', $item->ID_KLASIFIKASI) }}"
                                    data-name="{{ $item->KLASIFIKASI_PENGADUAN }}"><i class="bi bi-trash"></i></a>
                            </div>
                            <div class="edit-mode-actions d-none">
                                <a href="#" class="btn-inline-save text-success me-2" title="Simpan"
                                    data-url="{{ route('humas.klasifikasi-pengaduan.update', $item->ID_KLASIFIKASI) }}"><i
                                        class="bi bi-check-lg fs-5"></i></a>
                                <a href="#" class="btn-inline-cancel text-danger" title="Batal"><i
                                        class="bi bi-x-lg fs-5"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3 page-tabel">
        {{-- <nav aria-label="Page navigation example">
            <ul class="pagination mb-0">
                <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span
                            aria-hidden="true">&laquo;</span></a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span
                            aria-hidden="true">&raquo;</span></a></li>
            </ul>
        </nav> --}}
        {{ $klasifikasiPengaduan->links() }}
    </div>
</div>
