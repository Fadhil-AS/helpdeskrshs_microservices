<div class="container rounded container-tabel my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Kelola data unit kerja, struktur organisasi, dan admin unit kerja</p>
    </div>
    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                    data-bs-target="#modalTambahUnitKerja">
                    <i class="bi bi-plus-circle"></i> Tambah Unit Kerja
                </button>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari Unit Kerja...">
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID Bagian</th>
                        <th>Nama Bagian</th>
                        <th>Nama Singular</th>
                        <th>Nama Alternatif</th>
                        <th>Status</th>
                        <th>Tanggal Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parents as $parent)
                        @include('Services.Humas.unitKerjaHumas.partials.unitKerjaHumas._unitKerjaRow', [
                            'unit' => $parent,
                            'children' => $children,
                            'level' => 0,
                        ])
                    @endforeach
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
            {{ $parents->appends(request()->except('admin_page'))->links() }}
        </div>
    </div>
</div>
