<div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
    <h5 class="mb-1">Penyelesaian Pengaduan</h5>
    <p class="mb-0">Kelola daftar jenis penyelesaian pengaduan yang digunakan dalam sistem</p>
</div>
<div class="bg-white p-3 rounded-bottom shadow-sm">
    <form method="POST" action="{{ route('humas.penyelesaian-pengaduan.store') }}">
        <div class="d-flex flex-column flex-md-row gap-2 align-items-start mb-3" style="max-width: 100%;">
            @csrf
            <input type="text" class="form-control" placeholder="Masukkan Jenis Penyelesaian Pengaduan Baru"
                style="max-width: 400px;" name="PENYELESAIAN_PENGADUAN" required>
            <button class="btn btn-tambah-pengaduan text-white" type="submit">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </button>
        </div>
    </form>
    <div class="table-responsive" id="tabel-penyelesaian-pengaduan-container">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID Penyelesaian</th>
                    <th>Penyelesaian Pengaduan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyelesaianPengaduan as $item)
                    <tr>
                        <td><strong>{{ $item->ID_PENYELESAIAN }}</strong></td>
                        <td class="text-uppercase">
                            <span class="editable-text">{{ $item->PENYELESAIAN_PENGADUAN }}</span>
                            <input type="text" class="form-control form-control-sm editable-input d-none"
                                value="{{ $item->PENYELESAIAN_PENGADUAN }}" name="PENYELESAIAN_PENGADUAN">
                        </td>
                        <td>
                            @if ($item->STATUS == '1')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="view-mode-actions d-inline-block">
                                <a href="#" class="btn-inline-edit me-2"><i class="bi bi-pencil-square"></i></a>
                                <a href="#" class="btn-inline-delete text-danger"
                                    data-url="{{ route('humas.penyelesaian-pengaduan.destroy', $item->ID_PENYELESAIAN) }}"
                                    data-name="{{ $item->PENYELESAIAN_PENGADUAN }}"><i class="bi bi-trash"></i></a>
                            </div>
                            <div class="edit-mode-actions d-inline-block d-none">
                                <a href="#" class="btn-inline-save text-success me-2"
                                    data-url="{{ route('humas.penyelesaian-pengaduan.update', $item->ID_PENYELESAIAN) }}"
                                    title="Simpan"><i class="bi bi-check-lg fs-5"></i></a>
                                <a href="#" class="btn-inline-cancel text-danger"><i
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
        {{ $penyelesaianPengaduan->links() }}
    </div>
</div>
