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
                        @foreach ($paginatedParents as $parent)
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
                <input type="text" class="form-control border-start-0" placeholder="Cari Unit Kerja..."
                    id="search-admin-input" data-url="{{ route('humas.unit-kerja-humas') }}">
            </div>
        </div>
        <!-- Table -->
        <div id="admin-table-container">
            @include('Services.Humas.unitKerjaHumas.partials.adminUKH.contentTabel')
        </div>
    </div>
</div>
