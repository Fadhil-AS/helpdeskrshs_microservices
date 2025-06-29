<div class="container rounded container-tabel my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Admin Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Kelola data unit kerja, struktur organisasi, dan admin unit kerja</p>
    </div>
    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 ">
                <div class="grup-tombol">
                    <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                        data-bs-target="#modalTambahAdmin">
                        <i class="bi bi-plus-circle"></i> Tambah Admin Unit Kerja
                    </button>
                </div>
                <form id="filterForm" action="{{ route('humas.unit-kerja-humas') }}" method="GET"
                    class="d-flex flex-wrap gap-2 grup-tombol align-items-center">
                    <select name="filter_unit" class="form-select" style="width: 150px;">
                        <option value="">Semua Unit</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->ID_BAGIAN }}"
                                {{ request('filter_unit') == $parent->ID_BAGIAN ? 'selected' : '' }}>
                                {{ $parent->NAMA_BAGIAN }}
                            </option>
                        @endforeach
                    </select>

                    <select name="filter_status" class="form-select" style="width: 150px;">
                        <option value="">Semua Status</option>
                        <option value="Y" {{ request('filter_status') == 'Y' ? 'selected' : '' }}>Tervalidasi
                        </option>
                        <option value="N" {{ request('filter_status') == 'N' ? 'selected' : '' }}>Belum Tervalidasi
                        </option>
                    </select>
                </form>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari Unit Kerja...">
            </div>
        </div>
        <!-- Table -->
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
                <tbody>
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
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetailAdmin"
                                    title="Detail Admin" data-admin='{{ json_encode($admin) }}'>
                                    <i class="bi bi-eye me-2"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditAdmin"
                                    title="Edit Admin" data-admin='{{ json_encode($admin) }}'>
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
        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3 page-tabel">
            {{ $admins->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
