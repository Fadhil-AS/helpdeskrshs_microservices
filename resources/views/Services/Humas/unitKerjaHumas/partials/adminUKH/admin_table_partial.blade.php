<div class="table-responsive">
    <table class="table align-middle">
        <thead class="border-bottom">
            <tr class="text-nowrap">
                <th>No Register</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Unit Kerja</th>
                <th>NIP</th>
                <th>Status</th>
                <th>Tanggal Input</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="adminTableBody">
            @forelse ($admins as $admin)
                <tr>
                    <td><strong>{{ $admin->NO_REGISTER }}</strong></td>
                    <td>{{ $admin->USERNAME }}</td>
                    <td>{{ $admin->NAME }}</td>
                    <td>{{ $admin->unitKerja->NAMA_BAGIAN ?? 'N/A' }}</td>
                    <td>{{ $admin->NIP }}</td>
                    <td>
                        @if ($admin->VALIDASI == 'Y')
                            <span class="badge bg-success">Tervalidasi</span>
                        @else
                            <span class="badge bg-warning">Belum tervalidasi</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($admin->TGL_INSROW)->locale('id')->isoFormat('DD MMMM YYYY') }}
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetailAdmin" title="Detail Admin"
                            data-admin='{{ json_encode($admin) }}'>
                            <i class="bi bi-eye me-2"></i>
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditAdmin" title="Edit Admin"
                            data-admin='{{ json_encode($admin) }}'>
                            <i class="bi bi-pencil-square me-2"></i>
                        </a>
                        <a href="#" class="reset-password-btn me-2" title="Reset Password"
                            data-id="{{ $admin->NO_REGISTER }}" data-name="{{ $admin->NAME }}">
                            <i class="bi bi-arrow-counterclockwise text-primary"></i>
                        </a>
                        <form action="{{ route('humas.user-complaint.destroy', $admin) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin \'{{ $admin->NAME }}\'?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-link text-danger p-0" title="Hapus Admin"
                                style="vertical-align: baseline;" onclick="event.stopPropagation()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <p class="text-muted">Tidak ada data admin yang ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- <div class="d-flex justify-content-end mt-3 page-tabel">
    {{ $admins->appends(request()->except('page'))->links() }}
</div> --}}

<div id="paginationLinks">
    <div class="d-flex justify-content-end mt-3 page-tabel">
        {{ $admins->links() }}
    </div>
</div>
