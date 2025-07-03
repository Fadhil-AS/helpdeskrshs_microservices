<div class="table-responsive">
    <table class="table align-middle">
        <thead class="border-bottom">
            <tr class="text-nowrap">
                <th>ID</th>
                <th>Nama Direksi</th>
                <th>Nomor Telepon</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allDireksi as $direksi)
                <tr>
                    <td><strong>{{ $direksi->ID_DIREKSI }}</strong></td>
                    <td>{{ $direksi->NAMA }}</td>
                    <td><i class="bi bi-telephone me-2"></i>{{ $direksi->NO_TLPN }}</td>
                    <td><span class="badge bg-info">{{ $direksi->KET }}</span></td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi"
                            data-id="{{ $direksi->ID_DIREKSI }}" data-nama="{{ $direksi->NAMA }}"
                            data-no_tlpn="{{ $direksi->NO_TLPN }}" data-ket="{{ $direksi->KET }}">
                            <i class="bi bi-pencil-square me-2"></i>
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                            data-id="{{ $direksi->ID_DIREKSI }}" data-nama="{{ $direksi->NAMA }}">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-end mt-3 page-tabel">
    {!! $allDireksi->links() !!}
</div>
