<div class="p-4 rounded-top" style="background-color: #00B9AD; color: white; margin-top: 6vh;">
    <h5 class="mb-1">Jenis Media</h5>
    <p class="mb-0">Kelola daftar jenis media yang digunakan untuk pengaduan</p>
</div>
<div class="bg-white p-3 rounded-bottom shadow-sm">
    <form method="POST" action="{{ route('humas.jenis-media.store') }}">
        <div class="d-flex flex-column flex-md-row gap-2 align-items-start mb-3" style="max-width: 100%;">
            @csrf
            <input type="text" class="form-control" placeholder="Masukkan Jenis Pengaduan Baru"
                style="max-width: 400px;" name="JENIS_MEDIA" required>
            <button class="btn btn-tambah-pengaduan text-white" type="submit">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </button>
        </div>
    </form>
    <div class="table-responsive" id="tabel-jenis-media-container">
        <table class="table align-middle">
            <thead class="border-bottom">
                <tr class="text-nowrap">
                    <th>Jenis Media</th>
                    <th class="text-end ps-5">Status</th>
                    <th class="text-end pe-4">Aksi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisMedia as $item)
                    <tr>
                        <td class="text-uppercase jenis-media-cell">
                            <span class="editable-text">{{ $item->JENIS_MEDIA }}</span>
                            <input type="text"
                                class="form-control form-control-sm editable-input d-none editable-input"
                                value="{{ $item->JENIS_MEDIA }}" name="JENIS_MEDIA">
                        </td>
                        <td class="text-end">
                            @if ($item->STATUS == '1')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-2">
                            <div class="view-mode-actions d-inline-block">
                                <a href="#" class="btn-inline-edit me-2"><i class="bi bi-pencil-square"></i></a>
                                <a href="#" class="btn-inline-delete text-danger"
                                    data-url="{{ route('humas.jenis-media.destroy', $item->ID_JENIS_MEDIA) }}"
                                    data-name="{{ $item->JENIS_MEDIA }}"><i class="bi bi-trash"></i></a>
                            </div>
                            <div class="edit-mode-actions d-inline-block d-none">
                                <a href="#" class="btn-inline-save text-success me-2" title="Simpan"
                                    data-url="{{ route('humas.jenis-media.update', $item->ID_JENIS_MEDIA) }}"><i
                                        class="bi bi-check-lg fs-5"></i></a>
                                <a href="#" class="btn-inline-cancel text-danger"><i
                                        class="bi bi-x-lg fs-5"></i></a>
                            </div>
                        </td>
                        <td></td>
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
        {{ $jenisMedia->links() }}
    </div>
</div>
